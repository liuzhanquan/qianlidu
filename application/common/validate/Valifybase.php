<?php
namespace app\common\validate;
use \think\Validate;

class Valifybase extends Validate{
	
    protected $rule = [
        ['gid',         'require|number','项目id不能为空|项目ID必须为数字'],
        ['order_num',   'require|number','订单号不能为空|订单号必须为数字'],
        ['aduly',       'require|number','成人人数不能为空|成人人数必须为数字'],
        ['baby',        'number',         '订单号必须为数字'],
        ['addr_list',   'require','地址不能为空'],
        ['phone',		'require|checkPhone','手机号不能为空|手机号错误'],
        ['card_num',		'require|number','卡号不能为空|激活卡号必须为数字'],
        ['code',		'require|number','验证码不能为空|验证码必须为数字'],
    ];
    
    protected function checkPhone($value = '', $rule = '', $data, $field){
    	if (preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $value)) {// 参数验证
            return true;
        } else {
            return $field . '参数格式不正确!';
        }
    }


}
