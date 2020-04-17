<?php

namespace DtlSocial\Service\Social;

use DtlAuth\Service\RequestService;
use Laminas\ServiceManager\ServiceManager;
use DtlSocial\Entity\Google as GoogleEntity;
use DtlSocial\Authentication\Adapters\GoogleAdapter;
use DtlAuth\Service\OAuth2Service;
use Laminas\Http\Response;

class Google {

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

        $oauthService = new OAuth2Service($sm->get(GoogleAdapter::class));

        $accessToken = $oauthService->getAccessToken($code);

        if (!key_exists('access_token', $accessToken)) {
            return false;
        }

        /**
         * Check if token belongs to profile of a connected user
         */
        $authService = $sm->get(\Laminas\Authentication\AuthenticationService::class);
        $identity = $authService->getIdentity();
        
        $em = $sm->get(\Doctrine\ORM\EntityManager::class);

        $connected = $em->getRepository(GoogleEntity::class)
                ->findOneBy(['user' => $identity]);

        if (!$connected) {
            $user = $em->getRepository(\DtlUser\Entity\User::class)
                    ->find($identity->getId());
            
            $google = new GoogleEntity();
            $google->setAccessToken($accessToken['access_token']);
            $google->setRefreshToken($accessToken['refresh_token']);

            if (key_exists('expires_in', $accessToken)) {
                $google->setExpiresIn($accessToken['expires_in']);
            }

            $google->setIdToken($accessToken['id_token']);
            $google->setUser($user);
            $em->persist($google);
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

    public function revoke(GoogleEntity $google) {
        if (!$google instanceof GoogleEntity) {
            return false;
        }
        $uri = $this->configs['api_token_uri'] . '/revoke';
        $uri .= '?token=' . $google->getAccessToken();
        $this->getRequest()->setHeaders(['Content-Length: 0']);
        $response = $this->getRequest()->request($uri, 'POST');
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
     * @param GoogleEntity $google
     * @return boolean|GoogleEntity
     */
    public function refreshToken(GoogleEntity $google) {

        if (isset($google) && ($google instanceof GoogleEntity)) {
            $sm = $this->getServiceManager();
            $oauthService = new OAuth2Service($sm->get(GoogleAdapter::class));
            $refresh = $oauthService->getRefreshToken($google->getRefreshToken());
            if (key_exists('access_token', $refresh)) {
                $google->setAccessToken($refresh['access_token']);
                $google->setExpiresIn($refresh['expires_in']);
                $em = $sm->get(\Doctrine\ORM\EntityManager::class);
                $em->persist($google);
                $em->flush();
                return $google;
            }
            return false;
        }
        return false;
    }

    /**
     * Get User profile information
     * 
     * @param string $google
     */
    public function getProfile(GoogleEntity $google) {
        $uri = $this->configs['api_base_uri'] . '/' . 'userinfo/v2/me';
        $this->getRequest()->setHeaders(['Authorization: Bearer ' . $google->getAccessToken()]);
        $response = $this->getRequest()->request($uri);
        if (!$response->isSuccess()) {
            switch ($response->getStatusCode()) {
                case 401:
                    $refresh = $this->refreshToken($google);
                    if ($refresh instanceof GoogleEntity) {
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
