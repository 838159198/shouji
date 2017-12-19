<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/5
 * Time: 16:18
 */
class Apk extends CActiveRecord
{
    public function tableName()
    {
        return '{{apk}}';
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            /*array('name, pathname, pic, createtime, updatetime, install_instructions, activate_instructions, content', 'required'),
            array('status, auth, createtime, updatetime, order', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>20),
            array('pathname', 'length', 'max'=>10),
            array('pic', 'length', 'max'=>60),
            array('officialprice, price, quote', 'length', 'max'=>7),
            array('install_instructions, activate_instructions', 'length', 'max'=>255),
            array('enrollment', 'length', 'max'=>8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, pathname, pic, status, auth, officialprice, price, quote, createtime, updatetime, order, install_instructions, activate_instructions, content, enrollment', 'safe', 'on'=>'search'),*/
        );
    }
    /**
     * 根据条件获取列表id
     * @param int $cid 分类,int $num显示数量,string $condition条件
     * @return String
     * */
    protected function getListById($cid=0,$num,$condition){
        
        $model = new Apk();
        $sql = "SELECT `aa_id` FROM {{apk}} WHERE `pc_recommend` = 1 ORDER BY `number` LIMIT 0,{$num}";
    }
    /*
     * pc端手机助手推荐应用接口
     * */
    public function getHelperRecommendById($categoryIdData,$num=0){
        if($num == 0){
            $sql = "SELECT id FROM {{apk}} WHERE `cid` IN({$categoryIdData}) AND `helper_recommend` = 1 ORDER BY `helper_recommend_order` DESC,`id` DESC";
        }else{
            $sql = "SELECT id FROM {{apk}} WHERE `cid` IN({$categoryIdData}) AND `helper_recommend` = 1 ORDER BY `helper_recommend_order` DESC,`id` DESC LIMIT 0,{$num}";
        }

        $data = $this->findAllBySql($sql);
        if($data){
            foreach ($data AS $row){
                $id[] = $row['id'];
            }
            $id = implode(",",$id);
        }else{
            $id = "";
        }
        return $id;
    }
    /**
     * 查询apk详情
     * @param int $id
     * @return array
     * */
    public function getApkDetail($id){
        $data = $this->findByPk($id);
        if($data['product_status'] == 1){
            //通过product_id远程获取数据
            $data['appurl'] = "yuancheng.apk";
        }
        return $data;
    }
    /**
     * 获取数据集合
     * @param string $idString
     * @return array
     * */
    public function getApkListData($idString){
        //将id字符串转成数组
        $idArray = explode(",",$idString);
        foreach ($idArray AS $row){
            $data[] = $this->getApkDetail($row);
        }
        return $data;
    }
}