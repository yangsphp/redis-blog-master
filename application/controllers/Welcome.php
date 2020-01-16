<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Home_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
	{
        //var_dump($this->cache->redis->save('foo', 'bar', 3600));
        //var_dump($this->cache->redis->get('foo'));
		$this->load->view('welcome_message');
	}
}
