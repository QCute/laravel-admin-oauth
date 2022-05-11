<?php

return [

    'controller' => Cann\Admin\OAuth\Controllers\AuthController::class,

    // 是否允许账号密码登录
    'allowed_password_login' => true,

    // 当第三方登录未匹配到本地账号时，是否允许自动创建本地账号
    'allowed_auto_create_account_by_third' => false,

    // 启用的第三方登录
    'enabled_thirds' => [
        'WorkWechat',
        'DingDing',
        'Lark',
        'Wechat',
        'QQ',
    ],

    // 第三方登录秘钥
    'services' => [
        'WorkWechat' => [
            'corp_id'    => env('WORK_WECHAT_CORP_ID', ''),
            'agent_id'   => env('WORK_WECHAT_AGENT_ID', ''),
            'secret'     => env('WORK_WECHAT_AGENT_SECRET', ''),
            'code'       => 'errcode',  // 错误码属性
            'message'    => 'errmsg',   // 错误消息属性
            'id'         => 'UserId',   // 用户信息id属性
            'name'       => 'UserId',   // 用户信息名字属性
        ],
        'DingDing' => [
            'app_id'     => env('DINGDING_APP_ID', ''),
            'app_secret' => env('DINGDING_APP_SECRET', ''),
            'code'       => 'code',    // 错误码属性
            'message'    => 'message', // 错误消息属性
            'id'         => 'unionId', // 用户信息id属性
            'name'       => 'nick',    // 用户信息名字属性
        ],
        'Lark' => [
            'app_id'     => env('LARK_APP_ID', ''),
            'app_secret' => env('LARK_APP_SECRET', ''),
            'code'       => 'error',             // 错误码属性
            'message'    => 'error_description', // 错误消息属性
            'id'         => 'unionId',           // 用户信息id属性
            'name'       => 'name',              // 用户信息名字属性
        ],
        'Wechat' => [
            'app_id'     => env('WECHAT_APP_ID', ''),
            'app_secret' => env('WECHAT_APP_SECRET', ''),
            'code'       => 'errcode',  // 错误码属性
            'message'    => 'errmsg',   // 错误消息属性
            'id'         => 'unionid',  // 用户信息id属性
            'name'       => 'nickname', // 用户信息名字属性
        ],
        'QQ' => [
            'app_id'     => env('QQ_APP_ID', ''),
            'app_secret' => env('QQ_APP_SECRET', ''),
            'code'       => 'ret',    // 错误码属性
            'message'    => 'msg',    // 错误消息属性
            'id'         => 'openid', // 用户信息id属性
            'name'       => 'openid', // 用户信息名字属性
        ],
    ],
];
