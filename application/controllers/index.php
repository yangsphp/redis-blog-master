<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Home_Controller
{
    private $_redis;

    public function __construct()
    {
        parent::__construct();
        $this->_redis = $this->cache->redis->getRedis();
    }

    public function index()
    {
        //获取我的关注数量
        $follow_count = count($this->_redis->sMembers("flow:userid:" . $this->_user_id));
        //获取我的粉丝
        $fans_count = count($this->_redis->sMembers("flowing:userid:" . $this->_user_id));
        //获取我的贴子数量
        $post_count = $this->_redis->lSize("post:userid:" . $this->_user_id);
        //获取我的关注的帖子列表
        $my_follow_post = $this->getMyFollowPost();
        //获取最新的帖子列表
        $all_post = $this->getPostList();
        //获取最新注册的用户列表
        $user_list = $this->getUserList(0, 6);

        $data['follow_count'] = $follow_count;
        $data['fans_count'] = $fans_count;
        $data['post_count'] = $post_count;
        $data['username'] = $this->_username;
        $data['user_id'] = $this->_user_id;
        $data['my_follow_post'] = $my_follow_post;
        $data['all_post'] = $all_post;
        $data['user_list'] = $user_list;
        $this->load->view('index/index', $data);
    }

    /**
     * 获取
     * @return array
     */
    public function getUserList($offset, $size)
    {
        $user_id_list = $this->_redis->lRange('user:list:id', $offset, $size);
        $user_array = array();
        $my_follow = $this->_redis->sMembers("flow:userid:".$this->_user_id);
        $my_follow[] = $this->_user_id;
        foreach ($user_id_list as $k => $v)
        {
            $user['id'] = $this->cache->redis->get("user:{$v}:id");
            $user['username'] = $this->cache->redis->get("user:{$v}:username");
            $user['is_follow'] = 0;
            //判断是否已经关注
            if (in_array($v, $my_follow))
            {
                $user['is_follow'] = 1;
            }
            $user_array[] = $user;
        }
        return $user_array;
    }

    /**
     * 获取所有的帖子列表
     * @return array
     */
    public function getPostList()
    {
        $size = $this->_redis->lSize('post:new');
        $post_id_array = $this->_redis->lRange('post:new', 0, $size);
        $post = array();
        foreach ($post_id_array as $k => $v) {
            $info = $this->_redis->hGetAll("post:id:" . $v);
            $info['time'] = time_tran($info['date_entered']);
            $post[] = $info;
        }
        return $post;
    }

    /**
     * 获取我的关注的帖子列表
     * @return array
     */
    public function getMyFollowPost()
    {
        $size = $this->_redis->lSize('post:receive:'.$this->_user_id);
        $post_id_array = $this->_redis->lRange('post:receive:'.$this->_user_id, 0, $size);
        $post = array();
        $my_follow = $this->_redis->sMembers("flow:userid:".$this->_user_id);
        foreach ($post_id_array as $k => $v) {
            $info = $this->_redis->hGetAll("post:id:" . $v);
            $info['time'] = time_tran($info['date_entered']);
            $info['is_follow'] = 0;
            //判断是否已经关注
            if (in_array($info['user_id'], $my_follow))
            {
                $info['is_follow'] = 1;
            }
            $post[] = $info;
        }
        return $post;
    }

    /**
     * 获取我添加的帖子列表
     * @return array
     */
    public function getMySendPost()
    {
        $size = $this->_redis->lSize('post:id:'.$this->_user_id);
        $post_id_array = $this->_redis->lRange('post:id:'.$this->_user_id, 0, $size);
        $post = array();
        foreach ($post_id_array as $k => $v) {
            $info = $this->_redis->hGetAll("post:id:" . $v);
            $info['time'] = time_tran($info['date_entered']);
            $post[] = $info;
        }
        return $post;
    }

    public function follow()
    {
        $user_id = $this->input->get("uid");
        $flag = $this->input->get("flag");
        if ($flag == 0) {
            $this->_redis->sAdd("flow:userid:".$this->_user_id, $user_id);
            $this->_redis->sAdd("flowing:userid:".$user_id, $this->_user_id);
        }elseif ($flag == 1) {
            $this->_redis->srem("flow:userid:".$this->_user_id, $user_id);
            $this->_redis->srem("flowing:userid:".$user_id, $this->_user_id);
        }
        die(json_encode(array("code" => 0, "msg" => "ok")));
    }

    /**
     * 发布帖子
     */
    public function send()
    {
        $token = trim($_GET['token']);
        $content = $this->input->post("content");
        $new_post_id = $this->cache->redis->increment("global:post:id");
        $time = date("Y-m-d H:i:s", time());
        $this->_redis->hMset("post:id:$new_post_id", array('user_id' => $this->_user_id, 'username' => $this->_username, 'content' => $content, 'date_entered' => $time, 'view' => 0));
        //保存我的帖子id列表
        $this->_redis->lPush('post:userid:' . $this->_user_id, $new_post_id);

        //保存最新帖子
        $this->_redis->lPush('post:new', $new_post_id);

        //获取我的粉丝，并把我的动态发给他
        $fans = $this->_redis->smembers("flowing:userid:".$this->_user_id);
        $fans[] = $this->_user_id;
        foreach ($fans as $fansid) {
            $this->_redis->lpush("post:receive:$fansid", $new_post_id);
        }
        redirect('index/index?token=' . $token);
    }
}
