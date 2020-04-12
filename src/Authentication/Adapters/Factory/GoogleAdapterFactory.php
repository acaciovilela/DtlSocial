<?php

namespace DtlSocial\Authentication\Adapters\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlSocial\Authentication\Adapters\GoogleAdapter;

class GoogleAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $adapter = new GoogleAdapter();
        $adapter->setConfigs($container->get('config')['google']);
        return $adapter;
    }

}
