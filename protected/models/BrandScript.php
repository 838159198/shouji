<?php
class BrandScript extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{brand_script}}';
    }
    public function rules()
    {
        return array(
            //array('box_number,uid', 'required'),filesize
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,uid,md5,name,downPath,brand', 'safe', 'on' => 'search'),
        );
    }
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'Member', 'uid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户名',
            'md5' => 'md5值',
            'name' => '文件名',
            'brand' => '手机品牌',
            "downPath"=>"下载路径"
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('md5', $this->md5, true);
        $criteria->compare('brand', $this->brand,true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id', //设置默认排序
                //'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }
    // public static function getlistDataGroup(){
    //     $arrAgent['StFlashTool2']='StFlashTool2';
    //     $arrAgent['otherapk']='otherapk';
    //     $arrAgent['apklist']='apklist';
    //     return $arrAgent;
    // }
    // public function getlistDataStatus()
    // {
    //     $data = array(array("key"=>"StFlashTool2","value"=>"StFlashTool2"),
    //             array("key"=>"otherapk","value"=>"otherapk"),
    //             array("key"=>"apklist","value"=>"apklist")
    //     );
    //     return $data;
    // }

}