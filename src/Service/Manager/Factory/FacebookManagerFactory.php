<?php

namespace DtlSocial\Service\Manager\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlSocial\Service\Manager\FacebookManager;

class FacebookManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $facebookManager = new FacebookManager();
        $facebookManager->setServiceManager($container);
        return $facebookManager;
    }

}
