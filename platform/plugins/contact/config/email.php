<?php

return [
    'name' => 'plugins/contact::contact.settings.email.title',
    'description' => 'plugins/contact::contact.settings.email.description',
    'templates' => [
        'notice' => [
            'title' => 'plugins/contact::contact.settings.email.templates.notice_title',
            'description' => 'plugins/contact::contact.settings.email.templates.notice_description',
            'subject' => 'Message sent via your contact form from {{ site_title }}',
            'can_off' => true,
            'variables' => [
                'contact_name' => 'Tên liên hệ',
                'contact_email' => 'Email liên hệ',
                'contact_phone' => 'Số điện thoại liên hệ',
                'contact_content' => 'Nội dung liên hệ',
            ],
        ],
    ],
];