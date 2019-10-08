<?php
namespace app\index\controller;

use think\facade\{Request,Log,Session};
use think\Validate;

class Index extends Base
{
    protected $beforeActionList = [
        'checks' =>  ['except'=>'index'],
    ];
      
    protected function checks()
    {
    	$index  = Session::get('admin.erjz');
        if(!$index[0]){
        	$this->redirect(Session::get('admin.urls'));
        }
    }
  
    public function index()
    {
        return $this->fetch();
    }
  
    public function index2()
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
            	'channel'      => 'ssl',
                'channelID'    => 61456,
                'channelName'  => '叶子',
                'channelGrade' => 1,
                'proName'	   => '吹泡泡',
                'proportion'   => 20,
                'activationQuantity' => 30,
                'registerCt'   => 10,
                'pcRegister'   => 99,
                'newRegister'  => 9,
                'rechargeCt'   => 6,
                'appRecharge'  => 100,
                'vipzr'        => 10000,
                'exchangeAmount' => 290,
                'netProfit'    => 230,
                'qrcode'       => '/static/img/avater.jpg',
                'isuse'        => 0
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

    public function hello()
    {
         $data = [
           'code'  => 0,
           'msg'   => '',
           'count' => 0,
           'data'  => []
         ];
         echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
  
    public function addChannel()
    {
        if(Request::isPost()){
            $params = Request::post();
            //Log::write($params,'index-addChannel');
            $validate = Validate::make([
                'higherID'    => 'require|number',
                'grade'       => 'require|in:2,3',
                'channelName' => 'require|length:2,15',
                'channelAccount'  => 'require|alphaNum',
                'psw'             => 'require',
                'channel'     => 'require|number',
                'proName'     => 'require|length:2,15',
                'proportion'  => 'require|between:0,100',
            ]);
            if (!$validate->check($params)) {
                $err = $validate->getError();
                $data = [
                    'code'  => 100,
                    'msg'   => $err,
                ];
                return json($data);
            }
            $params['psw'] = strtoupper(md5($params['psw']));
            $params['useStatus'] = isset($params['isuse']) ? 0 : 1;
            if(isset($params['like'])){
                $czs = isset($params['like']['agent']) ? 1 : 0;
                $czs .= isset($params['like']['user']) ? 1 : 0;
                $czs .= isset($params['like']['recharge']) ? 1 : 0;
                $czs .= isset($params['like']['zong']) ? 1 : 0;
              	$params['visual'] = bindec($czs);
                Log::write($params['visual'], '权限');
            }else{
            	$params['visual'] = 0;
            }
            $data = [
                'code' => 200,
                'msg'  => '添加成功'
            ];
            return json($data);
        }
    }
  
    public function editCaccounts()
    {
    	if(Request::isPost()){
            $params = Request::post();
            $validate = Validate::make([
                'ecid'   => 'require|alphaNum',
                'channelID' => 'require|number',
            ]);
            if (!$validate->check($params)) {
                $err = $validate->getError();
                $data = [
                    'code'  => 100,
                    'msg'   => '参数不合法',
                ];
                return json($data);
            }
            Log::write($params,'index-editCaccounts');
            $data = [
                'code' => 200,
                'msg'  => '修改成功',
                'cAccount' => $params['ecid']
            ];
            return json($data);
        }
    }
  
    public function isUse()
    {
    	if(Request::isPost()){
            $params = Request::post();
            $validate = Validate::make([
                'isuse' => 'require|in:true,false',
                'cid'   => 'require|number',
            ]);
             Log::write($params,'index-isUse');
            if (!$validate->check($params)) {
                $err = $validate->getError();
                $data = [
                    'code'  => 100,
                    'msg'   => '参数不合法',
                ];
                return json($data);
            }
            if($params['isuse']){
            	$params['isuse'] = 0;
            }else{
            	$params['isuse'] = 1;
            }
            Log::write($params,'index-editCaccounts');
            $data = [
                'code'  => 200,
                'msg'   => '修改成功',
                'isuse' => $params['isuse']
            ];
            return json($data);
        }
    }
  
    public function hisList()
    {
        if(Request::isAjax()){
            $cs = Request::post();
            Log::write($cs,'index-hisList');
            $params['pageno'] = Request::post('page', 1); 
            $params['pagesize'] = Request::post('limit', 20);
            $params['cid'] = Request::post('cid', 0);
            $key = Request::post('key');
            $params['t1'] = $key['t1'] ?: date('Y-m-d', strtotime('-1 week')); 
            $params['t2'] = $key['t2'] ?: date('Y-m-d');
          
            $validate = Validate::make([
                'cid'      => 'require|number',
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
          
           
            $data = [
                'code'  => 0,
                'msg'   => '',
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
        return $this->fetch();
    }
      
}
