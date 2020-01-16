<?php
/**
 * Created by PhpStorm.
 * User: 25754
 * Date: 2020/1/14
 * Time: 11:18
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller
{
    private $_redis;
    public function __construct()
    {
        parent::__construct();
        $this->_redis = $this->cache->redis->getRedis();
    }

    public function index()
    {
        $this->load->view('login/index');
    }

    public function login()
    {
        header('Access-Control-Allow-Credentials:true');
        $post = $this->input->post("post");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('post[username]', '账号', 'required|trim', array("required" => "请输入%s"));
        $this->form_validation->set_rules('post[password]', '密码', 'required', array("required" => "请输入%s"));
        if ($this->form_validation->run() == FALSE) {
            $errors = explode("\n", validation_errors());
            die(json_encode(array("code" => 1, "msg" => strip_tags($errors[0]))));
        }
        $user_id = $this->cache->redis->get("user:username:{$post['username']}");
        $password = $this->cache->redis->get("user:{$user_id}:password");
        $salt = $this->cache->redis->get("user:{$user_id}:salt");
        if ($password != base16_encode($post['password'].$salt)){
            die(json_encode(array("code" => 1, "msg" => "登录失败, 用户名或密码不正确")));
        }
        $token = base16_encode(json_encode(array(
            '_i' => base16_encode($user_id),
            '_u' => base16_encode($post['username'])
        )));
        $this->cache->redis->save("global:token:{$token}", time(), $this->config->item('token_time'));
        die(json_encode(array("code" => 0, "msg" => "登录成功", "token" => $token)));
    }

    public function register()
    {
        $post = $this->input->post("post");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('post[username]', '账号', 'required|trim', array("required" => "请输入%s"));
        $this->form_validation->set_rules('post[password]', '密码', 'required', array("required" => "请输入%s"));
        $this->form_validation->set_rules('post[rpassword]', '密码', 'required|matches[post[password]]', array("required" => "请输入%s", "matches" => "两次输入%s不一致"));
        if ($this->form_validation->run() == FALSE) {
            $errors = explode("\n", validation_errors());
            die(json_encode(array("code" => 1, "msg" => strip_tags($errors[0]))));
        }
        //判断是否已经被注册
        $user_id = $this->cache->redis->get("user:username:{$post['username']}");
        if ($user_id) {
            die(json_encode(array("code" => 1, "msg" => "该用户名已被注册")));
        }
        $new_user_id = $this->cache->redis->increment("global:user:id");
        $salt = random(5);
        $password = base16_encode($post['password'].$salt);
        $this->cache->redis->save("user:{$new_user_id}:id", $new_user_id, 0);
        $this->cache->redis->save("user:{$new_user_id}:username", $post['username'], 0);
        $this->cache->redis->save("user:{$new_user_id}:password", $password, 0);
        $this->cache->redis->save("user:{$new_user_id}:salt", $salt, 0);
        $this->cache->redis->save("user:{$new_user_id}:date_entered", date("Y-m-d H:i:s", time()), 0);
        $this->cache->redis->save("user:username:{$post['username']}", $new_user_id, 0);

        $this->_redis->lPush("user:list:id", $new_user_id);
        die(json_encode(array("code" => 0, "msg" => "注册成功")));
    }

    public function logout()
    {
        $token = trim($_GET['token']);
        $this->cache->redis->save('global:token:'.$token, '', 1);
        redirect("login/index");
    }
}