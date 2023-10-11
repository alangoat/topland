<?php

namespace Botble\SocialLogin;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::whereIn('key', [
            'social_login_enable',
            'social_login_google_enable',
            'social_login_google_app_id',
            'social_login_google_app_secret',
        ])->delete();
    }
}
