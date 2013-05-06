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
            'status_url' => 'perfectmoney/status',
            'payment_url' => 'home',
            'nopayment_url' => 'home',
            'units' => 'USD'
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'ggPerfectmoney\Controller\Deposit' => 'ggPerfectmoney\Controller\DepositController',
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
                                'controller' => 'ggPerfectmoney\Controller\Deposit',
                                'action' => 'status'
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
            'ggPerfectmoney' => __DIR__ . '/../view'
        )
    )
);
