<?php
/**
 * MSG：保留接口，代码不做更改
 * Explain: 用户收益相关接口
 */
interface Iincome
{
    /**
     * 计算单个项目的收益值
     * @param MemberInfo $member
     * @param float $num 数量（金额或点击数）
     * @return float
     */
    public function compute(MemberInfo $member, $num);

    /**
     * 使用数组添加数据（上传Excel）
     * @param $array
     * @return int 导入数据数量
     * @throws CHttpException
     */
    public function createByArray($array);

    /**
     * 使用Xml添加数据
     * @param DOMDocument $dom
     * @param string $key 如果key有值，添加数据只针对该key
     * @return int 导入数据数量
     * @throws CHttpException
     */
    public function createByXml(DOMDocument $dom, $key = '');

    /**
     * 使用文本文件添加数据
     * @param string $txt
     * @param string $key
     * @return int 导入数据数量
     * @throws CHttpException
     */
    public function createByText($txt, $key = '');

    /**
     * 自己计算添加数据
     * @param string $key
     * @return int 导入数据数量
     * @throws CHttpException
     */
    public function createBySelf($key = '');

    /**
     * 获取xml数据的url地址
     * @param long $time
     * @return string
     */
    public function getApiUrl($time = null);

    /**
     * 使用数组向数据库批量添加数据
     * @param $type
     * @param $array
     */
    public function insertByArray($type, $array);

    /**
     * 如果封号，该ID封号日期之后的数据全部失效
     * @param $mrid
     * @param $createtime
     * @return mixed
     */
    public function closeIncome($mrid, $createtime);
}