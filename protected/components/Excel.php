<?php
/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-5-13 下午5:47
 * Explain: Excel相关
 */
class Excel
{
    private $_phpExcel;

    function __construct()
    {
       Yii::import('ext.PHPExcel');
        $this->_phpExcel = new PHPExcel();
    }

    /**
     * @return \PHPExcel
     */
    public function getPhpExcel()
    {
        return $this->_phpExcel;
    }


    /**
     * 根据文件名获取PHPExcel对象
     * @param $file
     * @return PHPExcel
     */
    public function getByFile($file)
    {
        $reader = PHPExcel_IOFactory::createReader('Excel5');
        $excel = $reader->load($file);
        return $excel;
    }

    /**
     * PHPExcel对象转换为数组
     * @param PHPExcel $excel
     * @return array
     */
    public function excel2Array(PHPExcel $excel)
    {
        if (empty($excel)) return array();
        return $excel->getSheet(0)->toArray();
    }

    /**
     * 生成Excel
     * @param string $name 名称
     * @param string[] $titles 标题组
     * @param string[] $keys 数据key
     * @param array $datas 数据
     * @return PHPExcel
     */
    public function createExcel($name, $titles, $keys, $datas)
    {
        $excel = $this->_phpExcel;
        $excel->getDefaultStyle()->getFont()->setSize(12);
        $excel->getActiveSheet()->setTitle($name);

        //设置标题
        $fix = 'A';
        foreach ($titles as $title) {
            $excel->getActiveSheet()->setCellValue($fix . '1', $title);
            $fix++;
        }

        //填充内容
        $line = 1;
        foreach ($datas as $data) {
            $line++;
            $dataFix = 'A';
            foreach ($keys as $key) {
                $row = $dataFix . $line;
                $val = isset($data[$key]) ? $data[$key] : '';
//                if (is_numeric($val)) {
//                    $excel->getActiveSheet()->getStyle($row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
//                }
                $excel->getActiveSheet()->setCellValue($row, $val);
                $dataFix++;
            }
        }
        return $excel;
    }

    /**
     * 下载
     * @param PHPExcel $excel
     * @param string $name
     */
    public function download(PHPExcel $excel, $name = '')
    {
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $filename = empty($name) ? urlencode(uniqid('sutuiapp-')) : urlencode($name);
        header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}