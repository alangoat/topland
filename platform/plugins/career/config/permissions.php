<?php

return [
    [
        'name' => 'Tuyển dụng',
        'flag' => 'career.index',
    ],
    [
        'name' => 'Tạo',
        'flag' => 'career.create',
        'parent_flag' => 'career.index',
    ],
    [
        'name' => 'Sửa',
        'flag' => 'career.edit',
        'parent_flag' => 'career.index',
    ],
    [
        'name' => 'Xóa',
        'flag' => 'career.destroy',
        'parent_flag' => 'career.index',
    ],
];
