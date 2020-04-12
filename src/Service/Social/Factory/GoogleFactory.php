<?php

namespace DtlSocial\Service\Social\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlSocial\Service\Social\Google;
use DtlAuth\Service\RequestService;

class GoogleFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $google = new Google();
        $google->setConfigs($container->get('config')['google']);
        $google->setRequest(new RequestService());
        $google->setServiceManager($container);
        return $google;
    }

}
