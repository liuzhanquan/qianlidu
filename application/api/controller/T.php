<?php
namespace app\api\controller;

class Index{
	
    public function index(){

    	echo md5('1234564d68af00');
    }

    public function view($uid ='',$level =''){
    	$levelInfo = db('level')->find($level);
    	if(!empty($levelInfo['position'])){
    		$position = unserialize($levelInfo['position']);
    		$textArr = array();
    		// 授权名称
    		if(!empty($position['name'])){
                if(!empty($position['name']['position'])){
                    $posArr = explode(',', $position['name']['position']);
                    $textArr['name']['left'] = $posArr[0];
                    $textArr['name']['top'] = $posArr[1] + 20;
                }
                $textArr['name']['text'] = '这个是测试的名称';
                $textArr['name']['fontSize'] = $position['name']['size'];
                $textArr['name']['fontColor'] = hex2rgba($position['name']['color']);
                $textArr['name']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['name']['angle'] = 0;
                $textArr['name']['center'] = 0;
    		}
    		// 身份证
    		if(!empty($position['card'])){
                if(!empty($position['card']['position'])){
                    $posArr = explode(',', $position['card']['position']);
                    $textArr['card']['left'] = $posArr[0];
                    $textArr['card']['top'] = $posArr[1] + 20;
                }
                $textArr['card']['text'] = '465464865321086456501255';
                $textArr['card']['fontSize'] = $position['card']['size'];
                $textArr['card']['fontColor'] = hex2rgba($position['card']['color']);
                $textArr['card']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['card']['angle'] = 0;
                $textArr['card']['center'] = 0;
    		}
    		// 微信号
    		if(!empty($position['weixin'])){
                if(!empty($position['weixin']['position'])){
                    $posArr = explode(',', $position['weixin']['position']);
                    $textArr['weixin']['left'] = $posArr[0];
                    $textArr['weixin']['top'] = $posArr[1] + 20;
                }
                $textArr['weixin']['text'] = 'weixinguangmai';
                $textArr['weixin']['fontSize'] = $position['weixin']['size'];
                $textArr['weixin']['fontColor'] = hex2rgba($position['weixin']['color']);
                $textArr['weixin']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['weixin']['angle'] = 0;
                $textArr['weixin']['center'] = 0;
    		}
    		// 地址
    		if(!empty($position['address'])){
                if(!empty($position['address']['position'])){
                    $posArr = explode(',', $position['address']['position']);
                    $textArr['address']['left'] = $posArr[0];
                    $textArr['address']['top'] = $posArr[1] + 20;
                }
                $textArr['address']['text'] = '广州市天河区';
                $textArr['address']['fontSize'] = $position['address']['size'];
                $textArr['address']['fontColor'] = hex2rgba($position['address']['color']);
                $textArr['address']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['address']['angle'] = 0;
                $textArr['address']['center'] = 0;
    		}
    		// 淘宝ID
    		if(!empty($position['tao'])){
                if(!empty($position['tao']['position'])){
                    $posArr = explode(',', $position['tao']['position']);
                    $textArr['tao']['left'] = $posArr[0];
                    $textArr['tao']['top'] = $posArr[1] + 20;
                }
                $textArr['tao']['text'] = 'joessss';
                $textArr['tao']['fontSize'] = $position['tao']['size'];
                $textArr['tao']['fontColor'] = hex2rgba($position['tao']['color']);
                $textArr['tao']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['tao']['angle'] = 0;
                $textArr['tao']['center'] = 0;
    		}
    		// 手机号码
    		if(!empty($position['phone'])){
                if(!empty($position['phone']['position'])){
                    $posArr = explode(',', $position['phone']['position']);
                    $textArr['phone']['left'] = $posArr[0];
                    $textArr['phone']['top'] = $posArr[1] + 20;
                }
                $textArr['phone']['text'] = '13265996501';
                $textArr['phone']['fontSize'] = $position['phone']['size'];
                $textArr['phone']['fontColor'] = hex2rgba($position['phone']['color']);
                $textArr['phone']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['phone']['angle'] = 0;
                $textArr['phone']['center'] = 0;
    		}
    		// 代理级别
    		if(!empty($position['grape'])){
                if(!empty($position['grape']['position'])){
                    $posArr = explode(',', $position['grape']['position']);
                    $textArr['grape']['left'] = $posArr[0];
                    $textArr['grape']['top'] = $posArr[1] + 20;
                }
                $textArr['grape']['text'] = $levelInfo['name'];
                $textArr['grape']['fontSize'] = $position['grape']['size'];
                $textArr['grape']['fontColor'] = hex2rgba($position['grape']['color']);
                $textArr['grape']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['grape']['angle'] = 0;
                $textArr['grape']['center'] = 0;
    		}
    		// 授权编码
    		if(!empty($position['number'])){
                if(!empty($position['number']['position'])){
                    $posArr = explode(',', $position['number']['position']);
                    $textArr['number']['left'] = $posArr[0];
                    $textArr['number']['top'] = $posArr[1] + 20;
                }
                $textArr['number']['text'] = 'SQ23565645';
                $textArr['number']['fontSize'] = $position['number']['size'];
                $textArr['number']['fontColor'] = hex2rgba($position['number']['color']);
                $textArr['number']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['number']['angle'] = 0;
                $textArr['number']['center'] = 0;
    		}
    		// 授权期限开始时间
    		if(!empty($position['start'])){
                if(!empty($position['start']['position'])){
                    $posArr = explode(',', $position['start']['position']);
                    $textArr['start']['left'] = $posArr[0];
                    $textArr['start']['top'] = $posArr[1] + 20;
                }
                $textArr['start']['text'] = date('Y年m月d日');
                $textArr['start']['fontSize'] = $position['start']['size'];
                $textArr['start']['fontColor'] = hex2rgba($position['start']['color']);
                $textArr['start']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['start']['angle'] = 0;
                $textArr['start']['center'] = 0;
    		}
    		// 授权期限结束时间
    		if(!empty($position['end'])){
                if(!empty($position['end']['position'])){
                    $posArr = explode(',', $position['end']['position']);
                    $textArr['end']['left'] = $posArr[0];
                    $textArr['end']['top'] = $posArr[1] + 20;
                }
                $textArr['end']['text'] = date('Y年m月d日',strtotime("+7day"));
                $textArr['end']['fontSize'] = $position['end']['size'];
                $textArr['end']['fontColor'] = hex2rgba($position['end']['color']);
                $textArr['end']['fontPath'] = ROOT_PATH.'font/font.ttf';
                $textArr['end']['angle'] = 0;
                $textArr['end']['center'] = 0;
    		}
    		$isImg = ROOT_PATH.'public'.str_replace("\\", '/', $levelInfo['tplimg']);
    		// 头像
    		$config = array(
    			'text'=>$textArr,
    			'background'=>$isImg, // 背景图
    		);
    		$returnFile = ROOT_PATH.'public'.DS.'uploads/certificate/qrcode_'.$uid.'.jpg'; // 生成文件
		    createPoster($config,$returnFile);
		    echo "<img src='/uploads/certificate/qrcode_".$uid.".jpg' />";
    	}
    }
}
