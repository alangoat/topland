<?php

return [
    [
        'name' => 'Thanh toán',
        'flag' => 'payment.index',
    ],
    [
        'name' => 'Cài đặt',
        'flag' => 'payments.settings',
        'parent_flag' => 'payment.index',
    ],
    [
        'name' => 'Xóa',
        'flag' => 'payment.destroy',
        'parent_flag' => 'payment.index',
    ],
];
