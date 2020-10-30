<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
/**
 * Api管理
 * @author dong
 */
class Api extends Base {
    

    /**
     * 上传图片
     */

    //原生上传
    public function uploadImages(){
        // 获取表单上传文件
        $files = request()->file('file');
        foreach($files as $file){
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->rule('date')->move('__ROOT__/Uploads');//以data作为文件名
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension(); 
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }    
        }
    }

    public function uploadImage() { 
     $files = request()->file('file');//TP5自带接受文件的方法 
        if($files){ 
            $info = $files->move(ROOT_PATH . 'public/uploads'); //把它移入到指点目录
            if($info){
                $filePath = $info->getSaveName();
                $filePath = str_replace("\\","/",$filePath);
                return $filePath;
                /*return json_encode($info->getSaveName());*///如果上传成功返回json类型的图片地址（适用多图）
            }else{ 
                echo $files->getError(); 
            } 
        } 
    }


}