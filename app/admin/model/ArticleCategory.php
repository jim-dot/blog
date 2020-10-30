<?php
namespace app\admin\model;
use think\Model;
class ArticleCategory extends Model
{

    //引用软删除方法集
    /*use SoftDelete;*/

    //设置当前表默认日期时间显示格式
    protected $dateFormat = 'Y/m/d H:i:s';

    //定义表中的删除时间字段,来记录删除时间
    // protected $deleteTime = 'delete_time';

    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
  

  

	//状态字段，status返回值处理
	public function getStatusAttr($value){
		$status=[
			0=>'隐藏',
			1=>'显示'
		];
		return $status[$value];
	}

    //定义关联方法
    public function article(){
        //文章分类与文章之间1对多关联
        return $this->hasMany('Article');

    }

}
?>