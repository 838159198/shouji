<?php
/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-4-26 上午10:07
 * Explain:权限相关
 */
class Auth
{
    const READ_FILE = false;
    public $file;

    function __construct()
    {
        $this->file = __DIR__ . '/AuthConfig.ini';
    }

    /**
     * 验证权限是否通过，true为通过
     * @param $currentAuth
     * @param $userAuth
     * @return bool
     */
    public static function check($currentAuth, $userAuth = null)
    {
/*        if (ENVIRONMENT === 'development') {
            return true;
        }*/
        if (Yii::app()->user->getState('type') !='Manage'){
            return false;
        }
        if (empty($currentAuth)) {
            return false;
        }

        if (is_null($userAuth)) {
            $userAuth = Yii::app()->user->getState('manage_auth');
        }

/*        if (!is_array($userAuth)) {
            return false;
        }*/
        if((Yii::app()->user->getState("manage_group")==1) || (in_array(strtolower($currentAuth),explode(",",strtolower(Yii::app()->user->getState("manage_auth"))))))
        {
            return 1;
        }
        else
        {
            return 0;
        }

        //return in_array($currentAuth, $userAuth);
    }

    /**
     * 根据文件夹地址返回所有文件名名称列表
     * @param $dir
     * @return array
     */
    private function getListFileName($dir)
    {
        $fileNameList = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $file = str_replace('.php', '', $file);
                        $fileNameList[] = $file;
                    }
                }
                closedir($dh);
            }
        }
        return $fileNameList;
    }

    /**
     * 根据文件夹地址，返回文件夹中所有权限列表。
     * 如果服务器开启eAccelerator，此方法无法读取doc中的name值。
     * 因此，需在本地运行此方法，再运行saveConfig()保存，并上传到服务器。
     *
     * @param $dir
     * @return array
     */
    public function getManageAuthList($dir)
    {
        $fileNameList = $this->getListFileName($dir);
        Yii::import('manage.controllers.*');
        $authList = array();
        foreach ($fileNameList as $file) {
            $cls = new ReflectionClass($file);
            $classTitle = $this->getTitle($cls->getDocComment());
            if (empty($classTitle)) {
                continue;
            }
            $controllerList = array(
                'title' => $classTitle,
                'name' => strtolower(str_replace('Controller', '', $cls->name)),
            );

            $methods = $cls->getMethods(ReflectionMethod::IS_PUBLIC);
            $actionList = array();
            foreach ($methods as $m) {
                if ($m->name == 'actions' || strstr($m->name, 'action') != $m->name) {
                    continue;
                }

                $actionTitle = $this->getTitle($m->getDocComment());
                if (empty($actionTitle)) {
                    continue;
                }

                $actionList[] = array(
                    'title' => $actionTitle,
                    'name' => strtolower(str_replace('action', '', $m->name))
                );
            }

            $controllerList['actions'] = $actionList;
            $authList[] = $controllerList;
        }
        return $authList;
    }

    /**
     * 保存权限
     * @param $authList
     */
    public function saveConfig($authList)
    {
        file_put_contents($this->file, serialize($authList));
    }

    /**
     * 获取权限设置
     * @return array
     */
    public function getConfig()
    {
        $authList = array();

        if (self::READ_FILE) {
            if (file_exists($this->file)) {
                $handler = fopen($this->file, "r");
                $authList = unserialize(fread($handler, filesize($this->file)));
            }
        } else {
            $dir = dirname(__FILE__) . '/../controllers';
            $authList = $this->getManageAuthList($dir);
        }
        return $authList;
    }


    /**
     * 根据读取的doc获取name
     * @param $str
     * @return string
     */
    private function getTitle($str)
    {
        if (preg_match('/@name.*/', $str, $arr)) {
            return trim(str_replace('@name', '', $arr[0]));
        } else {
            return '';
        }
    }

    /**
     * 获得权限列表，由checkBoxList调用
     * @return array
     */
    public function getAuthList()
    {
        $config = $this->getConfig();
        $list = array();
        foreach ($config as $v) {
            $cate['title'] = $v['title'];
            $cateName = 'manage.' . $v['name'];

            $item = array();
            foreach ($v['actions'] as $i) {
                $key = $cateName . '.' . $i['name'];
                $item[$key] = $i['title'];
            }
            $cate['items'] = $item;

            $list[$cateName] = $cate;
        }
        return $list;
    }


    /**
     * 根据Action对象获取该对象对应的权限值
     * @param CAction $action
     * @return string
     */
    public static function getAuthByAction($action)
    {
        $auth = '';
        $auth .= $action->getController()->getModule()->getId() . '.';
        $auth .= $action->getController()->getId() . '.';
        $auth .= $action->getId();
        return strtolower($auth);
    }

    /**
     * 根据权限判断返回的文字
     * @param string $auth 权限
     * @param string $text 文字
     * @param string $auto 权限不通过返回的文字
     * @return string
     */
    public static function getText($auth, $text, $auto = '')
    {
        return self::check($auth) ? $text : $auto;
    }


}