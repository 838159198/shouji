<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property integer $id
 * @property string $title
 * @property integer $cid
 * @property string $tags
 * @property string $content
 * @property integer $createtime
 * @property integer $lasttime
 * @property integer $status
 * @property integer $uid
 * @property integer $hits
 * @property string $keywords
 * @property string $descriptions
 */
class SpreadSource extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{spread_source}}';
	}
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'source_name' => '渠道名称',
            'source_mark' => '渠道标识',
            'source_reg' => '注册人数',
            'createtime' => '创建时间',
            'mid'=>'创建人',
            'status'=>'状态',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('source_name',$this->source_name,true);
        $criteria->compare('source_mark',$this->source_mark,true);
        $criteria->compare('source_reg',$this->source_reg,true);
        $criteria->compare('createtime',$this->createtime,true);
        $criteria->compare('mid',$this->mid);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'm'=>array(self::BELONGS_TO,"Manage","mid"),
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /*
     * url
     * */
    public function getUrl()
    {
        return "/article/{$this->id}";
    }
    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>可用</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>禁用</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
}
