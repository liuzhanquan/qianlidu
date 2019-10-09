<?php
namespace app\user\controller;
use app\common\controller\User;

class Goods extends User{

	/**
	 * 扫码出货
	**/
	public function scanout(){

		return $this->showtpl('user/Goods/scanout');
	}

	/**
	 * 扫码退货
	**/
	public function scanback(){

		return $this->showtpl('user/Goods/scanback');
	}

	/**
	 * 出货记录
	**/
	public function outlog(){

		return $this->showtpl('user/Goods/outlog');
	}

	/**
	 * 退货记录
	**/
	public function backlog(){

		return $this->showtpl('user/Goods/backlog');
	}

	/**
	 * 收货记录
	**/
	public function inlog(){

		return $this->showtpl('user/Goods/inlog');
	}

	/**
	 * 产品溯源
	**/
	public function formin(){ 

		return $this->showtpl('user/Goods/formin');
	}

	/**
	 * 零售出货
	**/
	public function retailout(){

		return $this->showtpl('user/Goods/retailout');
	}

	/**
	 * 零售退货
	**/
	public function retailback(){

		return $this->showtpl('user/Goods/retailback');
	}

}