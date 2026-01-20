<?php

use App\Helpers\SettingHelper;

if (!function_exists('setting')) {
    function setting($key, $default = null) {
        return SettingHelper::get($key, $default);
    }
}

if (!function_exists('hero_slides')) {
    function hero_slides() {
        return SettingHelper::getHeroSlides();
    }
}