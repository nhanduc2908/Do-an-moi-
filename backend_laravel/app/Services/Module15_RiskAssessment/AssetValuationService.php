<?php

namespace App\Services\Module15_RiskAssessment;

use App\Models\Module15_RiskAssessment\Asset;

class AssetValuationService
{
    public function calculateAssetValue($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        
        $values = [
            'acquisition_cost' => $this->calculateAcquisitionCost($asset),
            'replacement_cost' => $this->calculateReplacementCost($asset),
            'business_value' => $this->calculateBusinessValue($asset),
            'compliance_value' => $this->calculateComplianceValue($asset),
            'reputational_value' => $this->calculateReputationalValue($asset)
        ];
        
        $totalValue = array_sum($values);
        
        $asset->value = $totalValue;
        $asset->save();
        
        return [
            'asset_id' => $assetId,
            'asset_name' => $asset->asset_name,
            'values' => $values,
            'total_value' => $totalValue,
            'criticality' => $this->getCriticality($totalValue)
        ];
    }

    protected function calculateAcquisitionCost($asset)
    {
        return $asset->purchase_price ?? 0;
    }

    protected function calculateReplacementCost($asset)
    {
        return $asset->current_market_value ?? $this->calculateAcquisitionCost($asset) * 0.8;
    }

    protected function calculateBusinessValue($asset)
    {
        $factors = [
            'revenue_generation' => $asset->revenue_impact ?? 0,
            'operational_impact' => $asset->operational_importance ?? 0,
            'strategic_importance' => $asset->strategic_value ?? 0
        ];
        
        return array_sum($factors);
    }

    protected function calculateComplianceValue($asset)
    {
        $complianceFactors = [
            'gdpr' => $asset->gdpr_relevance ?? 0,
            'hipaa' => $asset->hipaa_relevance ?? 0,
            'pci_dss' => $asset->pci_relevance ?? 0,
            'iso27001' => $asset->iso_relevance ?? 0
        ];
        
        return array_sum($complianceFactors) * 10000; // Penalty costs
    }

    protected function calculateReputationalValue($asset)
    {
        $factors = [
            'customer_trust' => $asset->customer_impact ?? 0,
            'brand_value' => $asset->brand_impact ?? 0,
            'market_position' => $asset->market_impact ?? 0
        ];
        
        return array_sum($factors) * 50000;
    }

    protected function getCriticality($value)
    {
        if ($value > 1000000) return 'Critical';
        if ($value > 500000) return 'High';
        if ($value > 100000) return 'Medium';
        return 'Low';
    }

    public function getAssetPriority($assetId)
    {
        $valuation = $this->calculateAssetValue($assetId);
        
        $priorityScore = $valuation['total_value'] / 100000;
        
        if ($priorityScore > 10) return 1;
        if ($priorityScore > 5) return 2;
        if ($priorityScore > 2) return 3;
        return 4;
    }
}