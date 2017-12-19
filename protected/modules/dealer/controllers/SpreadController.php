<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/3
 * Time: 15:04
 * Explain:推广链接
 */
class SpreadController extends DealerController
{
    /**
     * Index
     */
    public function actionIndex()
    {
        $member = $this->loadModel($this->uid);
        if (empty($member->alias)) {
            $member->alias = Common::createTempPassword($member->username);
            $member->update();
        }
        $this->render('index', array(
            'member' => $member
        ));
    }

    /**
     * @param $id
     * @return MemberInfo
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Member::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}