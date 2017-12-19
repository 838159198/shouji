<?php
/**
 * Explain:SerachInfoController.php
 * @name 录入资料库
 */
class SerachInfoController extends DhadminController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @name 过滤器
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * @name 开发-权限规则
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','zxjlcreate','updatestatus'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * @name 查看信息
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * @name 录入信息
	 */
	public function actionCreate()
	{

		$model=new SerachInfo;
		$uid=Yii::app()->user->manage_id;
		if(isset($_POST['SerachInfo']))
		{
			$model->attributes=$_POST['SerachInfo'];
			$tixingtime=$_POST['SerachInfo']['tixingtime'];
			if(empty($tixingtime)){$model->tixingtime=NULL;}
			if($model->save())
			{
				echo '<script type="text/javascript">alert("新增成功！");window.location.href="/dhadmin/serachInfo/create"; </script>';
			}

		}

		$this->render('create',array(
			'model'=>$model,
			'uid'=>$uid
		));
	}

	/**
	 * @name 修改信息
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		$uid=Yii::app()->user->manage_id;
		$sid=$model->manage_id;
		$statust=$model->status;

		if($statust==1)
		{
			echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
			echo '<script charset="utf-8"  type="text/javascript">alert("已为有效用户，不能再次编辑！");history.go(-1); </script>';
			exit;
		}

		if(isset($_POST['SerachInfo']))
		{
			$model->attributes=$_POST['SerachInfo'];
			$tixingtime=$_POST['SerachInfo']['tixingtime'];
			if(empty($tixingtime)){$model->tixingtime=NULL;}
			if($model->save())
			{
				$attr = $model->getAttributes();
				$info = '';

				$skip = array('name', 'tel', 'mail', 'qq', 'content', 'com', 'area', 'source', 'search_id', 'manage_id', 'motifytime', 'tixingtime');
				foreach ($attr as $k => $v) {
					if (empty($v)) continue;
					if (in_array($k, $skip));
					$info .= ' [' . $model->getAttributeLabel($k) . '] ' . $v;
				}

				//添加log
				if (!empty($info)) {
					$logModel = SerachInfoLog::model();
					$logModel->setIsNewRecord(true);
					$logModel->unsetAttributes();
					$logModel->mid = $id;
					$logModel->utype = Common::USER_TYPE_MANAGE;
					$logModel->username = $this->username;
					$logModel->detail = $info;
					$logModel->createtime = time();
					$logModel->insert();
				}

				$this->redirect(array('view','id'=>$model->id));
			}

		}

		$this->render('update',array(
			'model'=>$model,
			'uid'=>$uid
		));
	}
	/**
	 * @name 状态审核
	 */
	public function actionUpdatestatus($id,$isv,$isr,$status)
	{
		if(($status!=1) && ($status!=2) )
		{
			echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
			echo '<script charset="utf-8"  type="text/javascript">alert("输入错误！");history.go(-1); </script>';
			exit;
		}
		if($isv==1)
		{
			echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
			echo '<script charset="utf-8"  type="text/javascript">alert("有效状态不能再次更改！");history.go(-1); </script>';
			exit;
		}
		// if(($status==1) && ($isr==0))
		// {
		// 	echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
		// 	echo '<script charset="utf-8"  type="text/javascript">alert("未注册不能更改为有效！");history.go(-1); </script>';
		// 	exit;
		// }

		$count=SerachInfo::model()->updateAll(array(
				'status' => $status,
				'motifytime' => date('Y-m-d H:i:s',time())),
			" id = ( " . $id . " )");

		if($count){
			echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
			echo '<script charset="utf-8" type="text/javascript">alert("修改成功!");history.go(-1); </script>';
		}else{
			echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
			echo '<script charset="utf-8" type="text/javascript">alert("有效状态不能再次更改!");history.go(-1); </script>';
		}

	}

	/**
	 * @name 删除信息
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * @name 首页
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('SerachInfo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * @name 信息管理
	 */
	public function actionAdmin()
	{
/*		$id=$this->uid;
		Yii::app()->session['snuid'] = $id;*/

		$model=new SerachInfo('search');
		$model->unsetAttributes();
		if(isset($_GET['SerachInfo']))
			$model->attributes=$_GET['SerachInfo'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * @param $id
	 * @name 咨询记录管理
	 */
	public function actionZxjlcreate($id)
	{
		$model = new SerachinfoRecords();

		if (isset($_POST['SerachinfoRecords'])) {
			$model->attributes = $_POST['SerachinfoRecords'];
			if ($model->validate()) {
				$model->sid = $id;
				$model->mid = Yii::app()->user->manage_id;
				$model->jointime = time();
				SerachInfo::model()->updateAll(array (
					'zixuntime' => date('Y-m-d')
				), " id =" . $id);

				$model->insert();
				$this->refresh();
			}
		}

		$dataProvider = $model->getBySid($id);
		$this->render('zxjlcreate', array(
			'dataProvider' => $dataProvider,
			'model' => $model
		));
	}
	/**
	 * @name 开发使用-查找id
	 */
	public function loadModel($id)
	{
		$model=SerachInfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * @name 开发使用-请求
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='serach-info-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
