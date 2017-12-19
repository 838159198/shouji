<?php

/**
 * This is the model class for table "{{note_log}}".
 *
 * The followings are the available columns in table '{{note_log}}':
 * @property string $id
 * @property string $mid
 * @property string $uid
 * @property string $detail
 * @property integer $createtime
 */
class NoteLog extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MemberInfoLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{note_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(

            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, mid, uid, detail, createtime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户ID',
            'mid' => '管理员',
            'detail' => '修改前内容',
            'createtime' => '修改时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('mid', $this->mid, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('detail', $this->detail, true);
        $criteria->compare('createtime', $this->createtime);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @param 添加日志记录
     */
    public static  function addLog($detail,$mid,$uid,$tag='',$update_detail='')
    {
        $noteLog=new NoteLog();
        $noteLog->uid=$uid;
        $noteLog->mid=$mid;
        $noteLog->detail=$detail;
        $noteLog->tag=$tag;
        $noteLog->createtime=date('Y-m-d H:i:s', time());
        $noteLog->update_detail=$update_detail;
        $noteLog->save();
    }
}