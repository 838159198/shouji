<?php
/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-5-2 下午2:14
 * @name 用户咨询记录
 */
class AdvisoryrecordsController extends DhadminController
{
    /**
     * @param $uid
     * @name 客户咨询记录
     */
    public function actionIndex($uid)
    {
        $model = new AdvisoryRecords();

        if (isset($_POST['AdvisoryRecords'])) {
            $model->attributes = $_POST['AdvisoryRecords'];
            if ($model->validate()) {
                $model->uid = $uid;
                $model->mid =Yii::app()->user->getState("manage_id");
                $model->jointime = time();
                $model->insert();
                $this->refresh();
            }
        }

        $dataProvider = $model->getByUid($uid);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'model' => $model
        ));
    }
}