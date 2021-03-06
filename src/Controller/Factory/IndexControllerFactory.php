<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace DtlSocial\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlSocial\Controller\IndexController;
use Doctrine\ORM\EntityManager;

class IndexControllerFactory implements FactoryInterface {
    
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): object {
        $controller = new IndexController();
        $controller->setEntityManager($container->get(EntityManager::class));
        return $controller;
    }
}
