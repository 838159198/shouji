<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/2/20
 * Time: 13:31
 */

class RomBoxPackage extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{rom_box_package}}';
    }


    public function rules()
    {
        return array(
            array('uid,pack_id,box_number', 'required'),
            array('uid', 'authenticate'),
            array('box_number','unique'),
            array('box_number', 'length', 'max' => 6,'message'=>'长度最大为6位'),
            array('box_number', 'length', 'is' => 6,'message'=>'长度必须为6位'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,uid,pack_id,box_number,status,', 'safe', 'on' => 'search'),
        );
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'package' => array(self::BELONGS_TO, "RomPackage", "pack_id"),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户名',
            'pack_id' => '套餐名',
            'status' => '状态',
            "box_number"=>"设备码",
        );
    }


    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('uid', $this->title, true);
        $criteria->compare('pack_id', $this->createtime, true);
        $criteria->compare('box_number', $this->lasttime, true);
        //$criteria->compare('status', $this->status);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }



    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public static function deleteData($box_number){
        $model=RomBoxPackage::model()->find("box_number=:box_number",array(":box_number"=>$box_number));
        if($model){
            $model->delete();
        }
    }

}