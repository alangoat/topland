<?php

return [
    'name' => 'core/acl::auth.settings.email.title',
    'description' => 'core/acl::auth.settings.email.description',
    'templates' => [
        'password-reminder' => [
            'title' => 'Khôi phục mật khẩu',
            'description' => 'Gửi email khôi phục mật khẩu tới người dùng',
            'subject' => 'Khôi phục mật khẩu',
            'can_off' => false,
            'variables' => [
                'reset_link' => 'Link khôi phục mật khẩu',
            ],
        ],
    ],
];
