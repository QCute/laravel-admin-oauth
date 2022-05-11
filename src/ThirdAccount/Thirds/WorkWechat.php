<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

use Cann\Admin\OAuth\Helpers\ApiHelper;

class WorkWechat extends ThirdAbstract
{
    const AUTHORIZE_URL = 'https://open.work.weixin.qq.com/wwopen/sso/qrConnect?';
    const ACCESS_TOKEN_URL = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?';
    const USER_INFO_URL = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?';

    protected $redirectUrl;

    public function getPlatform()
    {
        return 'WorkWechat';
    }

    public function getPlatformChn()
    {
        return '企业微信';
    }

    public function getAuthorizeUrl(array $params)
    {
        $paramsStr = http_build_query([
            'appid'         => $this->config['corp_id'],
            'agentid'       => $this->config['agent_id'],
            'redirect_uri'  => $this->redirectUrl,
            'response_type' => 'code',
            'scope'         => 'snsapi_base',
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
            'code'         => $params['code'],
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
            'appid'      => $this->config['corp_id'],
            'secret'     => $this->config['secret'],
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
