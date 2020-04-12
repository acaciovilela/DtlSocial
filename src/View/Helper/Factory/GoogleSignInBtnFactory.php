<?php

namespace DtlSocial\View\Helper\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlAuth\Service\OAuth2Service;
use DtlSocial\View\Helper\GoogleSignInBtn;
use DtlSocial\Authentication\Adapters\GoogleAdapter;

class GoogleSignInBtnFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $helper = new GoogleSignInBtn();
        $helper->setOauthService(new OAuth2Service($container->get(GoogleAdapter::class)));
        return $helper;
    }

}
