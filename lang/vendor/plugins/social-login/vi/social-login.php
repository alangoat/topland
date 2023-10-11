<?php

return [
    'settings' => [
        'title' => 'Cài Đặt Đăng Nhập Oauth2',
        'description' => 'Cấu hình đăng nhập Oauth2',
        'google' => [
            'title' => 'Google Login',
            'description' => 'Bật/tắt và cấu hình thông tin đăng nhập Google',
            'app_id' => 'App ID',
            'app_secret' => 'App Secret',
            'helper' => 'Callback URL là :callback',
        ],
        'github' => [
            'title' => 'Github Login',
            'description' => 'Bật/tắt và cấu hình thông tin đăng nhập Github',
            'app_id' => 'App ID',
            'app_secret' => 'App Secret',
            'helper' => 'Callback URL là :callback',
        ],
        'enable' => 'Bật?',
    ],
    'menu' => 'Cài đặt Oauth2',
];
