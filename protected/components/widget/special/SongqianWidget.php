<?php
class SongqianWidget extends CWidget
{
    public $num = 1;
    public $order = "id";
    public function init()
    {
        /*$model = new SpecialSongqianLog();
        $criteria = new CDbCriteria();
        $criteria -> limit = $this->num;
        $criteria -> order = "{$this->order} DESC";
        $data = $model -> findAll($criteria);
        $this->render("songqian",array("data"=>$data));*/
    }

    public function run()
    {
        $nameArray = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",1,2,3,4,5,6,7,8,9);
        $moneyArray = array("运气好像一般只抽中了5元现金红包",
            "幸运的抽中了10元现金红包",
            "幸运的抽中了15元现金红包",
            "幸运的抽中了20元现金红包",
            "幸运的抽中了25元现金红包",
            "幸运的抽中了30元现金红包",
            "幸运的抽中了35元现金红包",
            "幸运的抽中了40元现金红包",
            "幸运的抽中了45元现金红包",
            "幸运的抽中了50元现金红包",
            "幸运女神护体 抽中了100元现金红包",
            "999");
        $data = "";
        for($a=0;$a<30;$a++)
        {
            $name="";
            for($i=0;$i<3;$i++)
            {
                $name_key = rand(0,25);
                $name .= $nameArray[$name_key];
            }
            $money_key = rand(0,10);
            $money = $moneyArray[$money_key];
            //echo $name."***";
            //echo "<br>";
            //echo $money."元";
            $data .= "<dl><dt>{$name}***</dt><dd>{$money}</dd></dl>";
        }
        echo $data;

    }
}