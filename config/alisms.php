<?php

return [

    /*
    |--------------------------------------------------------------------------
    | api info
    |--------------------------------------------------------------------------
    |
    |
    */
    'smsauth' => [  //短信配置
        'sign' => "超级高考生App",
        'content' => [
            '11' => [  //短信验证码登录
                "tpl_id"=>"SMS_127060095",
                "var"=>"code"
            ],
            '1' => [  //注册模板
                "tpl_id"=>"SMS_123672846",
                "var"=>"code"
            ],
            '2' => [  //密码找回
                "tpl_id"=>"SMS_126255018",
                "var"=>"code"
            ],
            '3' => [  //密码修改
                "tpl_id"=>"SMS_126255018",
                "var"=>"code"
            ],

        ]
    ],
];