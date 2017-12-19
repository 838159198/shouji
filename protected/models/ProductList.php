<?php

/**
 * This is the model class for table "{{product_list}}".
 *
 * The followings are the available columns in table '{{product_list}}':
 * @property string $id
 * @property integer $pid
 * @property integer $status
 * @property string $pakid
 * @property string $pakname
 * @property integer $createtime
 * @property string $sign
 * @property string $appurl
 * @property string $isshow
 * @property string $version
 */
class ProductList extends CActiveRecord
{
	/** 权限 - 用户 */
	const AUTH_MEMBER = 0;
	/** 权限 - 管理 */
	const AUTH_MANAGE = 1;
	/** 权限 - 临时关闭 */
	const AUTH_CLOSED = 2;
    public $filename;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product_list}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('status,type,pakid,version,pakname', 'required'),
			array('type,version', 'length', 'max'=>50),
            array('type','check'),
			array('pakid', 'length', 'max'=>50),
            array('pakname', 'length', 'max'=>100),
            array('version','match','pattern'=>'/^[V|v][a-zA-Z0-9]/','message'=>'版本号必须以字母V或v开头'),
            //array('appurl','file','allowEmpty'=>false,'message'=>'上传包文件出错'),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,status,pid,pakid,pakname,sign,appurl,createtime,version,isshow,type,filesize,agent', 'safe', 'on'=>'search'),
		);
	}
    //上传同一业务包个数限制
    public function check()
    {
        $typeList = ProductList::model()->count("type=:type and status=:status and agent=:agent",array(":type"=>$this->type,":status"=>1,":agent"=>$this->agent));
        if ($typeList>20) {
            $this->addError('type', '该产品包已存在20个，请先进行状态修改在上传');
            return;
        }


    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'p' => array(self::BELONGS_TO, 'Product', 'pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pid' => '业务id',
            'type'=>'业务类型',
			'status' => '状态',
			'pakid' => '包id',
			'pakname' => '包名',
			'sign' => 'md5',
			'appurl' => 'app下载地址',
			'createtime' => '录入时间',
            'isshow' => 'isshow',
            'version' => '版本号',
            'filename'=>'文件名',
            'agent'=>'分组',
            'filesize'=>'文件大小'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
        $criteria->with = array('p');
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.pid',$this->pid,true);
		$criteria->compare('t.status',$this->status,true);
        $criteria->compare('t.pakid',$this->pakid,true);
        $criteria->compare('t.pakname',$this->pakname,true);
        $criteria->compare('t.sign',$this->sign,true);
        $criteria->compare('t.type',$this->type,true);
        $criteria->compare('t.appurl',$this->appurl,true);
        $criteria->compare('t.isshow',$this->isshow,true);
        $criteria->compare('t.agent',$this->agent,true);
        $criteria->compare('t.createtime',$this->createtime,true);
        $criteria->compare('t.version',$this->version,true);
        $criteria->compare('t.filesize',$this->filesize,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
                //加入排序
                'attributes'=>array(
                    'pname'=>array(
                        'asc'=>'p.name',
                        'desc'=>'p.name DESC',
                    ),
                    '*',
                ),
            ),
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 获取所有可用
	 * @param int $auth
	 * @return Resource[]
	 */
	public function getAll($auth = 1)
	{
		$c = new  CDbCriteria();
		$c->addCondition('status=:status');
		$c->params = array(':status' => Common::STATUS_TRUE);
		return $this->findAll($c);
	}

	/**
	 * 获得可用的keyword
	 * @param int $status
	 * @return Resource[]
	 */
	public function getKeywordList($status = Common::STATUS_TRUE)
	{
		$keys = array();
		$list = $this->findAll('status=:status', array(':status' => $status));
		foreach ($list as $v) {
			/** @var $v Product */
			$keys[$v->pathname] = $v->name;
		}
		return $keys;
	}
    /*
    * 状态
    * */
    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>可用</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>不可用</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
    public function getlistDataStatus()
    {
        $data = array(array("key"=>0,"value"=>"不可用"),array("key"=>1,"value"=>"可用"));
        //$data[1]="代理商";
        //$data[0]="普通用户";
        return $data;
    }
    /*
   * 显示
   * */
    public function getXisshow()
    {
        $data = "";
        if($this->isshow==1){
            $data = "<font color=#979EFF><b>显示</b></font>";
        }elseif($this->isshow==0){
            $data = "<font color=#ff0000><b>不显示</b></font>";
        }else if($this->isshow==2){
            $data = "<font color=#ff0000><b>确认中</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
    public function getlistDataIsShow()
    {
        $data = array(array("key"=>0,"value"=>"不显示"),array("key"=>1,"value"=>"显示"),array("key"=>2,"value"=>"确认中"));
        //$data[1]="代理商";
        //$data[0]="普通用户";
        return $data;
    }
    /*
  * 显示
  * */
    public function getXagent()
    {
        $data = "";
        if($this->agent==0){
            $data = "<b>0</b>";
        }elseif($this->agent==69){
            $data = "<b>69</b>";
        }elseif($this->agent==77){
            $data = "<b>77</b>";
        }elseif($this->agent==88){
            $data = "<b>88</b>";
        }elseif($this->agent==96){
            $data = "<b>96</b>";
        }
        elseif($this->agent==99){
            $data = "<b>99</b>";
        }
        elseif($this->agent==707){
            $data = "<b>707</b>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
    /*
   * 用户分组
   * */
    public function getlistDataAgent(){
        //分组
//        $sql = 'select agent from app_product_list WHERE `status`=1 GROUP BY agent';
//        $memberGroup = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array(array("key"=>0,"value"=>"0"),array("key"=>69,"value"=>"69"),array("key"=>77,"value"=>"77"),array("key"=>88,"value"=>"88"),array("key"=>96,"value"=>"96"),array("key"=>99,"value"=>"99"),array("key"=>707,"value"=>"707"));
        return $data;
    }
    public static function getlistDataMemberGroup(){
        $arrAgent[0]=0;
        $arrAgent[69]=69;
        $arrAgent[77]=77;
        $arrAgent[88]=88;
        $arrAgent[96]=96;
        $arrAgent[99]=99;
        $arrAgent[707]=707;
        return $arrAgent;
    }

    /**
     * 获取产品列表
     */
    public function getProductList()
    {
        $res = Ad::getAdList();
        return $res;
    }
    /**
     * 
     * 根据产品id获取中文名
     */
    public static function getname($id){
        $sql="select name from `app_product` where id=".$id;
        $data=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['name'];
        }else{
            return '';
        }
    }
    /**
     * 业务包二次确认页面操作
     * 
     */
    public static function handle($id){
        return '<a class="label label-primary" onclick="handle('.$id.')" href="javascript:;">显示</a>';
    }
    /**
     * 获取操作人中文名
     * 
     */
    public static function manage($id){
        $sql='select name from `app_manage` where id='.$id;
        $data=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['name'];
        }else{
            return '';
        }
    }
    /**
     * 
     * 根据产品id获取客户用户名
     */
    public static function getmemname($id){
        $sql="select username from `app_member` where id=".$id;
        $data=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['username'];
        }else{
            return '';
        }
    }
    /**
     * 根据imei获取操作人
     *
     */
    public static function imei($mid){
        $sql='select name from `app_manage` where id='.$mid;
        $data=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['name'];
        }else{
            return '系统';
        }
        
       
    }
    /**
     * 
     * 
     * @param  [type] $id [app_router_manage]
     * @return [type]     [详情]
     */
    public static function detail($id){
        return '<a target="_blank" title="查看" href="/dhadmin/page/detail?id='.$id.'"><img src="/assets/9700441a/gridview/view.png" alt="查看"></a>';
    }
    /**
     * /
     * @param  [type] $status [状态]
     * @return [type]         [1=正常;2=损坏;3=退回]
     */
    public static function condition($status){
        switch($status){
            case 1:
            return '<font color=green>正常</font>';
            break;
            case 2:
            return '<font color=red>损坏</font>';
            break;
            case 3:
            return '<font>退回</font>';
            default:
            return '';
            break;
        }
    }
    /**
     * 
     * 
     * @param  [type] $id        [app_router_list id]
     * @param  [type] $uid       [用户]
     * @param  [type] $coding    [设备码]
     * @param  [type] $router_id [app_router_manage]
     * @param  [type] $status    [设备状态 正常，损坏，退回]
     * @return [type]            [description]
     */
    public static function operation($id,$uid,$coding,$router_id,$status,$handle_status){
        $modelBill = MemberBill::model()->getByUid($uid);
        if(!empty($modelBill->surplus)){
            $cha=$modelBill->yj_total-$modelBill->dj_total;//押金和冻结金差值
            $cha2=$modelBill->surplus-$cha;//余额-差值
            if($cha2>=0){
                $modelBill->surplus=$modelBill->surplus-$cha;//余额
                $modelBill->dj_total=$modelBill->yj_total;//冻结金
            }else{
                $modelBill->dj_total=$modelBill->dj_total+$modelBill->surplus;//冻结金
                $modelBill->surplus=0;//余额    
            }
            $modelBill->save();
        }

        // $sql="select * from `app_router_manage` where id=".$router_id;
        // $result=yii::app()->db->createCommand($sql)->queryAll();
        // $kjd_sum=0;//最大解冻金额
        // if(!empty($result)){//优先资金
        //     if($status==1 || $status==3){//正常或退回
                if($modelBill->dj_total>150){//最大可解冻资金
                    $kjd_sum=150;
                }else{
                    $kjd_sum=$modelBill->dj_total;
                }
                

        //     }else{//损坏
        //         $kjd_sum=0;
        //     }
        // }

        // if(!empty($result) && $result[0]['type']==2){//优先设备

        // }


        //已处理或未处理
        if(isset($handle_status) && $handle_status==2){
            return '<button disabled class="btn btn-xs" style="background-color: #337ab7;color:#fff;width:70px" type="button">已处理</button>';
        }
        return '<button  class="btn btn-xs" style="background-color: #337ab7;color:#fff;width:70px" type="button" onclick="handle('.$id.','.$uid.',\''.$coding.'\','.$kjd_sum.','.$status.')">处理</button>';
    }

    /**
     * /
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public static function content($type){
        switch($type){
            case 1:
            return '添加会员';
            break;
            case 2:
            return '解封押金';
            break;
            case 3:
            return '冻结金扣除';
            break;
            case 4:
            return '发货状态修改';
            break;
            default:
            return '';
            break;
        }
    }

    /**
     * 
     * 操作id
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function lydetail($id){
        if(isset($id) && !empty($id)){
            $sql="select * from `app_router_log` where id={$id} limit 1";
            $result=yii::app()->db->createCommand($sql)->queryAll();
            $general=0;
            $luyou='';

            
            if(!empty($result)){
                $username=self::getmemname($result[0]['uid']);
                $manage=self::imei($result[0]['mid']);
                $type=self::content($result[0]['type']);
                $content=json_decode($result[0]['content'],true);
                $num=$beizhu=$coding=0;
                if(!empty($content)){
                    $num=!empty($content['shebei']) ? $content['shebei'] : 0;
                    $beizhu= !empty($content['beizhu']) ? $content['beizhu'] : '';
                    $coding= !empty($content['bianma']) ? $content['bianma'] : '';

                    if(isset($content['yajin'])){
                        $general=$content['yajin'];
                    }else if(isset($content['kouchu'])){
                        $general=$content['kouchu'];
                    }else if(isset($content['jiefeng'])){
                        $general=$content['jiefeng'];
                    }else{
                        $general=0;
                    }
                    $luyou=isset($content['luyou']) ? $content['luyou'] : '';
                }
                    

                // print_r($content);exit;
                return'<a href="javascript:;" onclick="xiangqing(\''.$username.'\',\''.$manage.'\',\''.$result[0]['createtime'].'\',\''.$type.'\',\''.$coding.'\',\''.$beizhu.'\','.$num.','.$general.',\''.$luyou.'\')"><img src="/assets/9700441a/gridview/view.png" alt="查看"></a>';
            }
            
        }
        
    }

}
