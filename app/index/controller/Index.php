<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use think\Session;
use think\Db;
class Index extends Base
{
    public function index()
    {
    	$this->view->assign('title','首页');
        $this->view->assign('keywords','dong博客');
        $this->view->assign('desc','博客');

        $this->view->count = ArticleModel::count();//统计当前记录条数，保存在模板变量count中
        $articleList = ArticleModel::paginate(10);//分页查询
        return $this->view->fetch();
    }
}
