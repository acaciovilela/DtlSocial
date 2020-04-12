<?php

/**
 * Facebook Adapter
 */

namespace DtlSocial\Authentication\Adapters;

use DtlAuth\Authentication\OAuth2AdapterInterface;
use DtlAuth\Authentication\OAuth2AdapterAbstract;

class FacebookAdapter extends OAuth2AdapterAbstract implements OAuth2AdapterInterface {

    /**
     * Facebook host
     */
    const API_AUTHORIZE_HOST = 'https://www.facebook.com/';

    /**
     * Facebook authorize path
     */
    const API_AUTHORIZE_PATH = '/dialog/oauth';

    /**
     * Graph API base URI
     */
    const API_BASE_URI = 'https://graph.facebook.com/';

    /**
     * Access Token path
     */
    const API_ACCESS_TOKEN_PATH = '/oauth/access_token';

    /**
     * Default response type for authorize request
     */
    const RESPONSE_TYPE = 'code';

    /**
     * Set and Get Facebook scopes as a string
     * 
     * @var string
     */
    protected $scopes;

    /**
     * Get an authorize URI
     * 
     * @return string
     */
    public function getAuthorizeUri() {

        $uri = self::API_AUTHORIZE_HOST . $this->configs['api_version'] . self::API_AUTHORIZE_PATH;

        if (isset($this->configs['scope'])) {
            $this->setScopes($this->configs['scope']);
        }

        return $this->formatUri($uri);
    }

    /**
     * 
     * @return string
     */
    public function getAccessTokenUri() {

        $uri = self::API_BASE_URI . $this->configs['api_version'] . self::API_ACCESS_TOKEN_PATH;

        return $this->formatUri($uri);
    }

    /**
     * 
     * @return array
     */
    public function getAuthorizeParameters() {
        $params = [];
        $params['client_id'] = $this->getConfigByKey('client_id');
        $params['redirect_uri'] = $this->getConfigByKey('redirect_uri');
        $params['response_type'] = $this->getResponseType();
        if (!empty($this->getScopes())) {
            $params['scope'] = $this->getScopes();
        }
        return $params;
    }

    /**
     * 
     * @param string $code
     * @return array
     */
    public function getAccessTokenParameters(string $code) {
        $params = [];
        $params['client_id'] = $this->getConfigByKey('client_id');
        $params['client_secret'] = $this->getConfigByKey('client_secret');
        $params['redirect_uri'] = $this->getConfigByKey('redirect_uri');
        $params['code'] = $code;
        return $params;
    }

    /**
     * 
     * @param string $token
     * @return array
     */
    public function getRefreshTokenParameters(string $token) {
        $params = [];
        $params['grant_type'] = 'fb_exchange_token';
        $params['client_id'] = $this->getConfigByKey('client_id');
        $params['client_secret'] = $this->getConfigByKey('client_secret');
        $params['fb_exchange_token'] = $token;
        return $params;
    }

    /**
     * 
     * @return string
     */
    public function getResponseType() {
        return self::RESPONSE_TYPE;
    }

    /**
     * 
     * @return string
     */
    public function getScopes() {
        return $this->scopes;
    }

    /**
     * 
     * @param string $scopes
     * @return $this
     */
    public function setScopes($scopes) {
        $this->scopes = $scopes;
        return $this;
    }

}
