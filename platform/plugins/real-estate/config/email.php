<?php

return [
    'name' => 'plugins/real-estate::settings.email.title',
    'description' => 'plugins/real-estate::settings.email.description',
    'templates' => [
        'notice' => [
            'title' => 'Yêu cầu liên hệ',
            'description' => 'Gửi email đến đại lý/ quản trị khi có khách hàng yêu cầu tư vấn qua form tư vấn',
            'subject' => 'Yêu cầu liên hệ',
            'can_off' => true,
            'variables' => [
                'consult_name' => 'Tên',
                'consult_phone' => 'Số điện thoại',
                'consult_email' => 'Email',
                'consult_content' => 'Nội dung',
                'consult_link' => 'Link bài đăng',
                'consult_subject' => 'Tiêu đề',
            ],
        ],
        'new-pending-property' => [
            'title' => 'Bất động sản chờ phê duyệt',
            'description' => 'Gửi email cho quản trị viên khi có bất động sản mới được tạo',
            'subject' => 'Một bất động sản mới được tạo bởi {{ post_author }} đang chờ được phê duyệt',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'post_author' => 'Tác giả bài đăng',
                'post_name' => 'Tên bài đăng',
                'post_url' => 'URL bài đăng',
            ],
        ],
        'account-registered' => [
            'title' => 'Tài khoản đăng kí mới',
            'description' => 'Gửi thông báo cho admin khi có tài khoản mới đăng ký',
            'subject' => 'Một tài khoản đăng kí mới trên {{ site_title }}',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'account_name' => 'Tên tài khoản',
                'account_email' => 'Email tài khoản',
            ],
        ],
        'confirm-email' => [
            'title' => 'Xác minh tài khoản',
            'description' => 'Gửi email xác minh email cho người dùng khi họ đăng ký tài khoản',
            'subject' => 'Xác minh tài khoản trên {{ site_title }}',
            'can_off' => false,
            'variables' => [
                'verify_link' => 'Link xác minh tài khoản',
            ],
        ],
        'password-reminder' => [
            'title' => 'Khôi phục mật khẩu',
            'description' => 'Gửi email khôi phục mật khẩu tới người dùng',
            'subject' => 'Khôi phục mật khẩu',
            'can_off' => false,
            'variables' => [
                'reset_link' => 'Link khôi phục mật khẩu',
            ],
        ],
        'payment-receipt' => [
            'title' => 'Hóa đơn thanh toán',
            'description' => 'Gửi thông báo cho người dùng khi họ mua vé đăng bài',
            'subject' => 'Hóa đơn thanh toán cho gói {{ package_name }} trên {{ site_title }}',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'account_name' => 'Tên tài khoản',
                'package_name' => 'Tên gói',
                'package_price' => 'Giá',
                'package_discount' => 'Giảm giá',
                'package_total' => 'Tổng cộng',
            ],
        ],
        'free-credit-claimed' => [
            'title' => 'Vé bài đăng miễn phí',
            'description' => 'Gửi thông báo cho quản trị viên khi người dùng nhận vé bài đăng miễn phí',
            'subject' => '{{ account_name }} đã yêu cầu vé bài đăng miễn phí trên {{ site_title }}',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'account_name' => 'Tên tài khoản',
                'account_email' => 'Email tài khoản',
            ],
        ],
        'payment-received' => [
            'title' => 'Thanh toán gói thành công',
            'description' => 'Gửi thông báo cho quản trị viên khi ai đó mua gói bài đăng',
            'subject' => 'Thanh toán nhận được từ {{ account_name }} trên {{ site_title }}',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'account_name' => 'Tên tài khoản',
                'account_email' => 'Email tài khoản',
                'package_name' => 'Tên gói',
                'package_price' => 'Giá',
                'package_discount' => 'Giảm giá',
                'package_total' => 'Tổng cộng',
            ],
        ],
    ],
];
