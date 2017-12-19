<?php

/**
 * This is the model class for table "{{memberpool_bak}}".
 *
 * The followings are the available columns in table '{{memberpool_bak}}':
 * @property string $id
 * @property integer $mid
 * @property integer $uid
 * @property integer $status
 * @property string $createtime
 */
class MemberpoolBak extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MemberBill the static model class
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
        return '{{memberpool_bak}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('mid,uid,status', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, mid, uid, createtime, status', 'safe', 'on' => 'search'),
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
            'member' => array(self::HAS_ONE, 'Member', array('id' => 'uid')),
            'manage' => array(self::HAS_ONE, 'Manage', array('id' => 'mid'))
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'mid' => 'mid',
            'uid' => 'uid',
            'status' => 'status',
            'createtime' => 'createtime'
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
        $criteria->compare('mid', $this->mid);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('status', $this->status);
        $criteria->compare('createtime', $this->createtime);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 获得列表
     * @return CActiveDataProvider
     */
    public function getListAll($aid)
    {
        $c = new CDbCriteria();

        $now=time()-5*3600*24;

        $thisuid=Yii::app()->user->manage_id;

        $c->addCondition('t.status=0');
        $c->addCondition('member.manage_id=0');
        $c->order = 't.createtime desc';
        $c->with = array('member', 'manage');

        if(!empty($aid))
        {
            $c->addCondition('t.mid='.$aid);
            $post=MemberpoolBak::model()->findAll($c);
            return $post;
        }
        else
        {
            $post=MemberpoolBak::model()->findAll($c);

            foreach($post as $key=>$val)
            {
                if($val["mid"]!=$thisuid)
                {
                    if(strtotime($val["createtime"])>$now)
                    {
                        unset($post[$key]);
                    }

                    //
                }
            }
            //print_r($post);

            return $post;
        }



    }



}