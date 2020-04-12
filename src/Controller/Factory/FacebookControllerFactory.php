<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace DtlSocial\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlSocial\Controller\FacebookController;
use Doctrine\ORM\EntityManager;
use DtlSocial\Service\Social\Facebook;

class FacebookControllerFactory implements FactoryInterface {
    
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): object {
        $controller = new FacebookController();
        $controller->setEntityManager($container->get(EntityManager::class));
        $controller->setFacebookService($container->get(Facebook::class));
        return $controller;
    }
}
