<?php

class Token {
  /**
   * @var string 用户名
   */
  const USERNAME = 'awangba';

  /**
   * @var string 密码
   */
  const PASSWORD = 'RD3wEc9D2e7d87';

  /**
   * @var string 加密公钥
   */
  const PUBLIC_KEY = 'verycloud#cryptpass';

  /**
   * @var string token
   */
  private static $token;

  /**
   * @var int token过期时间
   */
  private static $expire;

  /**
   * 返回token
   */
  public function token() {
      
    if(!empty(self::$token) && self::$expire > time()) {
      return self::$token;
    }
    $en=new Encrypt(self::PASSWORD, self::PUBLIC_KEY);
    $send_data = array(
      'username' => self::USERNAME,
      'password' => $en->encrypt(self::PASSWORD, self::PUBLIC_KEY)
    );

    $url = Request::$api_url . '/API/OAuth/authorize';

    $return = Request::sendRequest($url, $send_data);
    if($return['code'] == 1) {
      self::$token = $return['access_token'];
      self::$expire = $return['expires'];
      return $return['access_token'];
    }
    return '';
  }
}
