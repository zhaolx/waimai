<?php 
class GoodModel extends RelationModel{
    protected $_link = array(
            'Cate'=>array(
            'mapping_type'    =>BELONGS_TO,
                 'class_name'    =>'Cate',
				 'foreign_key'   =>'cid',
				 'mapping_name'=>'cid',
				 'as_fields'=>'name:cate_name',
                 // 定义更多的关联属性
             ),
         );

}
?>