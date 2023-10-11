<?php

return [
    [
        'name' => 'Quản trị viên',
        'flag' => 'users.index',
        'parent_flag' => 'core.system',
    ],
    [
        'name' => 'Tạo',
        'flag' => 'users.create',
        'parent_flag' => 'users.index',
    ],
    [
        'name' => 'Sửa',
        'flag' => 'users.edit',
        'parent_flag' => 'users.index',
    ],
    [
        'name' => 'Xóa',
        'flag' => 'users.destroy',
        'parent_flag' => 'users.index',
    ],

    [
        'name' => 'Nhóm và phân quyền',
        'flag' => 'roles.index',
        'parent_flag' => 'core.system',
    ],
    [
        'name' => 'Tạo',
        'flag' => 'roles.create',
        'parent_flag' => 'roles.index',
    ],
    [
        'name' => 'Sửa',
        'flag' => 'roles.edit',
        'parent_flag' => 'roles.index',
    ],
    [
        'name' => 'Xóa',
        'flag' => 'roles.destroy',
        'parent_flag' => 'roles.index',
    ],
];
