<?php

namespace App\Services\Module25_PenetrationTesting;

use App\Models\Module25_PenetrationTesting\SocialEngineeringLog;

class SocialEngineeringService
{
    public function createCampaign($data)
    {
        $campaign = SocialEngineeringLog::create([
            'campaign_name' => $data['name'],
            'target_emails' => $data['targets'],
            'technique' => $data['technique'],
            'started_at' => now(),
            'ended_at' => $data['duration_days'] ? now()->addDays($data['duration_days']) : null
        ]);
        
        $this->executeCampaign($campaign);
        
        return $campaign;
    }

    protected function executeCampaign($campaign)
    {
        switch ($campaign->technique) {
            case 'phishing_email':
                $this->sendPhishingEmails($campaign);
                break;
            case 'vishing':
                $this->makeVishingCalls($campaign);
                break;
            case 'smishing':
                $this->sendSmishingMessages($campaign);
                break;
            case 'usb_drop':
                $this->dropUsbDevices($campaign);
                break;
        }
    }

    protected function sendPhishingEmails($campaign)
    {
        foreach ($campaign->target_emails as $target) {
            // Send simulated phishing email
            $this->trackEmailSent($campaign->id, $target);
        }
    }

    protected function makeVishingCalls($campaign)
    {
        // Automated vishing calls
    }

    protected function sendSmishingMessages($campaign)
    {
        // SMS phishing
    }

    protected function dropUsbDevices($campaign)
    {
        // Physical USB drop
    }

    protected function trackEmailSent($campaignId, $target)
    {
        // Track email delivery
    }

    public function trackAction($campaignId, $target, $action)
    {
        $campaign = SocialEngineeringLog::findOrFail($campaignId);
        
        switch ($action) {
            case 'click':
                $campaign->clicked_count++;
                break;
            case 'report':
                $campaign->reported_count++;
                break;
            case 'submit':
                $campaign->submitted_data_count++;
                break;
        }
        
        $campaign->save();
        
        return $campaign;
    }

    public function getCampaignResults($campaignId)
    {
        $campaign = SocialEngineeringLog::findOrFail($campaignId);
        $total = count($campaign->target_emails);
        
        return [
            'campaign_name' => $campaign->campaign_name,
            'technique' => $campaign->technique,
            'total_targets' => $total,
            'clicked' => $campaign->clicked_count,
            'click_rate' => $total > 0 ? ($campaign->clicked_count / $total) * 100 : 0,
            'reported' => $campaign->reported_count,
            'report_rate' => $total > 0 ? ($campaign->reported_count / $total) * 100 : 0,
            'submitted_data' => $campaign->submitted_data_count,
            'submission_rate' => $total > 0 ? ($campaign->submitted_data_count / $total) * 100 : 0,
            'lessons_learned' => $this->generateLessons($campaign)
        ];
    }

    protected function generateLessons($campaign)
    {
        $lessons = [];
        
        if ($campaign->click_rate > 30) {
            $lessons[] = 'Additional phishing awareness training needed';
        }
        
        if ($campaign->report_rate < 10) {
            $lessons[] = 'Improve reporting mechanisms';
        }
        
        return $lessons;
    }
}