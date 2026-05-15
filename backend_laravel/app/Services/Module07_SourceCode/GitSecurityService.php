<?php

namespace App\Services\Module07_SourceCode;

use Illuminate\Support\Facades\Process;

class GitSecurityService
{
    public function scanCommitHistory($repoPath)
    {
        $result = Process::run("cd {$repoPath} && git log --all --pretty=format:'%H|%an|%ae|%ad' --date=iso");
        
        $commits = [];
        $lines = explode("\n", $result->output());
        
        foreach ($lines as $line) {
            if (empty($line)) continue;
            
            list($hash, $author, $email, $date) = explode('|', $line);
            $commits[] = [
                'hash' => $hash,
                'author' => $author,
                'email' => $email,
                'date' => $date,
                'changes' => $this->getCommitChanges($repoPath, $hash)
            ];
        }
        
        return $commits;
    }

    public function detectSensitiveFiles($repoPath)
    {
        $sensitivePatterns = [
            '*.env', '*.key', '*.pem', '*.crt', '*.p12',
            '*.pwd', '*.secret', '*.token', 'config.php'
        ];
        
        $found = [];
        
        foreach ($sensitivePatterns as $pattern) {
            $result = Process::run("cd {$repoPath} && git ls-files '{$pattern}'");
            $files = array_filter(explode("\n", $result->output()));
            
            foreach ($files as $file) {
                $found[] = $file;
            }
        }
        
        return $found;
    }

    protected function getCommitChanges($repoPath, $commitHash)
    {
        $result = Process::run("cd {$repoPath} && git show --stat {$commitHash}");
        return $result->output();
    }
}