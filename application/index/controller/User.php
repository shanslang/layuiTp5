<?php
namespace app\index\controller;

use think\facade\{Request,Log,Session};
use think\Validate;

class User extends Base
{
  	protected $beforeActionList = [
        'checks',
    ];

    protected function checks()
    {
    	$index = Session::get('admin.erjz');
        if(!$index[1]){
        	$this->redirect(Session::get('admin.urls'));
        }
    }
  
	public function index()
    {
        if(Request::isAjax()){
            $cs = Request::get();
            Log::write($cs, 'user-index');
            $params['pageno'] = Request::get('page', 1); 
            $params['pagesize'] = Request::get('limit', 20);
            $key = Request::get('key');
            $params['t1'] = $key['t1'] ?: date('Y-m-d', strtotime('-1 week')); 
            $params['t2'] = $key['t2'] ?: date('Y-m-d');
            $params['gid'] = $key['gid'] ?: 0;
            $params['cid'] = $key['cid'] ?: 0;
          
            $validate = Validate::make([
                'pageno'   => 'require|number',
                'pagesize' => 'require|number',
                't1'       => 'require|date',
                't2'       => 'require|date',
                'gid'      => 'require|number',
                'cid'      => 'require|number',
            ]);
          
            if (!$validate->check($params)) {
                $err = $validate->getError();
                $data = [
                    'code'  => 100,
                    'msg'   => '参数错误',
                    'count' => 0,
                    'data'  => [],
                    'addfile' => [
                    	't1' => $params['t1'],
                        't2' => $params['t2'],
                        'gid' => '',
                        'cid' => '',
                    ],
                ];
                return json($data);
            }
          
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => 0,
                'data'  => [],
                'addfile' => [
                    	't1' => $params['t1'],
                        't2' => $params['t2'],
                        'gid' => $params['gid'] ?: '',
                        'cid' => $params['cid'] ?: '',
                 ],
            ];
            return json($data);
        }
        return $this->fetch();
    }
}