<style>
.weui-panel:after {
    content: " ";
    position: absolute;
    left: 0;
    bottom: 0;
    right: 0;
    height: 1px;
     border-bottom: 0px solid #E5E5E5; 
    color: #E5E5E5;
    -webkit-transform-origin: 0 100%;
    transform-origin: 0 100%;
    -webkit-transform: scaleY(0.5);
    transform: scaleY(0.5);
    }

.weui-grid{
    position: relative;
    float: left;
    padding: 20px 10px;
    width: 25%;
    box-sizing: border-box;
}
table, td, th
  {
  border:1px solid #EBEBEB;
  }
</style>

<div class="weui-panel" style="margin-top:0">
    <div class="weui-panel__hd"><?=date('Y-m-d',strtotime($date))?>日收益明细</div>
    <div class="weui-panel__bd">
        <div class="weui-media-box weui-media-box_small-appmsg">
            
    <div class="weui-grids">
    <table style="width:100%;border-collapse:collapse;text-align: center;font-size:14px">
        <tr style="background-color: #F2F2F2;height:50px">
            <td>业务名称</td>
            <td>单价</td>
            <td>判定个数</td>
            <td>业务总收益</td>
        </tr>
        <?php
                if(!empty($data)){
                    // print_r($data);exit;
                    foreach($data[0] as $k=>$v) {
                        $prinfo = Product::model()->find('pathname=:pathname',array(':pathname'=>$k));
                        if(empty($prinfo)){
                            continue;
                        }
                        else{

                            echo '<tr style="height:30px"><td>
           
                                   '.$prinfo['name'].'
                                </td>
                                <td>
                         
                                    '.$prinfo["quote"].'元/个
                                </td>
                                <td>
                                    
                                    '.$data[0][$k]/$prinfo["quote"].'个
                                </td>
                                <td>
                                    
                                    '.$data[0][$k].'元
                                </td></tr>';
                            

                        }

                    } 
                }

                
                ?>

    </table>
        
        
    
            
                
                <!-- 循环结束 -->
        </div>  
        </div>
    </div>
</div>