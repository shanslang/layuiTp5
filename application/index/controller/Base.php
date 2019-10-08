<?php

namespace app\index\controller;

use think\Controller;
use think\facade\Session;

class Base extends Controller 
{
    protected function initialize()
    {
        if(!Session::has('admin')){
        	$this->error('请登录呦！', 'Login/index');
        }
    }
}