<?php

return [
    [
        'name' => 'Liên hệ',
        'flag' => 'contacts.index',
    ],
    [
        'name' => 'Sửa',
        'flag' => 'contacts.edit',
        'parent_flag' => 'contacts.index',
    ],
    [
        'name' => 'Xóa',
        'flag' => 'contacts.destroy',
        'parent_flag' => 'contacts.index',
    ],
];
