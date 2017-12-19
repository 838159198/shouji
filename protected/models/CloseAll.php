<?php
class CloseAll extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{close_all}}';
    }
    public function rules()
    {
        return array(
            array('id,mid,type,num,datetime', 'safe', 'on' => 'search'),
        );
    }
    public function relations()
    {
        return array(
            'm' => array(self::BELONGS_TO, 'Manage', 'mid')
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'mid' => '操作人员',
            'datetime' => '操作时间',
            'type' => '业务',
            'agent' => '分组',
            'num' => '关闭数量',
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
    //一键关闭记录
    public static function setCloseAll($mid,$type,$agent){
        $model =new CloseAll();
        $model->mid=$mid;
        $model->type=$type;
        $model->agent=$agent;
        $model->datetime=date("Y-m-d H:i:s");
        if($model->save()){
            return $model;
        }
    }
    public static function getAgent($agent){
        $arr = array(
            '0' => 'ROM开发者',
            '1' => '全部',
            '99' => '线下手机销售'
        );
        return $arr[$agent];
    }

}