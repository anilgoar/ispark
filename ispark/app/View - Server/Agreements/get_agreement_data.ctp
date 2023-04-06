<?php

if(!empty($Data))
{
?>
<table class="table table-hover table-bordered" border="1">
    <tr>
        <th>Branch Name</th>
        <th>Cost Center</th>
        <th>Document</th>
        <th>Status</th>
        <th>Period From</th>
        <th>Period To</th>
        <th>Download</th>
        <th>Edit</th>
    </tr>
    <?php
            foreach($Data as $d):
                echo "<tr>";
                   echo "<td>".$d['t2']['branch']."</td>";
                   echo "<td>".$d['t2']['cost_center']."</td>";
                   echo "<td>".$d['t1']['document_type']."</td>";
                   echo "<td>".$d['0']['Agri_status']."</td>";
                   echo "<td>".$d['t1']['periodFrom']."</td>";
                   echo "<td>".$d['t1']['periodTo']."</td>";
                   echo "<td>";
                    $arr = explode(',',$d['t1']['image_upload']);
                    foreach($arr as $a)
                    {
                        echo '<a href="'.$this->webroot.'app/webroot/Agreement/'.$d['t1']['data_id'].'/'.$a.'">'.$a."</a><br>";
                    }
                    echo "</td>";
                    echo "<td>";
                    echo $this->Html->link('Edit',array('controller'=>'agreements','action'=>'edit','?'=>array('id'=>$d['t1']['data_id']),'full_base' => true));
                    echo "</td>";
                echo "</tr>";
            endforeach;
    ?>
</table>
    
<?php }