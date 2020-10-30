<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Article as ArticleModel;
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


         foreach ($articleList as $key => $value) {
            //跳过模型层，直接从数据库中查询teacher表
            $categoryName = Db::name('ArticleCategory')->where(array('id'=>$value['category_id']))->value('categoryName');
            if(empty($categoryName)){
               $articleList[$key]['categoryName'] = '<span style="color:red;">未分配</span>'; 
            }
            $articleList[$key]['categoryName'] =  $categoryName;
            
        }
        $this->view->assign('articleList',$articleList);
        return $this ->view-> fetch('article_list');
    }

    //文章状态变更
    public function setStatus(Request $request){
        $article_id=$request->param('id');
        $result=ArticleModel::get($article_id);
        if($result->getData('status')==1){
            ArticleModel::update(['status'=>0],['id'=>$article_id]);
        }else{
            ArticleModel::update(['status'=>1],['id'=>$article_id]);
        }
    }
     
    //渲染文章添加界面
    public function articleAdd()
    {
        //给模板赋值seo变量
        $this->view->assign('title','添加文章');

        //将文章分类表中所有数据赋值给当前模板
        $this->view->assign('articleCategoryList',\app\admin\model\ArticleCategory::all());
        //渲染添加模板
        return $this->view->fetch('article_add');
    }



    //添加文章操作
    public function doAdd(Request $requst)
    {
        $data=$this->request->param();
        // print_r($data);die;
        $dataList['name']=$data['name'];
        $dataList['author']=$data['author'];
        $dataList['content']=$data['content'];
        $dataList['category_id']=$data['category_id'];
        $dataList['is_hot']=$data['is_hot'];
        $dataList['status']=$data['status'];
        
        $dataList['create_time']=time();
        $dataList['update_time']=time();
        if(!empty($data['image'])){
            $dataList['img'] = $data['img'];
        }
        $rule=[
            'name|文章标题'=>'require',
            'author|作者'=>'require',
            'content|内容'=>'require',
        ];
        $res=$this->validate($dataList,$rule);
        $status=0;
        $message="添加失败！";
        if ($res===true) {
            # code...
             $result=ArticleModel::create($dataList);
            if($result!==null){
                $status=1;
                $message="添加成功！";
        }
        }
        return ['status'=>$status,'message'=>$message];
    } 

     /**
     * 文章编辑界面渲染
     */
    public function articleEdit(Request $requst)
    {
        $article_id = $requst->param('id');//前端提交过来的ID字段
        $result = ArticleModel::get($article_id)->toArray();//根据当前用户id查询表
         $this->view->assign('articleCategoryList',\app\admin\model\ArticleCategory::all());
        $this->view->assign('article_info',$result);
        return $this->view->fetch('article_edit');
    }

      //更新数据操作
    public function editArticle(Request $request)
    {
        $data = $request -> param();
        $dataList['name']=$data['name'];
        $dataList['author']=$data['author'];
        $dataList['content']=$data['content'];
        $dataList['category_id']=$data['category_id'];
        $dataList['is_hot']=$data['is_hot'];
        $dataList['status']=$data['status'];
        $dataList['create_time']=time();
        $dataList['update_time']=time();
        if(!empty($data['image'])){
            $dataList['img'] = $data['img'];
        }
        $result = ArticleModel::where('id',$data['id'])->update($dataList);

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
