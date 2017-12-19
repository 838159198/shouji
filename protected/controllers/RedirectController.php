<?php
/**
 * Explain: 跳转、异常页
 */
class RedirectController extends Controller
{
    //public $layout = '//layouts/redirect';
    public $css = array();

    public function actionIndex($url, $message)
    {
        $this->pageTitle = Yii::app()->name . ' - 跳转页';
        $this->renderPartial('index', array(
            'url' => $url,
            'message' => $message,
        ));
    }

    /**
     * 异常页
     */
    public function actionError()
    {
        $this->pageTitle = Yii::app()->name . ' - Error';
        $this->breadcrumbs = array('Error',);
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

}