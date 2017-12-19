<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MsgController extends CController
{
    protected $uid, $username, $agent, $type, $scale, $point;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->setPageTitle(Common::getAppParam('name') . ' - 用户平台');
        //判断用户是否已登录，并且是普通用户登录
        $user = Yii::app()->user;
        if ($this->isLogin($user) === false) {
            throw new CHttpException(500, '未登录或登录已超时');
        }
        $this->uid = $user->getState('member_uid');
        $this->username = $user->getState('member_username');
        $this->type = $user->getState('type');
        if ($user->getState('member_cooperate')) {
            $this->coo = true;
        }
        // 防止用户登录其他平台
        Common::preventCrossPlatformLogon();
    }
    /**
     * 判断用户是否已登陆
     * @param CWebUser $user
     * @return bool
     */
    private function isLogin(CWebUser $user)
    {
        if ($user->isGuest) return false;

        //管理员登陆用户账号时member_manage为true
        //此程序段判断如果非用户和代理商打开界面，判断是否是管理员登陆，如果不是返回未登录
        $memberType = array(Common::USER_TYPE_MEMBER, Common::USER_TYPE_AGENT, Common::USER_TYPE_MSG);
        if (in_array($user->getState('type'), $memberType) == false) {
            if ($user->getState('member_manage') == false) {
                return false;
            }
        }

        if (is_null($user->getState('member_uid'))) {
            return false;
        }
        return true;
    }

    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='main';
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
}