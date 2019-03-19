<?php
return [

	// 安全检验码，以数字和字母组成的32位字符。 2018042702598531
	'key' => 'w8p8gksxdjpou8boaqm5xs898spajj32',// MjBDQfQE3VHmPqvPrtdiFA==

	// 签名方式
	'sign_type' => 'RSA2',

	// 商户私钥。
	'private_key_path' => __DIR__ . '/key/private_key.pem',

	// 阿里公钥。
	'public_key_path' => __DIR__ . '/key/public_key.pem',

	// 异步通知连接。
	'notify_url' => 'http://182.92.150.116/recallg/wx/alipay.php'
];
