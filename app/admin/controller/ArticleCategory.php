<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\ArticleCategory as ArticleCategoryModel;
use think\Request;
use think\Session;
use think\Db;

/**
 * 文章分类管理
 */

class ArticleCategory extends Base
{   

     protected function _initialize()
    {
        $this->assign('pTitle','文章分类管理');
    }

    /**
     * 文章分类列表
     */

    public function articleCategoryList()
    {
        $this->view->assign('title','文章分类列表');
        $this->view->assign('keywords','dong博客');
        $this->view->assign('desc','博客');

        $this->view->count = ArticleCategoryModel::count();//统计当前记录条数，保存在模板变量count中
        $articleCategoryList = ArticleCategoryModel::paginate(10);//分页查询
        $this ->view-> assign('articleCategoryList', $articleCategoryList);
        return $this ->view-> fetch('article_category_list');
    }

    //文章分类状态变更
    public function setStatus(Request $request){
        $articleCategory_id=$request->param('id');
        $result=ArticleCategoryModel::get($articleCategory_id);
        if($result->getData('status')==1){
            ArticleCategoryModel::update(['status'=>0],['id'=>$articleCategory_id]);
        }else{
            ArticleCategoryModel::update(['status'=>1],['id'=>$articleCategory_id]);
        }
    }
     
    //渲染文章分类添加界面
    public function articleCategoryAdd()
    {
        //给模板赋值seo变量
        $this->view->assign('title','添加文章分类');
        //渲染添加模板
        return $this->view->fetch('article_category_add');
    }



    //添加文章分类操作
    public function doAdd(Request $requst)
    {
        $data=$this->request->param();
        $dataList['categoryName']=$data['categoryName'];
        $dataList['status']=$data['status'];
        $dataList['create_time']=time();

        $rule=[
            'categoryName|文章分类标题'=>'require',
        ];
        
        $res=$this->validate($data,$rule);
        
        $status=0;
        $message="添加失败！";
        if ($res===true) {
            # code...
             $result=ArticleCategoryModel::create($dataList);
             
            if($result!==null){
                $status=1;
                $message="添加成功！";
        }
        }
        return ['status'=>$status,'message'=>$message];

    } 

     /**
     * 文章分类编辑界面渲染
     */
    public function articleCategoryEdit(Request $requst)
    {
        $articleCategory_id = $requst->param('id');//前端提交过来的ID字段
        $result = ArticleCategoryModel::get($articleCategory_id)->toArray();//根据当前用户id查询表
        $this->view->assign('articleCategory_info',$result);
        return $this->view->fetch('article_category_edit');
    }

      //更新数据操作
    public function editArticleCategory(Request $request)
    {
        $data = $request -> param();
        $dataList['categoryName']=$data['categoryName'];
        $dataList['status']=$data['status'];
        $dataList['update_time']=time();
        $result = ArticleCategoryModel::where('id',$data['id'])->update($dataList);

        if (true == $result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
 
    //删除操作
    public function deleteArticleCategory(Request $requst)
    {
       $articleCategory_id = $requst->param('id');
       ArticleCategoryModel::destroy($articleCategory_id);
    }
    
}
