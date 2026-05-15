<?php

namespace App\Services\Module18_SecurityAwareness;

use App\Models\Module18_SecurityAwareness\PhishingSimulation;
use Illuminate\Support\Facades\Mail;

class PhishingSimulationService
{
    public function createSimulation($data)
    {
        $simulation = PhishingSimulation::create([
            'name' => $data['name'],
            'template' => $data['template'],
            'target_emails' => $data['target_emails'],
            'clicked_count' => 0,
            'reported_count' => 0,
            'submitted_data_count' => 0,
            'started_at' => $data['started_at'] ?? now(),
            'ended_at' => $data['ended_at'] ?? now()->addDays(7)
        ]);
        
        $this->sendPhishingEmails($simulation);
        
        return $simulation;
    }

    protected function sendPhishingEmails($simulation)
    {
        foreach ($simulation->target_emails as $email) {
            Mail::send("emails.phishing.{$simulation->template}", [], function($message) use ($email, $simulation) {
                $message->to($email)
                        ->subject($this->getSubject($simulation->template))
                        ->from('security-alert@company.com', 'Security Team');
            });
        }
    }

    public function trackClick($email, $simulationId)
    {
        $simulation = PhishingSimulation::find($simulationId);
        if ($simulation) {
            $simulation->clicked_count++;
            $simulation->save();
            
            $this->recordUserAction($email, $simulationId, 'clicked');
        }
        
        // Redirect to training page
        return redirect()->route('security.training.phishing');
    }

    public function trackReport($email, $simulationId)
    {
        $simulation = PhishingSimulation::find($simulationId);
        if ($simulation) {
            $simulation->reported_count++;
            $simulation->save();
            
            $this->recordUserAction($email, $simulationId, 'reported');
            $this->awardPositiveScore($email);
        }
        
        return ['success' => true];
    }

    public function trackDataSubmission($email, $simulationId, $data)
    {
        $simulation = PhishingSimulation::find($simulationId);
        if ($simulation) {
            $simulation->submitted_data_count++;
            $simulation->save();
            
            $this->recordUserAction($email, $simulationId, 'submitted', $data);
            $this->scheduleTraining($email);
        }
    }

    protected function recordUserAction($email, $simulationId, $action, $data = null)
    {
        // Store user action for reporting
    }

    protected function awardPositiveScore($email)
    {
        // Increase security awareness score
    }

    protected function scheduleTraining($email)
    {
        // Schedule security training for user
    }

    protected function getSubject($template)
    {
        $subjects = [
            'password_expired' => 'Your password will expire in 24 hours',
            'invoice' => 'Unpaid invoice - Action Required',
            'security_alert' => 'Security Alert: Suspicious login detected',
            'package_delivery' => 'Your package delivery status'
        ];
        
        return $subjects[$template] ?? 'Important Security Update';
    }

    public function getSimulationResults($simulationId)
    {
        $simulation = PhishingSimulation::findOrFail($simulationId);
        
        $total = count($simulation->target_emails);
        
        return [
            'name' => $simulation->name,
            'total_recipients' => $total,
            'clicked' => $simulation->clicked_count,
            'click_rate' => $total > 0 ? ($simulation->clicked_count / $total) * 100 : 0,
            'reported' => $simulation->reported_count,
            'report_rate' => $total > 0 ? ($simulation->reported_count / $total) * 100 : 0,
            'submitted_data' => $simulation->submitted_data_count,
            'submission_rate' => $total > 0 ? ($simulation->submitted_data_count / $total) * 100 : 0
        ];
    }
}