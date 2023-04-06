<?php $last_date = explode("-",$date_last_det); $last_day = $last_date[0]; ?>
<table border="2" >
    <tr>
        <th colspan="5" style="text-align: center;">Commitment Revenue - <input type="text" id="commitment" value="0" readonly="" /></th>
    </tr>    
    <tr>
        <th>Header</th>
        <th>Cost Center</th>
        <th>Forecast</th>
        <th>MTD</th>
        
        <?php
            for($n=1; $n<=$last_day; $n++)
            {
                echo "<th>";
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
            $forecast = round($mtd*($end_date/$calculation_days));
            echo "<tr><th>".$parts['parts']['PartName']."</th>";
            echo "<th>";
                echo '<input type="text" id="cost'.$PartId.'" name="cost['.$PartId.']" value="" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" />';
            echo "</th>";
            echo "<th>";
                echo '<input type="text" id="forcast'.$PartId.'" name="forcast['.$PartId.']" value="'.$forecast.'" onKeyPress="return checkNumber(this.value,event)" readonly=""  />';
            echo "</th>";
            echo "<th>";
                echo '<input type="text" id="mtd'.$PartId.'" name="mtd['.$PartId.']" value="" onKeyPress="return checkNumber(this.value,event)" readonly=""  />';
            echo "</th>";
            
            for($n=1; $n<=$last_day; $n++)
            {
                echo "<th>";
                echo '<input type="text" id="date'.$n.'_'.$PartId.'"  value="0" onKeyPress="return checkNumber(this.value,event)" readonly="" />';
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
            echo "<tr><th>".$parts['parts']['PartName']." Rate</th>";
            echo "<th>";
                echo '<input type="text" id="costRate'.$PartId.'" name="costRate['.$PartId.']" value="" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" />';
            echo "</th>";
            echo "<th>";

            echo "</th>";
            echo "<th>";
                echo '<input type="text" id="mtdRate'.$PartId.'" name="mtdRate['.$PartId.']" value="'.$mtd.'" onKeyPress="return checkNumber(this.value,event)" readonly="" />';
            echo "</th>";

            
            for($n=1; $n<=$last_day; $n++)
            {
                echo "<th>";
                echo '<input type="text" id="dateRate'.$n.'_'.$PartId.'"  value="0" onKeyPress="return checkNumber(this.value,event)" readonly="" />';
                echo "</th>";
            }

            echo "</tr>";
        }
?>
    <tr>
        <th>Amount</th>
        <th><input type="text" id="costTotal" name="costTotal" value="" readonly="" /></th>
        <th><input type="text" id="ForecastTotal" name="ForecastTotal" value="0" readonly="" /></th>
        <th><input type="text" id="MtdTotal" name="MtdTotal" value="0" readonly="" /></th>

        <?php

            for($n=1; $n<=$last_day; $n++)
                {
                    echo "<th>";
                ?>   
                    <input type="text" id="DateTotal<?php echo $n; ?>"  value="0" readonly="" />
                <?php    echo "</th>";
                }

                foreach($part_array as $parts)
                {?>
                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" />
          <?php }?>
                <?php
                foreach($rate_array as $parts)
                {?>
                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" />
          <?php }?>
                    <?php
                foreach($not_added_arr as $parts)
                {?>
                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" />
          <?php }?>
    </tr>
</table>
                
<input type="hidden" id="mtd_old" name="mtd_old" value="0" />
<input type="hidden" id="id_arr" name="id_arr" value="<?php echo implode(",",$part_array); ?>" />
<input type="hidden" id="not_added_arr" name="not_added_arr" value="<?php echo implode(",",$not_added_arr); ?>" />
<input type="hidden" id="id_arr_rate" name="id_arr_rate" value="<?php echo implode(",",$rate_array); ?>" />
<input type="hidden" id="calculation_days" name="calculation_days" value="<?php echo round($end_date/$calculation_days,2); ?>" />
<input type="hidden" id="mnt_arr" name="mnt_arr" value="<?php echo $m; ?>" />
<input type="hidden" id="calculation_days" name="calculation_days" value="<?php echo round($end_date/$calculation_days,2); ?>" />