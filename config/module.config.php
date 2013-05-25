<?php
$merchantName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'Default Merchant Name';

return array(
    'zfPerfectmoney' => array(
        // Admin account
        'merchant' => array(
            'id'       => '171397',
            'password' => 'dadada777',
            'UAccount' => 'U2386165',
            'apiKey'   => 'Dead',
            'name'     => $merchantName
        ),

        'payment' => array(
            // route or url
            'api_url' => 'https://perfectmoney.com/api/step1.asp',
            'status_url' => 'perfectmoney/status',
            'payment_url' => 'home',
            'nopayment_url' => 'home',
            'units' => 'USD'
        ),

        'transaction' => array(
            'url' => 'https://perfectmoney.com/acct/confirm.asp'
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'zfPerfectmoney\Controller\Deposit' => 'zfPerfectmoney\Controller\DepositController',
        )
    ),

    'router' => array(
        'routes' => array(
            'perfectmoney' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/perfectmoney'
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'status' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/status',
                            'defaults' => array(
                                'controller' => 'zfPerfectmoney\Controller\Deposit',
                                'action' => 'status'
                            )
                        )
                    ),
                    'deposit' => array(
                        'type' => '/literal',
                        'options' => array(
                            'route' => '/deposit',
                            'defaults' => array(
                                'controller' => 'zfPerfectmoney\Controller\Deposit',
                                'action' => 'deposit'
                            )
                        )
                    )
                )
            )
        )
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        ),
        'template_path_stack' => array(
            'zfPerfectmoney' => __DIR__ . '/../view'
        )
    )
);
