<?php

namespace App\Services\Module17_PhysicalSecurity;

use App\Models\Module17_PhysicalSecurity\CctvFootage;

class CctvService
{
    public function getLiveFeed($cameraId)
    {
        // Return RTMP or HLS stream URL
        return [
            'camera_id' => $cameraId,
            'stream_url' => "rtmp://cctv.local/stream/{$cameraId}",
            'thumbnail' => "https://cctv.local/thumbnail/{$cameraId}"
        ];
    }

    public function searchFootage($criteria)
    {
        $query = CctvFootage::query();
        
        if (isset($criteria['camera_id'])) {
            $query->where('camera_id', $criteria['camera_id']);
        }
        
        if (isset($criteria['from'])) {
            $query->where('recorded_at', '>=', $criteria['from']);
        }
        
        if (isset($criteria['to'])) {
            $query->where('recorded_at', '<=', $criteria['to']);
        }
        
        if (isset($criteria['has_motion'])) {
            $query->where('has_motion', $criteria['has_motion']);
        }
        
        return $query->orderBy('recorded_at', 'desc')->paginate(20);
    }

    public function detectMotion($cameraId, $timestamp)
    {
        $footage = CctvFootage::where('camera_id', $cameraId)
            ->where('recorded_at', '>=', $timestamp)
            ->where('has_motion', true)
            ->first();
        
        return $footage ? $footage->has_motion : false;
    }

    public function exportFootage($cameraId, $from, $to, $format = 'mp4')
    {
        $footages = CctvFootage::where('camera_id', $cameraId)
            ->whereBetween('recorded_at', [$from, $to])
            ->get();
        
        // Merge video files
        $outputPath = storage_path("exports/cctv_{$cameraId}_{$from}_{$to}.{$format}");
        
        return [
            'export_path' => $outputPath,
            'size' => file_exists($outputPath) ? filesize($outputPath) : 0,
            'duration' => $footages->sum('duration_seconds')
        ];
    }
}