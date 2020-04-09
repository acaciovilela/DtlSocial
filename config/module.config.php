<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DtlSocial;

use DtlSocial\Controller\IndexController;
use DtlSocial\Controller\Factory\IndexControllerFactory;

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
        'client_id' => '2354348601273813',
        /**
         * Facebook Client Secret
         */
        'client_secret' => '75107d8887315c4f2d790a4085ac0b45',
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
        'scope' => '',
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
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
                        'child_routes' => [],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Dashboard',
                'route' => 'dtl-admin',
                'pages' => [
                    [
                        'label' => 'Redes Sociais',
                        'route' => 'dtl-admin/dtl-social'
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
