<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SettingHelper
{
    public static function get($key, $default = null)
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            if (!Schema::hasTable('settings')) {
                return $default;
            }

            $setting = Setting::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    public static function getHeroSlides()
    {
        return Cache::rememberForever('settings.hero_slides', function () {
            if (!Schema::hasTable('settings')) {
                return [];
            }

            $slides = [];
            $slideSettings = Setting::where('key', 'like', 'hero_slide.%')->get();

            foreach ($slideSettings as $setting) {
                $parts = explode('.', $setting->key);
                if (count($parts) >= 3) {
                    $slideId = $parts[1];
                    $field = $parts[2];

                    if (!isset($slides[$slideId])) {
                        $slides[$slideId] = [
                            'id' => $slideId,
                            'image' => null,
                            'title' => '',
                            'description' => '',
                            'button_text' => '',
                            'button_link' => '',
                            'is_active' => true,
                            'order' => 0
                        ];
                    }
                    if ($field === 'image' && !empty($setting->value)) {
                        $slides[$slideId][$field] = Storage::url($setting->value);
                    } else {
                        $slides[$slideId][$field] = $setting->value;
                    }
                }
            }

            $activeSlides = array_filter($slides, function($slide) {
                return isset($slide['is_active']) && $slide['is_active'] == '1';
            });

            usort($activeSlides, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

            return array_values($activeSlides);
        });
    }

    public static function clearCache()
    {
        Cache::flush();
    }

    public static function getImage($key, $default = null)
    {
        $path = self::get($key, $default);
        
        if (empty($path)) {
            return $default;
        }
        
        // Jika sudah full URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Convert storage path ke public URL
        return Storage::url($path);
    }
}