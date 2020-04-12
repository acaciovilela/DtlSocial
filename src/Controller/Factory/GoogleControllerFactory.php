<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace DtlSocial\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlSocial\Controller\GoogleController;
use Doctrine\ORM\EntityManager;
use DtlSocial\Service\Social\Google;

class GoogleControllerFactory implements FactoryInterface {
    
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): object {
        $controller = new GoogleController();
        $controller->setEntityManager($container->get(EntityManager::class));
        $controller->setGoogleService($container->get(Google::class));
        return $controller;
    }
}
