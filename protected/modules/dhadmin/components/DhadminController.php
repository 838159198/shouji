<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class DhadminController extends CController
{
    /**
     * 当前用户权限
     * @var array
     */
    protected $auth;
    /**
     * 当前用户ID
     * @var int
     */
    protected $uid, $username;
    /*public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        //echo Yii::app()->getController()->id;
    }*/
    //访问action之前进行权限判断
    protected function beforeAction($action)
    {
        //echo Yii::app()->getController()->id;
        //echo $this->getId();
        //echo $this->getAction()->id;
        //先判断是否登录
        if(!Yii::app()->user->isGuest){
            //判断是否是管理员账号
            if(Yii::app()->user->type!="Manage"){
                throw new CHttpException(403,"您没有访问权限");
            }else{

                //如果用户组，管理员无限制，其他判断auth权限
                if(Yii::app()->user->manage_group==1){
                    return parent::beforeAction($action);
                }else{
                    //获取当前controller和action名称
                    $controller = $this->getId();
                    $action = $this->getAction()->id;
                    //判断是否存在
                    if(in_array(strtolower($controller."_".$action),explode(",",strtolower(Yii::app()->user->manage_auth)))){
                        return parent::beforeAction($action);
                    }else{
                        throw new CHttpException(403,"您没有访问权限");
                    }
                }
            }
        }else{
            throw new CHttpException(500,"请先登录");
        }
        //return parent::beforeAction($action);
    }

    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
	/**
     * 跳过验证的权限
     * @return array
     */
    protected function skipAuth()
    {
        return array();
    }

    /**
     * 验证是否跳过权限验证，true为跳过
     * @param $auth
     * @return bool
     */
    private function checkSkipAuth($auth)
    {
        $skip = $this->skipAuth();
        if (in_array($auth, $skip)) {
            return true;
        }
        return false;
    }

    /**
     * 控制器过滤器
     * @return array
     */
    public function filters()
    {
        return parent::filters();
    }

    /**
     * @param CAction $action
     * @return bool
     */
/*    protected function beforeAction($action)
    {
        $currentAuth = Auth::getAuthByAction($action);
        if (!$this->checkSkipAuth($currentAuth)) {
            if (!Auth::check($currentAuth, $this->auth)) {
                Common::redirect(Yii::app()->createUrl('/manage/default/index'), '无此权限');
            }
        }
        return parent::beforeAction($action);
    }*/

    protected function afterAction($action)
    {
        parent::afterAction($action);
        //关闭数据库连接
        Yii::app()->db->setActive(false);
    }
}