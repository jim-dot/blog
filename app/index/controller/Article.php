<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Article as ArticleModel;
use think\Request;
use think\Session;
use think\Db;

/**
 * 文章管理
 */

class Article extends Base
{   

     protected function _initialize()
    {
        $this->assign('pTitle','文章管理');
    }

    /**
     * 文章列表
     */

    public function articleList()
    {
        $this->view->assign('title','文章列表');
        $this->view->assign('keywords','dong博客');
        $this->view->assign('desc','博客');

        $this->view->count = ArticleModel::count();//统计当前记录条数，保存在模板变量count中
        $articleList = ArticleModel::paginate(10);//分页查询
        $this ->view-> assign('articleList', $articleList);
        return $this ->view-> fetch('article_list');
    }
    
  
    //渲染文章添加界面
    public function articleAdd()
    {
        //给模板赋值seo变量
        $this->view->assign('title','添加文章');
        //渲染添加模板
        return $this->view->fetch('article_add');
    }


    //添加文章操作
    public function doAdd(Request $requst)
    {
        $data=$this->request->param();
        $rule=[
            'title|文章标题'=>'require',
            'author|作者'=>'require',
            'content|内容'=>'require',
        ];
        $data['create_time']=time();
        $res=$this->validate($data,$rule);
        $status=0;
        $message="添加失败！";
        if ($res===true) {
            # code...
             $result=ArticleModel::create($data);
            if($result!==null){
                $status=1;
                $message="添加成功！";
        }
        }
        return ['status'=>$status,'message'=>$message];
      /* $arr =array();
       $data = $this->request->param();
       $arr['image'] =json_encode($data['image']); 
       $arr['name'] =$data['name'];  
       $arr['sort'] =$data['sort'];  
       $arr['is_show'] =$data['is_show'];  
       $status = 1;
       $message = "添加成功！";
        $rule = [
            'name|文章标题' => 'require',
        ];
        $result = $this->validate($data,$rule);//验证
        if ($result === true) {
            $user = ArticleModel::create($arr);
            if ($user === null) {
                $status = 0;
                $message = '添加失败~~';
            }
        }else{
           $message = $result;
        }
       return ['status' => $status,'message' => $message];*/
    } 

     /**
     * 文章编辑界面渲染
     */
    public function articleEdit(Request $requst)
    {
        $article_id = $requst->param('id');//前端提交过来的ID字段
        $result = ArticleModel::get($article_id)->toArray();//根据当前用户id查询表
        $this->view->assign('article_info',$result);
        return $this->view->fetch('article_edit');
    }

      //更新数据操作
    public function editArticle(Request $request)
    {
        $data = $request -> param();
        $arr['name'] =$data['name'];  
        $arr['sort'] =$data['sort'];  
        $arr['is_show'] =$data['is_show'];
        if(!empty($data['image'])){
            $arr['image'] = $data['image'];
        }
        $result = ArticleModel::where('id',$data['id'])->update($arr);
        if (true == $result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }


   
   
    //删除操作
    public function deleteArticle(Request $requst)
    {
       $article_id = $requst->param('id');
       ArticleModel::destroy($article_id);
    }
    
}
