<?php
return array(
    'ggPerfectmoney' => array(
        // Admin account
        'merchant' => array(
            'id'       => '171397',
            'password' => 'dadada777',
            'UAccount' => 'U2386165',
            'apiKey'   => 'Dead',
            'name'     => $_SERVER['SERVER_NAME']
        ),

        'payment' => array(
            // route or url
            'api_url' => 'https://perfectmoney.com/api/step1.asp',
            'status_url' => 'deposit/perfectmoney/callback',
            'payment_url' => 'home',
            'nopayment_url' => 'home'
        ),
    ),
);
