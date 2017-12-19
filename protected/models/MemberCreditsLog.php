<?php
class MemberCreditsLog extends CActiveRecord
{
    public  $username;
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{member_credits_log}}';
    }
    public function rules()
    {
        return array(
            array('username,credits,remarks', 'required','on'=>'logcreate'),
            array('username', 'customValidatorUsername','on'=>'logcreate'),
            array('credits', 'numerical', 'integerOnly' => true,'on'=>'logcreate'),
            array('credits', 'in','not'=>true, 'range' => array(0),"message"=>"积分不能等于0",'on'=>'logcreate'),
            array('remarks', 'length','min'=>2, 'max' => 100,'on'=>'logcreate')
            /*array('credits', 'numerical', 'integerOnly' => true),
            array('credits', 'in','not'=>true, 'range' => array(0),"message"=>"积分不能等于0"),
            array('remarks', 'length','min'=>2, 'max' => 100),*/
            //
/*            array('title, credits,status,content,coverimage,previewimage,order', 'required'),
            array('credits,status,order', 'numerical', 'integerOnly' => true),
            array('status','length','is'=>1),
            array('order','length','max'=>5),
            array('credits','length','max'=>10),*/
            /*array('title', 'required','message'=>"请填写文章标题",'on'=>'admin'),
            array('status', 'required','message'=>"请选择状态",'on'=>'admin'),
            array("cid",'required','message'=>"请选择栏目"),
            array('title', 'length', 'max' => 60,'message'=>'标题长度不能超过60'),
            array('keywords', 'length', 'max' => 120,'message'=>'关键字长度不能超过120'),
            array('descriptions', 'length', 'max' => 250,'message'=>'描述长度不能超过250'),
            array('tags', 'length', 'max' => 100,'message'=>'标签长度不能超过100','on'=>'admin'),
            array('content', 'required','message'=>"请填写文章内容"),
            array('content', 'length', 'min' => 10,'message'=>'文章内容不能少于10个字符'),
            array('tags', 'normalizeTags','on'=>'admin'),*/
            //array('jointime, overtime, status', 'numerical', 'integerOnly' => true),

            //array('name,createtime,lasttime', 'length', 'max' => 10),
            //array('name, alias, attrib', 'length', 'max' => 32),
            //array('title, fromer, keyword', 'length', 'max' => 96),
            //array('preview', 'length', 'max' => 48),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            //array('id, cd, name, title, alias, attrib, fromer, keyword, preview, content, jointime, overtime, times, status', 'safe', 'on' => 'search'),
        );
    }
    /*
     * 验证用户名
     * */
    function customValidatorUsername($attribute, $params)
    {
        $member_model = new Member();
        $member_data = $member_model->find("username=:username",array(":username"=>$this->username));
        if(empty($member_data)){
            $this->addError($attribute, '该用户名不存在');
        }
    }
    public function relations()
    {
        return array(
            //'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            //'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
            'member' => array(self::BELONGS_TO, 'Member', 'memberId'),
            'creditssource'=>array(self::BELONGS_TO, 'MemberCreditsSource', 'source'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'create_datetime' => '创建时间',
            'remarks'=>'备注',
            'credits'=>'积分变化',
            'opid'=>'操作员',
            'account_credits'=>'可使用积分',
            'memberId'=>'会员',
            'username'=>"用户名",
            'source'=>'积分来源',
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
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }
    /*
     * 获取操作员
     * */
    public function getOPname()
    {
        if($this->opid==0){
            $data = "系统";
        }elseif($this->opid=="" || $this->opid<0){
            $data = "发生错误，请联系技术";
        }else{
            $manage_model = new Manage();
            $manage_data = $manage_model->findByPk($this->opid);
            if(empty($manage_data)){
                $data = "发生错误，请联系技术";
            }else{
                $data = $manage_data['name'];
            }
        }
        return $data;
    }

    //更新积分日志
    public static function updateCreditsLog($uid,$credits,$source='weixin'){
        $credits_model = new MemberCreditsLog();
        $credits_model->create_datetime = time();
        $credits_model->memberId = $uid;
        $credits_model->credits = 1000;
        $credits_model->remarks = "用户绑定微信获赠1000积分";
        $credits_model->opid = 0;
        $credits_model->source =$source;
        $credits_model->account_credits =$credits+1000;
        $credits_model->save();
    }
    /*
     * 获取会员名
     * */

}