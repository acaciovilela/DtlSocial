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
use DtlSocial\Controller\GoogleController;
use DtlSocial\Controller\Factory\GoogleControllerFactory;

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
    /**
     * Google configuration
     * 
     * Enter with your data for each field
     */
    'google' => [
        /**
         * Google Client ID
         */
        'client_id' => '512073975466-d5rjon10i8m271ku50946rsihkf1usc2.apps.googleusercontent.com',
        /**
         * Google Client Secret
         */
        'client_secret' => 'RLB86mA_zCKBgN1NFM5GN00o',
        /**
         * Google Authorize API base URI
         */
        'api_authorize_uri' => 'https://accounts.google.com',
        /**
         * Google Token URI
         */
        'api_token_uri' => 'https://oauth2.googleapis.com',
        /**
         * Google Base URI
         */
        'api_base_uri' => 'https://www.googleapis.com',
        /**
         * Redirect URI to your application
         */
        'redirect_uri' => 'https://rocker.com.br/app/social/google/oauth',
        /**
         * Google scopes in authorize request
         */
        'scope' => 'profile email openid',
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            FacebookController::class => FacebookControllerFactory::class,
            GoogleController::class => GoogleControllerFactory::class,
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
                            'google' => [
                                'type' => \Laminas\Router\Http\literal::class,
                                'options' => [
                                    'route' => '/google',
                                    'defaults' => [
                                        'controller' => GoogleController::class,
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
                        'label' => 'Contas',
                        'route' => 'dtl-admin/dtl-social',
                        'pages' => [
                            [
                                'label' => 'Facebook',
                                'route' => 'dtl-admin/dtl-social/facebook'
                            ],
                            [
                                'label' => 'Google',
                                'route' => 'dtl-admin/dtl-social/google'
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'admin' => [
            [
                'label' => 'Contas',
                'route' => 'dtl-admin/dtl-social'
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Authentication\Adapters\FacebookAdapter::class => Authentication\Adapters\Factory\FacebookAdapterFactory::class,
            Authentication\Adapters\GoogleAdapter::class => Authentication\Adapters\Factory\GoogleAdapterFactory::class,
            Service\Social\Facebook::class => Service\Social\Factory\FacebookFactory::class,
            Service\Social\Google::class => Service\Social\Factory\GoogleFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\FacebookSignInBtn::class => View\Helper\Factory\FacebookSignInBtnFactory::class,
            View\Helper\GoogleSignInBtn::class => View\Helper\Factory\GoogleSignInBtnFactory::class,
        ],
        'aliases' => [
            'facebookSignInBtn' => View\Helper\FacebookSignInBtn::class,
            'googleSignInBtn' => View\Helper\GoogleSignInBtn::class,
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
