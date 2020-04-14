<?php

/**
 * Google Adapter
 */

namespace DtlSocial\Authentication\Adapters;

use DtlAuth\Authentication\OAuth2AdapterInterface;
use DtlAuth\Authentication\OAuth2AdapterAbstract;

class GoogleAdapter extends OAuth2AdapterAbstract implements OAuth2AdapterInterface {

    /**
     * Google host
     */
    const API_AUTHORIZE_HOST = 'https://accounts.google.com/';

    /**
     * Google authorize path
     */
    const API_AUTHORIZE_PATH = '/o/oauth2/v2/auth';

    /**
     * Graph API base URI
     */
    const API_BASE_URI = 'https://graph.google.com/';

    /**
     * Access Token path
     */
    const API_ACCESS_TOKEN_PATH = '/token';

    /**
     * Default response type for authorize request
     */
    const RESPONSE_TYPE = 'code';

    /**
     * Set and Get Google scopes as a string
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

        $uri = $this->configs['api_authorize_uri'] . self::API_AUTHORIZE_PATH;

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

        $uri = $this->configs['api_token_uri'] . self::API_ACCESS_TOKEN_PATH;

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
        $params['access_type'] = 'offline';
        $params['prompt'] = 'consent';
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
        $params['grant_type'] = 'authorization_code';
        return $params;
    }

    /**
     * 
     * @param string $token
     * @return array
     */
    public function getRefreshTokenParameters(string $token) {
        $params = [];
        $params['grant_type'] = 'refresh_token';
        $params['client_id'] = $this->getConfigByKey('client_id');
        $params['client_secret'] = $this->getConfigByKey('client_secret');
        $params['refresh_token'] = $token;
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
