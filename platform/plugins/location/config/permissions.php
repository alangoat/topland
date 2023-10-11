<?php

return [
    [
        'name' => 'Địa điểm',
        'flag' => 'plugin.location',
    ],

    [
        'name' => 'Tỉnh/thành phố',
        'flag' => 'state.index',
        'parent_flag' => 'plugin.location',
    ],
    [
        'name' => 'Tạo',
        'flag' => 'state.create',
        'parent_flag' => 'state.index',
    ],
    [
        'name' => 'Sửa',
        'flag' => 'state.edit',
        'parent_flag' => 'state.index',
    ],
    [
        'name' => 'Xóa',
        'flag' => 'state.destroy',
        'parent_flag' => 'state.index',
    ],

    [
        'name' => 'Quận/huyện',
        'flag' => 'city.index',
        'parent_flag' => 'plugin.location',
    ],
    [
        'name' => 'Tạo',
        'flag' => 'city.create',
        'parent_flag' => 'city.index',
    ],
    [
        'name' => 'Sửa',
        'flag' => 'city.edit',
        'parent_flag' => 'city.index',
    ],
    [
        'name' => 'Xóa',
        'flag' => 'city.destroy',
        'parent_flag' => 'city.index',
    ],
];
