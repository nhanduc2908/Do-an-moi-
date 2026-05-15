<?php

namespace App\Services\Module22_EmailSecurity;

use Illuminate\Support\Facades\Http;

class EmailSandboxService
{
    protected $sandboxUrl = 'https://sandbox.security.local/analyze';

    public function analyzeAttachment($attachmentPath, $fileName)
    {
        $fileContent = file_get_contents($attachmentPath);
        
        // Check file signature
        $fileType = $this->detectFileType($fileContent);
        
        if ($this->isDangerousType($fileType)) {
            return [
                'safe' => false,
                'reason' => "Dangerous file type: {$fileType}",
                'action' => 'block'
            ];
        }
        
        // Extract macros from Office documents
        if (in_array($fileType, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            $hasMacros = $this->checkMacros($attachmentPath);
            if ($hasMacros) {
                return [
                    'safe' => false,
                    'reason' => 'Document contains macros',
                    'action' => 'quarantine'
                ];
            }
        }
        
        // Check with external sandbox
        $sandboxResult = $this->submitToSandbox($attachmentPath, $fileName);
        
        return $sandboxResult;
    }

    protected function detectFileType($content)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, $content);
        finfo_close($finfo);
        
        return $mime;
    }

    protected function isDangerousType($mime)
    {
        $dangerous = [
            'application/x-msdownload',
            'application/x-executable',
            'application/x-msdos-program',
            'application/vnd.microsoft.portable-executable'
        ];
        
        return in_array($mime, $dangerous);
    }

    protected function checkMacros($filePath)
    {
        // Extract and check for VBA macros
        $output = shell_exec("olevba {$filePath} 2>/dev/null | grep -i 'macro'");
        return !empty($output);
    }

    protected function submitToSandbox($filePath, $fileName)
    {
        try {
            $response = Http::attach('file', file_get_contents($filePath), $fileName)
                ->timeout(120)
                ->post($this->sandboxUrl);
            
            if ($response->successful()) {
                $result = $response->json();
                return [
                    'safe' => !$result['malicious'],
                    'score' => $result['score'] ?? 0,
                    'behavior' => $result['behavior'] ?? [],
                    'action' => $result['malicious'] ? 'block' : 'deliver'
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Sandbox analysis failed', ['error' => $e->getMessage()]);
        }
        
        return ['safe' => true, 'action' => 'deliver', 'note' => 'Sandbox unavailable'];
    }

    public function analyzeUrl($url)
    {
        $response = Http::get("{$this->sandboxUrl}/url", ['url' => $url]);
        
        if ($response->successful()) {
            $result = $response->json();
            return [
                'safe' => !$result['malicious'],
                'category' => $result['category'] ?? 'unknown',
                'reputation' => $result['reputation'] ?? 0
            ];
        }
        
        return ['safe' => true];
    }
}