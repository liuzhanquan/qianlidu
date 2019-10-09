<?php
namespace app\common\model;
use \think\Model;
use \think\Request;

class Upload extends Model{

    protected $error = '';
    /**
     * 上传数据
     * @return array 文件信息
     */
    public function uploadData($siteId = '0',$type = '0',$name='file'){
    	$config = load_config('application/upload');
    	$file = request()->file($name);
    	$size = $config['upload_size']*1024*1024;
    	switch ($type) {
    		case '1':
					$upload_path = ROOT_PATH . 'uploads';
					$upload_file = $_FILES;
					$file_name = explode('.', $upload_file[$name]['name']);
					$upload_name = $file_name[0];
					$info = $file->validate(['size'=>$size,'ext'=>$config['upload_type']])->move($upload_path,$upload_name);
					if(!$info){
						return $file->getError();
					}
					$path = DS.$info->getSaveName();
    			break;
    		
    		default:
					$upload_path = ROOT_PATH . 'public' . DS . 'uploads';
					$info = $file->validate(['size'=>$size,'ext'=>$config['upload_type']])->move($upload_path);
					if($info){
						$path = $info->getSaveName();//获取上传文件名
						// 缩略图
						if($config['thumb_status']){
							$image = \think\Image::open($upload_path.DS.$path);
							$path = $info->getSaveName().'_'.$config['thumb_width'].'x'.$config['thumb_height'].'.'.$info->getExtension();;
							$image->thumb($config['thumb_width'],$config['thumb_height'],$config['thumb_type'])->save($upload_path.'/'.$path);
						}
						// 水印
						if($config['watermark_status']){
							$image = \think\Image::open($upload_path.DS.$path);
							if($config['watermark_type'] == '1'){
								// 图片水印
								$image->water(ROOT_PATH.'font/shuiyin.png',$config['watermark_local'])->save($upload_path.DS.$path);
							} else if($config['watermark_type'] == '2'){
								// 文字水印
								$image->text($config['watermark_text'],ROOT_PATH .'font/font.ttf',$config['watermark_text_size'],$config['watermark_text_color'],$config['watermark_local'])->save($upload_path.DS.$path);
							}
						}
						$local = '/uploads/'.$path; // 返回路径
						$path = $local; // 返回路径
					}else{
						return $file->getError();
					}
    			break;
    	}
    	// 文件入库
		$fileInfo = $info->getInfo();
    	$file_data = [
			'name'=>$fileInfo['name'],
			'type'=>$fileInfo['type'],
			'size'=>$fileInfo['size'],
			'path'=>$path,
			'local'=>$local,
			'timestamp'=>request()->time(),
    	];
    	//db('upload')->insert($file_data);
    	return $path;
    }
    /**
     * 生成推广海报
     * @param $siteId
     * @param $uid
     */
    public function getQrcode($siteId,$uid) {

    }
}
	