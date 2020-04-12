<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DtlSocial;

use DtlSocial\Controller\IndexController;
use DtlSocial\Controller\Factory\IndexControllerFactory;
use DtlSocial\Controller\FacebookController;
use DtlSocial\Controller\Factory\FacebookControllerFactory;

return [
    /**
     * Facebook configuration
     * 
     * Enter with your data for each field
     */
    'facebook' => [
        /**
         * Facebook Client ID
         */
        'client_id' => '599484637301931',
        /**
         * Facebook Client Secret
         */
        'client_secret' => '7ebe560133d9d5cda18fcbadc111d190',
        /**
         * Facebook Graph API base URI
         */
        'api_base_uri' => 'https://graph.facebook.com/',
        /**
         * Current API version 
         */
        'api_version' => 'v6.0',
        /**
         * Redirect URI to your application
         */
        'redirect_uri' => 'https://rocker.com.br/app/social/facebook/oauth',
        /**
         * Facebook scopes in authorize request
         */
        'scope' => 'manage_pages',
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            FacebookController::class => FacebookControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'dtl-admin' => [
                'child_routes' => [
                    'dtl-social' => [
                        'type' => \Laminas\Router\Http\literal::class,
                        'options' => [
                            'route' => '/social',
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'facebook' => [
                                'type' => \Laminas\Router\Http\literal::class,
                                'options' => [
                                    'route' => '/facebook',
                                    'defaults' => [
                                        'controller' => FacebookController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'oauth' => [
                                        'type' => \Laminas\Router\Http\literal::class,
                                        'options' => [
                                            'route' => '/oauth',
                                            'defaults' => [
                                                'action' => 'oauth',
                                            ],
                                        ],
                                    ],
                                    'disconnect' => [
                                        'type' => \Laminas\Router\Http\literal::class,
                                        'options' => [
                                            'route' => '/disconnect',
                                            'defaults' => [
                                                'action' => 'disconnect',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => [], 'allow' => '*'],
                ['actions' => ['index'], 'allow' => '@']
            ],
        ]
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Dashboard',
                'route' => 'dtl-admin',
                'pages' => [
                    [
                        'label' => 'Redes Sociais',
                        'route' => 'dtl-admin/dtl-social',
                        'pages' => [
                            [
                                'label' => 'Facebook',
                                'route' => 'dtl-admin/dtl-social/facebook'
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'admin' => [
            [
                'label' => 'Redes Sociais',
                'route' => 'dtl-admin/dtl-social'
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Authentication\Adapters\FacebookAdapter::class => Authentication\Adapters\Factory\FacebookAdapterFactory::class,
            Authentication\Adapters\SicoobAdapter::class => Authentication\Adapters\Factory\SicoobAdapterFactory::class,
            Service\Social\Facebook::class => Service\Social\Factory\FacebookFactory::class,
            Service\Manager\FacebookManager::class => Service\Manager\Factory\FacebookManagerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\FacebookSignInBtn::class => View\Helper\Factory\FacebookSignInBtnFactory::class,
        ],
        'aliases' => [
            'facebookSignInBtn' => View\Helper\FacebookSignInBtn::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'DtlSocial' => __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
