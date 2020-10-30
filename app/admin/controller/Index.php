<?php
namespace app\admin\controller;
use app\admin\controller\Base;
class Index extends Base
{
    public function index()
    {
    	$this->isLogin();  //判断用户是否登录
    	$this->view->assign('title','博客后台系统');
        return $this->view->fetch();
    }
}
