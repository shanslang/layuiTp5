<?php
namespace app\index\controller;

use think\facade\{Request,Log,Session};
use think\Validate;

class Recharge extends Base
{
    protected $beforeActionList = [
        'checks',
    ];
  
    protected function checks()
    {
    	$index = Session::get('admin.erjz');
        if(!$index[2]){
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
          
            $validate = Validate::make([
                'pageno'   => 'require|number',
                'pagesize' => 'require|number',
                't1'       => 'require|date',
                't2'       => 'require|date',
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
                    ],
                ];
                return json($data);
            }
          
            $cid = Session::get('admin.grade');
            Log::write($cid, 'recharge-index');
          
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => 0,
                'data'  => [],
                'addfile' => [
                    	't1' => $params['t1'],
                        't2' => $params['t2'],
                 ],
            ];
            return json($data);
        }
        return $this->fetch();
    }
}