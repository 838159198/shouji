<?php
/**
 * This is the model class for table "{{role}}".
 *
 * The followings are the available columns in table '{{role}}':
 * @property string $id
 * @property string $name
 * @property string $fid
 *
 * @property Role $f
 * 用户角色
 */
class Role extends CActiveRecord
{
	
	/** 管理员*/
	const ROLE_ADMIN 		= 1;
	/** 经理 */
	const ROLE_AMALDAR 		= 2;
	/** 客服主管 */
	const SUPERVISOR 		= 3;
	/** 见习主管 */
	const PRACTICE_VISOR 	= 4;
	
	/** 高级客服*/
	const ADVANCED_STAFF 	= 5;
	
	/** 客服 */
	const SUPPORT_STAFF 	= 6;
	/** 见习客服*/
	const PRACTICE_STAFF 	= 7;


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{role}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, fid', 'required'),
            array('name', 'length', 'max' => 20),
            array('fid', 'length', 'max' => 11),
            array('fid', 'authenticate'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, fid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * 过滤器
     * @param $attribute
     * @param $params
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) return;

        if ($this->id == $this->fid) {
            $this->addError('fid', '角色上级不能是自己');
            return;
        }

        if (empty($this->id)) return;

        $roleList = $this->findAll('fid=:fid', array(':fid' => $this->id));
        foreach ($roleList as $role) {
            /** @var $role Role */
            if ($role->id == $this->fid) {
                $this->addError('fid', '角色不能循环添加');
                return;
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'f' => array(self::BELONGS_TO, 'Role', 'fid')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '角色名称',
            'fid' => '上级角色',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('fid', $this->fid, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 获取角色列表
     * @return array
     */
    public function getList()
    {
        $c = new CDbCriteria();
        $c->with = 'f';
        return $this->findAll($c);
    }

    /**
     * @return CActiveDataProvider
     */
    public function getDataProvider()
    {
        $c = new CDbCriteria();
        $c->with = 'f';
        return new CActiveDataProvider($this, array(
            'criteria' => $c
        ));
    }
    public function  getBaseWageByRole($role){
        $sql = 'SELECT base_wage FROM app_role WHERE id = \''.$role.'\' ';
        $res =  Yii::app()->db->createCommand($sql)->queryAll();
        return $res[0]['base_wage'];
    }
    /**
     * 获取角色列表共DownList控件使用
     * @return array
     */
    public function getDownList()
    {
        /* @var $result Role[] */
        $result = $this->findAll();
        $list = array(0 => '== 请选择 ==');
        foreach ($result as $role) {
            $list[$role->id] = $role->name;
        }
        return $list;
    }

    /**
     * 根据角色ID获取该角色所有子角色列表
     * @param integer $rid
     * @param array $roleList
     * @return array
     */
    public function getChildRoleList($rid, $roleList)
    {
        $childList = array();
        foreach ($roleList as $role) {
            /** @var $role Role */
            if ($role->fid == $rid) {
                $_id = $role['id'];
                $childList[$_id] = $role;
                $childList += $this->getChildRoleList($_id, $roleList);
            }
        }
        return $childList;
    }

    /**
     * 根据角色ID，获取改角色下级角色的用户列表
     * @param $rid role
     * @return array
     */
    public function getChildManageList($rid)
    {
        $roleList = $this->getChildRoleList($rid, $this->getList());
        $manageList = Manage::model()->getByRole(array_keys($roleList));
        return $manageList;
    }

    public function getRoleNameByRole($role){
        $role = Role::model()->findByPk($role);
        return $role->name;
    }

}