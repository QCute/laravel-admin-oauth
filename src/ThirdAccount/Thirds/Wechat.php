<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

use Cann\Admin\OAuth\Helpers\ApiHelper;

class Wechat extends ThirdAbstract
{
    const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/qrconnect?';
    const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
    const USER_INFO_URL = 'https://api.weixin.qq.com/sns/userinfo?';

    protected $redirectUrl;

    public function getPlatform()
    {
        return 'Wechat';
    }

    public function getPlatformChn()
    {
        return '微信';
    }

    public function getAuthorizeUrl(array $params)
    {
        $paramsStr = http_build_query([
            'appid'         => $this->config['app_id'],
            'redirect_uri'  => $this->redirectUrl,
            'scope'         => 'snsapi_login',
            'response_type' => 'code',
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
            'openid'       => $tokenInfo['openid'],
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
            'appid'      => $this->config['app_id'],
            'secret'     => $this->config['app_secret'],
            'code'       => $params['code'],
            'grant_type' => 'authorization_code',
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
