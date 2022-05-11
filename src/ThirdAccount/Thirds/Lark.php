<?php

namespace Cann\Admin\OAuth\ThirdAccount\Thirds;

use Cann\Admin\OAuth\Helpers\ApiHelper;

class Lark extends ThirdAbstract
{
    const AUTHORIZE_URL = 'https://passport.feishu.cn/suite/passport/oauth/authorize?';
    const ACCESS_TOKEN_URL = 'https://passport.feishu.cn/suite/passport/oauth';
    const USER_INFO_URL = 'https://passport.feishu.cn/suite/passport/oauth';

    protected $redirectUrl;

    public function getPlatform()
    {
        return 'Lark';
    }

    public function getPlatformChn()
    {
        return '飞书';
    }

    public function getAuthorizeUrl(array $params)
    {
        $paramsStr = http_build_query([
            'client_id'     => $this->config['app_id'],
            'redirect_uri'  => $this->redirectUrl,
            'response_type' => 'code',
            'state'         => $this->generateState(),
        ]);

        return 'https://passport.feishu.cn/suite/passport/sso/qr?goto=' . self::AUTHORIZE_URL . $paramsStr;
    }

    public function getThirdUser(array $params)
    {
        \Validator::make($params, [
            'code' => 'required|string',
        ])->validate();

        $this->validateState($params['state']);
        
        // access token
        $tokenInfo = $this->getAccessToken($params);
        
        // use info
        $headers = [
            'Authorization' => $tokenInfo['token_type'] . ' '. $tokenInfo['access_token'],
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
            'client_id'     => $this->config['app_id'],
            'client_secret' => $this->config['app_secret'],
            'code'          => $params['code'],
            'redirect_uri'  => $this->redirectUrl,
            'grant_type'    => 'authorization_code',
        ];
        return $this->request(self::ACCESS_TOKEN_URL, $params, 'POST', 'JSON');
    }

    private function request(string $url, array $params, string $method = 'GET', string $format = null, $headers = [])
    {
        $response = ApiHelper::guzHttpRequest($url, $params, $method, $format, $headers);

        if (isset($response[$this->config['code']]) && $response[$this->config['code']]) {
            throw new \Exception($response[$this->config['message']] . '(code:' . $response[$this->config['code']] . ')');
        }

        return $response;
    }
}