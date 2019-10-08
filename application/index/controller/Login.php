<?php

namespace app\index\controller;

use think\captcha\Captcha;
use think\Validate;
use think\facade\{Session,Log};

class Login extends Base 
{
    protected function initialize()
    {
        
    }

    public function index()
    {
        if(Session::has('admin')){
            $this->success('已登录过呦', 'Index/index');
        }

		if ($this->request->isPost()) {
            $data = $this->request->post();
            $rule = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|length:3,30',
                '__token__' => 'token',
                'vercode'   => 'require|captcha',
            ];
            $validate = new Validate($rule, [], ['username' => '用户名', 'password' => '密码', 'vercode' => '验证码']);
            $result = $validate->check($data);
            $url = $this->request->get('url', 'Login/index');
            if (!$result) {
                $this->error($validate->getError(), $url, ['token' => $this->request->token()]);
            }
            $data['grade'] = 1;
            $data['channelID'] = 5454;
            $data['visual'] = 3;
            $erjz = decbin($data['visual']);
            $erjz = sprintf("%04d", $erjz);
            $urls = jumpUrl();
            $ins = stripos($erjz, '1'); 
          	if($ins === false){$this->error('您没有权限', $url, ['token' => $this->request->token()]);}
            $data['erjz'] = $erjz;
            $data['urls'] = $urls[$ins];
            Session::set('admin',$data);
            $this->success('登录成功','index/index','',0);
        }else{
            return $this->fetch();
        }  
    }

    public function loginout()
	{
		if(Session::has('admin')){
            Session::delete('admin');
        }
        $this->success('退出成功', 'index','',0);
	}

	public function verify()
    {
        $captcha = new Captcha();
        return $captcha->entry();    
    }
}