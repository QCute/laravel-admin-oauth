<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

use Cann\Admin\OAuth\Helpers\ApiHelper;

class DingDing extends ThirdAbstract
{
    const AUTHORIZE_URL = 'https://login.dingtalk.com/oauth2/auth?';
    const ACCESS_TOKEN_URL = 'https://api.dingtalk.com/v1.0/oauth2/userAccessToken';
    const USER_INFO_URL = 'https://api.dingtalk.com/v1.0/contact/users/me';

    protected $redirectUrl;

    public function getPlatform()
    {
        return 'DingDing';
    }

    public function getPlatformChn()
    {
        return '钉钉';
    }

    public function getAuthorizeUrl(array $params)
    {
        $paramsStr = http_build_query([
            'client_id'     => $this->config['app_id'],
            'redirect_uri'  => $this->redirectUrl,
            'scope'         => 'openid',
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

        $headers = [
            'x-acs-dingtalk-access-token' => $tokenInfo['accessToken'],
        ];
        $userInfo = $this->request(self::USER_INFO_URL, [], 'GET', 'JSON', $headers);

        return [
            'id'   => $userInfo[$this->config['id']],
            'name' => $userInfo[$this->config['name']],
        ];
    }

    private function getAccessToken($params)
    {
        $params = [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->config['app_id'],
            'client_secret' => $this->config['app_secret'],
            'code'          => $params['code'],
        ];
        return $this->request(self::ACCESS_TOKEN_URL, $params, 'POST', 'JSON');
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
