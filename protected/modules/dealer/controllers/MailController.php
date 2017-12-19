<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-4-12 下午3:56
 * Explain:系统通知
 */
class MailController extends DealerController
{
    /**
     * Index
     */
    public function actionIndex()
    {
        $status = '<' . Mail::STATUS_MEMBER_DEL;
        $dataProvider = Mail::model()->getListByUid($status, $this->uid);
        $this->render('index', array('data' => $dataProvider));
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $t = Yii::app()->db->beginTransaction();
        try {
            $model->status = Mail::STATUS_READ;
            $model->update();
            $t->commit();
            $this->render('view', array('model' => $model));
        } catch (Exception $e) {
            $t->rollback();
            throw new CHttpException(500, '错误，请重试');
        }
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $t = Yii::app()->db->beginTransaction();
        try {
            $model->status = Mail::STATUS_MEMBER_DEL;
            $model->update();
            $t->commit();
        } catch (Exception $e) {
            $t->rollback();
        }
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect('index');
        }

        Yii::app()->end();
    }

    /**
     * 获取满足条件的行数(Ajax)
     */
    public function actionCount()
    {
        $status = '<' . Mail::STATUS_READ;
        $count = Mail::model()->getCountByUid($status, $this->uid);
        echo $count;
        Yii::app()->end();
    }

    /**
     * @param $id
     * @return Mail
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Mail::model()->getById($id);
        if ($model === null || $model->recipient != $this->uid)
            throw new CHttpException(404, '无此数据，或查看权限');
        return $model;
    }
}