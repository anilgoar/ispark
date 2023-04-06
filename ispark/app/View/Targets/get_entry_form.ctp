<style>
.tbl input{text-align: center !important;}                    
</style>
<?php $last_date = explode("-",$date_last_det); $last_day = $last_date[0]; ?>
<table border="2" class="tbl" >
    
    <tr>
        <th style="text-align:center; width: 80px;">Header</th>
        <th style="width: 80px;text-align: center">Cost Center</th>
        <th style="width: 80px;text-align: center">Forecast</th>
        <th style="width: 80px;text-align: center">MTD</th>
        
        <?php
            for($n=1; $n<=$last_day; $n++)
            {
                echo '<th style="width: 80px;text-align:center">';
                echo $n.'-'.$last_date[1];
                echo "</th>";
            }
        ?>
    </tr>
                
<?php
        $data_rate = array();
        $start_date = 1;
        $today_date =  date("d");
        $end_date =  date("t");

        $calculation_days = $last_day;


        foreach($ParticularDetails as $parts)
        {
            $PartId = $parts['parts']['PartId'];
            $mtd = 0;
            $forecast = round($mtd*($end_date));
            echo "<tr>"; 
            echo "<th>".str_replace(" ","&nbsp;",$parts['parts']['PartName'])."</th>";
            echo '<th style="width: 80px;">';
                echo '<input type="text" id="cost'.$PartId.'" name="cost['.$PartId.']" value="'.$cost_data['cost'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" style="width: 80px;" />';
            echo "</th>";
            echo "<th>";
                echo '<input type="text" id="forcast'.$PartId.'" name="forcast['.$PartId.']" value="'.$cost_data['forecast'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" readonly="" style="width: 80px;"  />';
            echo "</th>";
            echo "<th>";
                echo '<input type="text" id="mtd'.$PartId.'" name="mtd['.$PartId.']" value="'.$cost_data['HeadTotal'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" readonly="" style="width: 80px;" />';
            echo "</th>";
            
            for($n=1; $n<=$last_day; $n++)
            {
                echo "<th>";
                if($PartId=='10')
                {
                    echo '<input type="text" id="date'.$n.'_'.$PartId.'" name="date['.$n.'_'.$PartId.']" value="'.$cost_data['date'.(int)$n.'_'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" readonly="" style="width: 80px;" />';
                }
                else
                {
                    echo '<input type="text" id="date'.$n.'_'.$PartId.'" name="date['.$n.'_'.$PartId.']" value="'.$cost_data['date'.(int)$n.'_'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" style="width: 80px;" />';
                }
                echo "</th>";
            }
            echo "</tr>";

            if(!empty($parts['parts']['AddRequired']) && empty($parts['parts']['RateRequired']))
            {
                $part_array[] = $PartId;
            }
            else if(!empty($parts['parts']['AddRequired']))
            {
                $data_rate[] = $parts;
                $rate_array[] = $PartId;
            }
            else
            {
                $not_added_arr[] =  $PartId;
            }
        }

        foreach($data_rate as $parts)
        {
            $PartId = $parts['parts']['PartId'];
            echo "<tr><th>".str_replace(" ","&nbsp;",$parts['parts']['PartName'])." Rate</th>";
            echo "<th>";
                echo '<input type="text" id="costRate'.$PartId.'" name="costRate['.$PartId.']" value="'.$cost_data['costRate'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" style="width: 80px;" />';
            echo "</th>";
            echo "<th>";

            echo "</th>";
            echo "<th>";
                echo '<input type="hidden" id="mtdRate'.$PartId.'" name="mtdRate['.$PartId.']" value="'.$cost_data['HeadTotalRate'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" readonly="" onblur="get_total_amt_dash()" style="width: 80px;" />';
            echo "</th>";

            
            for($n=1; $n<=$last_day; $n++)
            {
                echo "<th>";
                echo '<input type="text" id="dateRate'.$n.'_'.$PartId.'" name="dateRate['.$n.'_'.$PartId.']" value="'.$cost_data['dateRate'.$n.'_'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" style="width: 80px;" />';
                echo "</th>";
            }

            echo "</tr>";
        }
?>
    <tr>
        <th>Amount</th>
        <th><input type="text" id="costTotal" name="costTotal" value="<?php echo $cost_data['CostTotal']; ?>" readonly="" style="width: 80px;" /></th>
        <th><input type="text" id="ForecastTotal" name="ForecastTotal" value="<?php echo $cost_data['mtd_old']; ?>" readonly="" style="width: 80px;" /></th>
        <th><input type="text" id="MtdTotal" name="MtdTotal" value="<?php echo $cost_data['mtd_old']; ?>" readonly="" style="width: 80px;" /></th>

        <?php

            for($n=1; $n<=$last_day; $n++)
                {
                    echo "<th>";
                ?>   
                    <input type="text" id="DateTotal<?php echo $n; ?>"  value="<?php echo $cost_data['DateTotal'.$n]; ?>" readonly="" style="width: 80px;" />
                <?php    echo "</th>";
                }

                foreach($part_array as $parts)
                {?>
                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" style="width: 80px;" />
          <?php }?>
                <?php
                foreach($rate_array as $parts)
                {?>
                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" style="width: 80px;" />
          <?php }?>
                    <?php
                foreach($not_added_arr as $parts)
                {?>
                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" style="width: 80px;" />
          <?php }?>
    </tr>
</table>
                
<input type="hidden" id="mtd_old" name="mtd_old" value="0" />
<input type="hidden" id="id_arr" name="id_arr" value="<?php echo implode(",",$part_array); ?>" />
<input type="hidden" id="not_added_arr" name="not_added_arr" value="<?php echo implode(",",$not_added_arr); ?>" />
<input type="hidden" id="id_arr_rate" name="id_arr_rate" value="<?php echo implode(",",$rate_array); ?>" />
<input type="hidden" id="calculation_days" name="calculation_days" value="1" />
<input type="hidden" id="mnt_arr" name="mnt_arr" value="<?php echo $last_day; ?>" />
