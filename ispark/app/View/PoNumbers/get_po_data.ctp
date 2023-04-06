<?php

if(!empty($Data))
{
?>
<table class="table table-hover table-bordered" border="1">
    <tr>
        <th>Sr. No.</th>
        <th>Branch Name</th>
        <th>Cost Center</th>
        <th>Agreement Status</th>
        <th>Amount</th>
        <th>Balance</th>
        <th>Period From</th>
        <th>Period To</th>
        <th>PO Number</th>
        <th>Download</th>
        <th>Edit</th>
    </tr>
    <?php $i =1;
            foreach($Data as $d):
                echo "<tr>";
                   echo "<td>".$i++."</td>";
                   echo "<td>".$d['t2']['branch']."</td>";
                   echo "<td>".$d['t2']['cost_center']."</td>";
                   echo "<td>".$d['0']['Agri_status']."</td>";
                   echo "<td>".$d['t3']['amount']."</td>";
                   echo "<td>".$d['t3']['balAmount']."</td>";
                   echo "<td>".$d['t1']['periodTo']."</td>";
                   echo "<td>".$d['t1']['periodFrom']."</td>";
                   
                   echo "<td>";
                    $arr = explode(',',$d['t1']['poNumber']);
                    foreach($arr as $a){echo $a."<br>";}
                    echo "</td>";
                    echo "<td>";
                    if(!empty($d['t1']['image_upload']))
                    {
                        $arr = explode(',',$d['t1']['image_upload']);
                        foreach($arr as $a) 
                        {
                            echo '<a href="'.$this->webroot.'app/webroot/PO/'.$d['t1']['data_id'].'/'.$a.'">'.$a."</a><br>";
                        }
                    }
                    echo "</td>";
                    echo "<td>";
                    echo $this->Html->link('Edit',array('controller'=>'poNumbers','action'=>'edit','?'=>array('id'=>$d['t1']['data_id']),'full_base' => true));
                    echo "</td>";
                echo "</tr>";
            endforeach;
    ?>
</table>
    
<?php }