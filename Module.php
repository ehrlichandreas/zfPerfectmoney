<?php

namespace ggPerfectmoney;

use Zend\Mvc\Router;
use Zend\ModuleManager;

class Module implements ModuleManager\Feature\AutoloaderProviderInterface,
                        ModuleManager\Feature\ConfigProviderInterface,
                        ModuleManager\Feature\ServiceProviderInterface
{
    const CONFIG_KEY = 'ggPerfectmoney';

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ggperfectmoney_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    if (isset($config[self::CONFIG_KEY])) {
                        $config = $config[self::CONFIG_KEY];
                        $router = $sm->get('router');

                        // Expand routes in config to canonical urls
                        foreach (array('status', 'payment', 'nopayment') as $key) {
                            try {
                                $option =& $config['payment']["${key}_url"];
                                $option = $router->assemble(
                                    array(),
                                    array(
                                        'name' => $option,
                                        'force_canonical' => true
                                    )
                                );
                            } catch (Router\Exception\RuntimeException $e) {
                                // route not found, use config literally as url
                                // the variable was not set, so we just do nothing
                            }
                        }
                    } else {
                        $config = array();
                    }

                    return $config;
                },

                'ggperfectmoney_payment_form' => function ($sm) {
                    return new Form\PaymentForm(null, $sm->get('ggperfectmoney_module_options'));
                },
                'ggperfectmoney_status_form' => function ($sm) {
                    return new Form\StatusForm(null, $sm->get('ggperfectmoney_module_options'));
                },
                'ggperfectmoney_transaction' => function ($sm) {
                    return new Transaction($sm->get('ggperfectmoney_module_options'));
                }
            )
        );
    }
}
