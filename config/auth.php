<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    // ✅ วาง admin guard ไว้ใน array 'guards'
    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver'   => 'session',
            'provider' => 'users', // ใช้ provider เดียวกับผู้ใช้ทั่วไป (หรือเปลี่ยนเป็น 'admins' ถ้ามีโมเดลแยก)
        ],
    ],

    // ✅ provider ที่ถูกเรียกใช้
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\SignupUser::class, // ให้แน่ใจว่า extends Authenticatable
        ],

        // ถ้ามีตาราง/โมเดล admin แยก ค่อยเพิ่ม:
        // 'admins' => [
        //     'driver' => 'eloquent',
        //     'model'  => App\Models\Admin::class,
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
