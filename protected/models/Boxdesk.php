<?php
class Boxdesk extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{rom_boxdesk}}';
    }
    public function rules()
    {
        return array(
            //array('box_number,uid', 'required'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('tid,version', 'required','on'=>'create'),
            array('tid', 'length','min'=>6,'max' => 6),
            array('tid', 'numerical', 'integerOnly' => true),
            array('tid','unique','on'=>'create'),
            array('uid','unique','on'=>'edit'),
            array('id,uid,tid,md5,filename,downloadurl,filesize,version,createtime', 'safe', 'on' => 'search'),
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
            'tid' => '统计id',
            'md5' => 'md5值',
            'version' => '版本号',
            'filename' => '文件名',
            'filesize' => '文件大小',
            'createtime' => '创建时间',
            'updatetime' => '更新时间',
            "downloadurl"=>"下载路径"
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        /*$criteria->compare('title', $this->title, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('lasttime', $this->lasttime, true);*/
        //$criteria->compare('status', $this->status);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC', //设置默认排序
                //'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }

    /**
     * 根据用户分组获取统计软件个数
     * @param $type
     * @param $flag
     * @return $model[] count总数  count1已分配 count2未分配
     */
    public static function getDeskNum()
    {
        $sql ="SELECT COUNT(id)count, count(if(status=0,true,null))as count1,count(if(status=1,true,null)) as count2 FROM app_rom_boxdesk ";
        $model = Yii::app()->db->createCommand($sql)->queryRow();
        return $model;
    }

}