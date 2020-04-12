<?php

namespace DtlSocial\Service\Social;

use DtlAuth\Service\RequestService;
use Laminas\ServiceManager\ServiceManager;
use DtlSocial\Entity\Facebook as FacebookEntity;
use DtlSocial\Authentication\Adapters\FacebookAdapter;
use DtlAuth\Service\OAuth2Service;
use Laminas\Http\Response;

class Facebook {

    /**
     *
     * @var array
     */
    protected $configs;

    /**
     *
     * @var RequestService
     */
    protected $request;

    /**
     *
     * @var ServiceManager 
     */
    protected $serviceManager;

    public function signIn(string $code) {

        $sm = $this->getServiceManager();

        $oauthService = new OAuth2Service($sm->get(FacebookAdapter::class));

        $accessToken = $oauthService->getAccessToken($code);
        
        if (!key_exists('access_token', $accessToken)) {
            return false;
        }

        /**
         * Check Access Token
         */
        $app = $oauthService->getAccessToken('', ['grant_type' => 'client_credentials']);

        $check = false;

        if (key_exists('access_token', $app)) {
            $check = $this->checkAccessToken($accessToken['access_token'], $app['access_token']);
        }

        if (is_array($check)) {
            if (key_exists('data', $check)) {
                $facebookId = $check['data']['user_id'];
            }
        }

        /**
         * Check if token belongs to profile of a connected user
         */
        $em = $sm->get('doctrine.entitymanager.orm_default');

        $connected = $em->getRepository(FacebookEntity::class)
                ->findOneBy(['facebookId' => $facebookId]);

        if (!$connected) {
            $authService = $sm->get(\Laminas\Authentication\AuthenticationService::class);
            $identity = $authService->getIdentity();

            $user = $em->getRepository(\DtlUser\Entity\User::class)
                    ->find($identity->getId());

            $facebook = new FacebookEntity();
            $facebook->setAccessToken($accessToken['access_token']);
            $facebook->setTokenType($accessToken['token_type']);

            if (key_exists('expires_in', $accessToken)) {
                $facebook->setExpiresIn($accessToken['expires_in']);
            }

            $facebook->setFacebookId($facebookId);
            $facebook->setUser($user);
            $em->persist($facebook);
            $em->flush();
        } else {
            $connected->setAccessToken($accessToken['access_token']);
            if (key_exists('expires_in', $accessToken)) {
                $connected->setExpiresIn($accessToken['expires_in']);
            }
            $em->persist($connected);
            $em->flush();
        }
        return true;
    }
    
    public function revoke(FacebookEntity $facebook) {
        if (!$facebook instanceof FacebookEntity) {
            return false;
        }
        $uri = $this->configs['api_base_uri'] . $facebook->getFacebookId() . '/permissions';
        $uri .= '?access_token=' . $facebook->getAccessToken();
        $response = $this->getRequest()->request($uri, 'DELETE');
        $revoke = $this->getRequest()->getJsonDecode($response);
        if (key_exists('success', $revoke)) {
            if ($revoke['success']) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 
     * @param string $accessToken
     * @param string $appToken
     * @return array
     */
    public function checkAccessToken(string $accessToken, string $appToken) {
        $uri = $this->configs['api_base_uri'] . 'debug_token';
        $params = [
            'input_token' => $accessToken,
            'access_token' => $appToken,
        ];
        $response = $this->getRequest()->request($uri, 'GET', $params);
        return $this->getRequest()->getJsonDecode($response);
    }
    
    /**
     * 
     * @param FacebookEntity $facebook
     * @return boolean|FacebookEntity
     */
    public function refreshToken(FacebookEntity $facebook) {
        if (isset($facebook) && ($facebook instanceof FacebookEntity)) {
            $sm = $this->getServiceManager();
            $oauthService = new OAuth2Service($sm->get(FacebookAdapter::class));
            $refresh = $oauthService->getRefreshToken($facebook->getAccessToken());
            if (key_exists('access_token', $refresh)) {
                $facebook->setAccessToken($refresh['access_token']);
                $facebook->setExpiresIn($refresh['expires_in']);
                $facebook->setTokenType($refresh['token_type']);
                $em = $sm->get(\Doctrine\ORM\EntityManager::class);
                $em->persist($facebook);
                $em->flush();
                return $facebook;
            }
            return false;
        }
        return false;
    }

    /**
     * Get User profile information
     * 
     * @param string $accessToken
     */
    public function getProfile(FacebookEntity $facebook) {
        $uri = $this->configs['api_base_uri'] . $this->configs['api_version'] . '/' . $facebook->getFacebookId();
        $uri .= '?fields=id,name,email,accounts,picture';
        $uri .= '&access_token=' . $facebook->getAccessToken();
        $response = $this->getRequest()->request($uri);
        if (!$response->isSuccess()) {
            switch ($response->getStatusCode()) {
                case 401:
                    $refresh = $this->refreshToken($response, $facebook);
                    if ($refresh instanceof FacebookEntity) {
                        return $this->getProfile($refresh);
                    }
                    return $this->errorHandler($response);
            }
        }
        return $this->getRequest()->getJsonDecode($response);
    }
    
    private function errorHandler(Response $response) {
        throw new \Exception($response->getReasonPhrase());
    }
    
    public function getConfigs() {
        return $this->configs;
    }

    public function setConfigs($configs) {
        $this->configs = $configs;
        return $this;
    }

    public function getRequest(): RequestService {
        return $this->request;
    }

    public function setRequest(RequestService $request) {
        $this->request = $request;
        return $this;
    }

    public function getServiceManager(): ServiceManager {
        return $this->serviceManager;
    }

    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

}
