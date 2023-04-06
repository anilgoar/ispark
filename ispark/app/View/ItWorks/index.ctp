<script>
<?php if($LastLog !=""){ ?>
setInterval(function(){DashboardLog('<?php echo $LastLog;?>'); }, 3000);
<?php }?>
function DashboardLog(Id){
    $.post("<?php echo $this->webroot;?>ItWorks/UserLog",{Id:Id},function(data){
    });
}
</script>
<style>
* {
  box-sizing: border-box;
}

/* Create four equal columns that floats next to each other */
.column {
  float: left;
  width: 100%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
<h2 align="center">Last Updated Date : <font color="red"><?php echo $last_date; ?></font></h2>
<div class="row" style="overflow: auto;">
  <div class="column" style="background-color:#fffffd;">
    
    <table class="table table-striped table-hover responsetable">
        
        <tr>
            <th>S. No.</th>
			<th>Branch</th>
			<th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>TAT</th>
			<th>Revised</th>
            <th>Remarks</th>
			<th>Spoc</th>
        </tr>
    <?php $i =1; 
        
           foreach($record_arr as $record)
           // for($i=0;$i<$cnt;$i++)
            {
                //$record = $record_arr[$i];
                if(strtolower(trim($record['Status']))=='in progress')
                {
                    $font_color="orange";
                }
                else if(strtolower(trim($record['Status']))=='over')
                {
                    $font_color="green";
                }
                else
                {
                    $font_color="red";
                }
                
                echo '<tr>';
                    echo '<td>';
                    
                    if(!empty($record)) echo "".($i++)."";
                    
                    echo '</td>';
                    echo '<td>'.$record['Branch'].'</td>';
					echo '<td>'.$record['Category'].'</td>';
					echo '<td>'.$record['Description'].'</td>';
                    echo '<td>'.'<font color="'.$font_color.'">'.$record['Status'].'</font></td>';
                    echo '<td>'.$record['TAT'].'</td>';
					echo '<td>'.$record['Revised'].'</td>';
                    echo '<td>'.$record['Remarks'].'</td>';
					echo '<td>'.$record['Spoc'].'</td>';
                    echo '<td style="border:0px;background: #ffffff">&nbsp;&nbsp;&nbsp;</td>';
                    
                    
                    
                echo '</tr>';
                
                
                
                
            }
    ?>
        </table>
  </div>
  
</div>
