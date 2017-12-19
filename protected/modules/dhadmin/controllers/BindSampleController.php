<?php
/**
 * 业务资源管理
 */
class BindSampleController extends DhadminController
{
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 * 未使用
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 业务资源创建
	 */
	public function actionCreate()
	{
        $product_model = new Product();
        $product_data = $product_model -> findAll();
		$model=new BindSample;
		if(isset($_POST['BindSample']))
		{
            if(!empty($_POST['BindSample']['val']))
            {
                $val=$_POST['BindSample']['val'];
                $val=array($val);
                $type=$_POST['BindSample']['type'];
                $bs=BindSample::model()->getByValList($val,$type);
                if(!empty($bs))
                {
                    throw new CHttpException(500, '录入ID已存在');
                }
            }
			$model->attributes=$_POST['BindSample'];
            $detail=json_encode($model->attributes);
			if($model->save()){
                //日志记录
                $mid = Yii::app()->user->manage_id;
                NoteLog::addLog($detail,$mid,$uid='',$tag='业务资源创建',$update='');
                $this->redirect('admin');
            }

	}
		$this->render('create',array(
			'model'=>$model,
            'product'=>$product_data
		));
	}

	/**
	 * 业务资源修改
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        $update=json_encode($model->attributes);
        $uid="";
        $type="";
        $val="";
        if(!empty($_POST['BindSample']['username']) && $_POST['BindSample']['uid']!=69 && $_POST['BindSample']['uid']!=77 && $_POST['BindSample']['uid']!=88 && $_POST['BindSample']['uid']!=96 && $_POST['BindSample']['uid']!=99 && $_POST['BindSample']['uid']!=707)
        {
            $username=$_POST['BindSample']['username'];
            $type=$_POST['BindSample']['type'];
            $val=$_POST['BindSample']['val'];
            $member=Member::model()->getByUserName($username);
            if(empty($member))
            {
                throw new CHttpException(500, '查无此人');
            }
            $uid=$member["id"];
            $mbrs=MemberResource::model()->getBidValue($uid,$type);
            if(empty($mbrs))
            {
                throw new CHttpException(500, '用户需先开启此业务');
            }

        }
		if(isset($_POST['BindSample']))
		{
            if(!empty($uid) && $uid!=69 && $uid!=77 && $uid!=88 && $uid!=96 && $uid!=99 && $uid!=707)
            {
                $_POST['BindSample']['uid']=$uid;
            }

			$model->attributes=$_POST['BindSample'];
            $detail=json_encode($model->attributes);//日志
			if($model->save())
            {
                $memlog=new MemberResourceLog();
                $memlog->uid=$uid;
                $memlog->type=$type;
                $memlog->mrid=$val;
                $memlog->createtime=DateUtil::time();
                $memlog->insert();

                //日志记录
                $mid = Yii::app()->user->manage_id;
                NoteLog::addLog($detail,$mid,$uid,$tag='业务资源编辑',$update);
                $this->redirect('admin');
            }

		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 * 业务资源删除--未使用
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * 未使用
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('BindSample');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * 业务资源列表
	 */
	public function actionAdmin()
	{
		$model=new BindSample('search');
		$model->unsetAttributes();
		if(isset($_GET['BindSample']))
			$model->attributes=$_GET['BindSample'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BindSample the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BindSample::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BindSample $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bind-sample-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
