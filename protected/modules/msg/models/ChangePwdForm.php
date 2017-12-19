<?php
/**
 * Explain:修改密码模块
 */
class ChangePwdForm extends CFormModel
{
    public $old, $password, $again;
    private $_identity;

    public function rules()
    {
        return array(
            array('old,password,again', 'required'), //用户名、密码、确认密码必须填写
            array('old,password,again', 'length', 'min' => 6, 'max' => 16), //密码长度为6-16
            array('again', 'compare', 'compareAttribute' => 'password','message'=>'密码与确认密码必须一致'), //密码与确认密码必须一致
            array('old', 'authenticate'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'old' => '原始密码',
            'password' => '修改密码',
            'again' => '确认密码',
        );
    }

    /**
     * 验证规则
     */
    public function authenticate($attribute, $params)
    {

        if (!$this->hasErrors()) {
            $model = $this->loadModel();
            if (!$model->validatePassword($this->old)) {
                $this->addError('old', '原始密码错误');
            }
        }
    }

    /**
     * 修改
     */
    public function change()
    {
        if (!$this->hasErrors()) {
            $model = $this->loadModel();
            $update=' [password] '.$model->password;
            $model->password = $model->createPassword($this->password);
            $model->update();
            //日志记录

            if (Yii::app()->user->getState('member_manage')!=false){
                $mid = Yii::app()->user->manage_id;
            }else {
                $mid = '';
            }
            $detail=' [password] '.$model->createPassword($this->password);
            NoteLog::addLog($detail,$mid,$uid=$model->id,$tag='密码修改',$update);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return MemberInfo|null
     */
    private function loadModel()
    {
        return Member::model()->findByPk(Yii::app()->user->member_uid);
        //return Member::model()->findByPk(Yii::app()->user->member_id);
    }


}