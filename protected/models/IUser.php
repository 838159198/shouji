<?php
/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-8-28 上午9:26
 * Explain: 用户接口，凡是用户对象，都有下面的方法
 */
interface IUser
{
    /**
     * 验证密码是否有效
     * @param $password
     * @return bool
     */
    public function validatePassword($password);

    /**
     * 获得加密密码
     * @param $password
     * @return string
     */
    public function createPassword($password);
}