<?php
namespace app\admin\model;
use think\Model;
class Article extends Model
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

    //是否热门字段，is_hot返回值处理
    public function getIsHotAttr($value){
        $status=[
            0=>'否',
            1=>'是'
        ];
        return $status[$value];
    }

    public function getContentAttr($value)
    {   
        $content = json_encode($value);
        $content = json_decode($content);
        $content = htmlspecialchars_decode($content);
        return $content;
    }

    //定义关联方法
    public function article_category(){
        //文章与文章分类之间是多对一的反关联
        return $this->belongsTo('ArticleCategory');
    }

}
?>