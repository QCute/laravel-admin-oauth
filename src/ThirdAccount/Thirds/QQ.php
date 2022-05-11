<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

use Cann\Admin\OAuth\Helpers\ApiHelper;

class QQ extends ThirdAbstract
{
    const AUTHORIZE_URL = 'https://graph.qq.com/oauth2.0/authorize?';
    const ACCESS_TOKEN_URL = 'https://graph.qq.com/oauth2.0/token?';
    const USER_INFO_URL = 'https://graph.qq.com/oauth2.0/me?';

    protected $redirectUrl;

    public function getPlatform()
    {
        return 'QQ';
    }

    public function getPlatformChn()
    {
        return 'QQ';
    }

    public function getAuthorizeUrl(array $params)
    {
        $paramsStr = http_build_query([
            'client_id'     => $this->config['app_id'],
            'redirect_uri'  => $this->redirectUrl,
            'response_type' => 'code',
            'scope'         => 'get_user_info',
            'state'         => $this->generateState(),
        ]);

        return self::AUTHORIZE_URL . $paramsStr;
    }

    public function getThirdUser(array $params)
    {
        \Validator::make($params, [
            'code' => 'required|string',
        ])->validate();

        $this->validateState($params['state']);

        $tokenInfo = $this->getAccessToken($params);

        $params = [
            'access_token' => $tokenInfo['access_token'],
        ];
        $userInfo = $this->request(self::USER_INFO_URL, $params);

        return [
            'id'   => $userInfo[$this->config['id']],
            'name' => $userInfo[$this->config['name']],
        ];
    }

    private function getAccessToken(array $params)
    {
        $params = [
            'client_id'     => $this->config['app_id'],
            'client_secret' => $this->config['app_secret'],
            'redirect_uri'  => $this->redirectUrl,
            'code'          => $params['code'],
            'grant_type'    => 'authorization_code'
        ];
        return $this->request(self::ACCESS_TOKEN_URL, $params);
    }

    private function request(string $url, array $params, string $method = 'GET', string $format = null, array $headers = [])
    {
        $response = ApiHelper::guzHttpRequest($url, $params, $method, $format, $headers);

        if (isset($response[$this->config['code']]) && $response[$this->config['code']]) {
            throw new \Exception($response[$this->config['message']] . '(code:' . $response[$this->config['code']] . ')');
        }

        return $response;
    }
}
