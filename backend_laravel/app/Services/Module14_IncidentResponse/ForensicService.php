<?php

namespace App\Services\Module14_IncidentResponse;

use App\Models\Module14_IncidentResponse\ForensicEvidence;

class ForensicService
{
    public function collectEvidence($incidentId, $data)
    {
        $evidence = ForensicEvidence::create([
            'incident_id' => $incidentId,
            'evidence_type' => $data['type'],
            'file_path' => $data['path'],
            'hash_value' => $this->calculateHash($data['path']),
            'collected_by' => auth()->id(),
            'collected_at' => now(),
            'description' => $data['description'] ?? null
        ]);
        
        $this->preserveChainOfCustody($evidence);
        
        return $evidence;
    }

    protected function calculateHash($filePath)
    {
        if (file_exists($filePath)) {
            return hash_file('sha256', $filePath);
        }
        return null;
    }

    protected function preserveChainOfCustody($evidence)
    {
        $chain = $evidence->chain_of_custody ?? [];
        $chain[] = [
            'timestamp' => now(),
            'handler' => auth()->user()->name,
            'action' => 'collected',
            'notes' => 'Evidence collected from source'
        ];
        
        $evidence->chain_of_custody = $chain;
        $evidence->save();
    }

    public function transferEvidence($evidenceId, $toUser)
    {
        $evidence = ForensicEvidence::findOrFail($evidenceId);
        
        $chain = $evidence->chain_of_custody ?? [];
        $chain[] = [
            'timestamp' => now(),
            'handler' => auth()->user()->name,
            'action' => 'transferred',
            'to' => $toUser,
            'notes' => 'Evidence transferred for analysis'
        ];
        
        $evidence->chain_of_custody = $chain;
        $evidence->save();
        
        return $evidence;
    }

    public function analyzeEvidence($evidenceId, $analysisResults)
    {
        $evidence = ForensicEvidence::findOrFail($evidenceId);
        
        $evidence->details = array_merge($evidence->details ?? [], [
            'analysis' => $analysisResults,
            'analyzed_at' => now(),
            'analyzed_by' => auth()->id()
        ]);
        
        $evidence->save();
        
        return $evidence;
    }
}