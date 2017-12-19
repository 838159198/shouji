<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/8
 * Time: 14:36
 * @name 业务一键关闭
 */
class CloseAllController extends DhadminController
{
    /*
     * @name: 业务关闭历史记录
     * */
    public function actionCloseAll(){

        $closeAll = new CloseAll();
        $criteria = new CDbCriteria();
        $criteria -> order = "id DESC";
        $count = $closeAll->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=20;
        $pager->applyLimit($criteria);
        $closeAlls = $closeAll -> findAll($criteria);
        $this->render("closeall",array('data'=>$closeAlls,'pages'=>$pager,'count'=>$count));
    }
    /*
     * @name: 业务关闭历史记录
     * */
    public function actionDetail($id,$type){

        $model = new MemberResourceLog();
        $criteria = new CDbCriteria();
        $criteria->condition='type=:type';
        $criteria->params=array(':type'=>$type);
        $criteria->condition='sign=:sign';
        $criteria->params=array(':sign'=>$id);
        $criteria -> order = "id DESC";
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=20;
        $pager->applyLimit($criteria);
        $closeAlls = $model -> findAll($criteria);
        $this->render("detail",array('data'=>$closeAlls,'pages'=>$pager,'count'=>$count));
    }
    /**
     * @name 一键关闭
     * @param string $type 业务类型
     * @param string $agent 用户分组
     * @param string $status 开关
     * @throws CHttpException
     */
    public function actionCloseType($type='', $agent='', $status='')
    {
        $type=isset($type)?$type:'llq';
        $agent=isset($agent)?$agent:0;
        $status=0;
        $mid = Yii::app()->user->manage_id;
        $members=Member::model()->findAll("status=1 and agent=:agent",array(":agent"=>$agent));
        if($agent==1){
            $members=Member::model()->findAll("status=1");
        }
        $resource = Product::model()->getByKeyword($type);
        if (is_null($resource)) throw new CHttpException(500, '不存在该业务。' );

        $closeModel=CloseAll::setCloseAll($mid,$type,$agent);
        $sign=$closeModel->id;//一键关闭标志
        // if ($resource->auth == Product::AUTH_CLOSED) throw new CHttpException(500, '开启状态出现错误。' );
        $num=0;
        foreach($members as $member){
            $uid=$member->id;
            $bindResource = MemberResource::model()->find("uid=:uid and type=:type and status=1 and openstatus=1",array(":uid"=>$uid,":type"=>$type));

            //如果没有绑定表中没有值
            if (is_null($bindResource)) continue;
            $num++;

            //添加LOG,更改开启状态
            MemberResourceLog::model()->add($bindResource, $status,$sign);
        }
        $closeModel->num=$num;
        $closeModel->update();
        $this->redirect(array("closeAll/closeall"));

    }
    /**
     * @name: 某分组开启某业务de个数
     */
    public function actionOpenAll()
    {
        if(Yii::app()->request->isAjaxRequest && (isset($_POST['agent'])) && (isset($_POST['type'])))
        {
            $agent = $_POST['agent'];
            $type=$_POST['type'];
            $sql="SELECT count(a.id)num FROM app_member_resource a LEFT JOIN app_member m ON a.uid=m.id WHERE a.type='{$type}' and m.agent={$agent} AND a.openstatus=1";
            if($agent==1){
                $sql="SELECT count(a.id)num FROM app_member_resource a LEFT JOIN app_member m ON a.uid=m.id WHERE a.type='{$type}'  AND a.openstatus=1";
            }
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            if(isset($data)){
                $resource = Product::model()->getByKeyword2($type);
                if($resource){
                    exit(CJSON::encode(array("status"=>200,"num"=>$data[0]['num'],"type"=>$resource->name)));
                }else{
                    exit(CJSON::encode(array("status"=>403,"message"=>"查询出错")));
                }
            }else{
                exit(CJSON::encode(array("status"=>403,"message"=>"查询出错")));
            }

        }else{
            exit(CJSON::encode(array("status"=>403,"message"=>"发生错误")));
        }
    }


}
