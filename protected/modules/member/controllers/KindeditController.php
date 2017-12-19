<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/8
 * Time: 14:36
 * kindedit图片上传
 */
class KindeditController extends MemberController
{
    public function actions()
    {
        return array(
            //在actions下的return array添加下面两句，没有actions的话自己添加
            'upload'=>array('class'=>'application.extensions.KEditor.KEditorUpload'),
            'manageJson'=>array('class'=>'application.extensions.KEditor.KEditorManage'),
        );
    }

}
