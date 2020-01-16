<?php
/**
 * Created by PhpStorm.
 * User: 25754
 * Date: 2020/1/14
 * Time: 10:24
 */

class MY_Controller extends CI_Controller
{

}

//前台公共控制
class Home_Controller extends CI_Controller
{
    protected $_user_id;
    protected $_username;
    protected $_token_time;
    public function __construct()
    {
        parent::__construct();
        $token = trim($_GET['token']);
        $time = $this->cache->redis->get('global:token:'.$token);
        if (!$time || !$token)
        {
            redirect('login/index');
        }
        $this->_token_time = $this->config->item('token_time');
        $this->updateTokenTime($token);
        $user_info = json_decode(base16_decode($token), true);
        $this->_user_id = base16_decode($user_info['_i']);
        $this->_username = base16_decode($user_info['_u']);
    }

    public function updateTokenTime($token)
    {
        $this->cache->redis->save('global:token:'.$token, time(), $this->_token_time);
    }
}