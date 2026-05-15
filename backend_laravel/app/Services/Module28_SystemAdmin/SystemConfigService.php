<?php

namespace App\Services\Module28_SystemAdmin;

use App\Models\Module28_SystemAdmin\SystemSetting;

class SystemConfigService
{
    public function getSetting($key, $default = null)
    {
        $setting = SystemSetting::where('setting_key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $this->castValue($setting->setting_value, $setting->setting_type);
    }

    public function setSetting($key, $value, $category = 'general')
    {
        $type = $this->detectType($value);
        
        $setting = SystemSetting::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $this->serializeValue($value),
                'setting_type' => $type,
                'category' => $category
            ]
        );
        
        // Clear cache
        cache()->forget("system_setting_{$key}");
        
        return $setting;
    }

    public function getSettingsByCategory($category)
    {
        $settings = SystemSetting::where('category', $category)->get();
        
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->setting_key] = $this->castValue($setting->setting_value, $setting->setting_type);
        }
        
        return $result;
    }

    protected function detectType($value)
    {
        if (is_bool($value)) return 'boolean';
        if (is_int($value)) return 'integer';
        if (is_array($value)) return 'array';
        if (is_float($value)) return 'float';
        return 'string';
    }

    protected function serializeValue($value)
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }
        
        return (string) $value;
    }

    protected function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function exportConfiguration()
    {
        $settings = SystemSetting::all();
        
        $config = [];
        foreach ($settings as $setting) {
            $config[$setting->setting_key] = $this->castValue($setting->setting_value, $setting->setting_type);
        }
        
        return json_encode($config, JSON_PRETTY_PRINT);
    }

    public function importConfiguration($jsonConfig)
    {
        $config = json_decode($jsonConfig, true);
        
        foreach ($config as $key => $value) {
            $this->setSetting($key, $value);
        }
        
        return count($config);
    }
}