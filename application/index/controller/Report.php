<?php
namespace app\index\controller;

use think\facade\{Request,Log,Session};
use think\Validate;

class Report extends Base
{
  	protected $beforeActionList = [
        'checks',
    ];

    protected function checks()
    {
    	$index = Session::get('admin.erjz');

        if(!$index[3]){
        	$this->redirect(Session::get('admin.urls'));
        }
    }
  
	public function index()
    {
        if(Request::isAjax()){
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
			$z_report[] = 1;  // z_register
            $z_report[] = 2;  // z_newregister
            $z_report[] = 3; // z_appre
            $z_report[] = 4;  // z_vipzr
            $z_report[] = 5; // z_exchange
            $z_report[] = 6;  // z_netProfit
            if (!$validate->check($params)) {
                $err = $validate->getError();
                //$this->error($validate->getError());
                $data = [
                    'code'  => 100,
                    'msg'   => '参数错误',
                    'count' => 0,
                    'data'  => [],
                    'addfile' => [
                    	't1' => $params['t1'],
                        't2' => $params['t2'],
                        'z_report' => $z_report,
                    ],
                ];
                 return json($data);
            }
			$list = [
            	'date' => '2019-09-29',
                'activeCt' => 1,
                'registerCt'  => 23,
                'pcregister'  => 435673,
                'newaddCt'    => 35,
                'rechargeCt'  => 34,
                'appAmount'   => 353,
                'vipzr'       => 4356,
                'excharge'    => 5345,
                'netProfit'   => 56345743
            ];
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => 1,
                'data'  => [
                	0 => $list
                ],
                'addfile' => [
                    	't1' => $params['t1'],
                        't2' => $params['t2'],
                  		'z_report' => $z_report,
                 ],
            ];
            return json($data);
        }
        return $this->fetch();
    }
  
    public function detailes()
    {
        if(Request::isAjax()){
            $cs = Request::post();
            Log::write($cs,'index-detailes');
            $params['pageno'] = Request::post('page', 1); 
            $params['pagesize'] = Request::post('limit', 20);
            $params['t1'] = Request::post('t1', date('Y-m-d'));
          	
            $validate = Validate::make([
                'pageno'   => 'require|number',
                'pagesize' => 'require|number',
                't1'       => 'require|date',
            ]);
          
            if (!$validate->check($params)) {
                $err = $validate->getError();
                $data = [
                    'code'  => 100,
                    'msg'   => '参数错误',
                    'count' => 0,
                    'data'  => [],
                ];
                 return json($data);
            }
 			$cid = Session::get('admin.channelID');
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => 0,
                'data'  => [],
            ];
            return json($data);
        }
        return $this->fetch();
    }
}