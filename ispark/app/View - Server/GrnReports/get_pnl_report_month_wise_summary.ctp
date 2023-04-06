<?php //print_r($SummaryMonth); exit; ?>
<table border="2">
    <tr>
        <th></th>
        <th></th>
         <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th>'.$month.'</th>';
                }
         ?>
        <th>Total</th>
    </tr>
    
    <tr>
      <th></th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    <tr>
        <th>Revenue</th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>
    </tr>
    <tr>
        <td>Unprocessed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.($SummaryData[$month]['Revenue']['Unprocessed']-$SummaryData[$month]['Revenue']['Processed']).'</td>';
                $RevenueUn += ($SummaryData[$month]['Revenue']['Unprocessed']-$SummaryData[$month]['Revenue']['Processed']);
            }
         ?>
        <td><?php echo $RevenueUn;?></td>
    </tr>
    <tr>
        <td>Processed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.$SummaryData[$month]['Revenue']['Processed'].'</td>';
                $RevenuePr += $SummaryData[$month]['Revenue']['Processed'];
            }
         ?>
        <td><?php echo $RevenuePr;?></td>
    </tr>
    
    <tr>
      <th></th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    <tr>
      <th></th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    
    <tr>
        <th>Salary</th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    <tr>
        <td>Unprocessed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.$SummaryData[$month]['Salary']['Unprocessed'].'</td>';
                $SalaryUn += $SummaryData[$month]['Salary']['Unprocessed'];
            }
         ?>
        <td><?php echo $SalaryUn;?></td>
    </tr>
    <tr>
        <td>Processed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.$SummaryData[$month]['Salary']['Processed'].'</td>';
                $SalaryPr += $SummaryData[$month]['Salary']['Processed'];
            }
         ?>
        <td><?php echo $SalaryPr;?></td>
    </tr>
    
    <tr>
      <th></th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    <tr>
      <th></th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    
    <tr>
        <th>Direct Expenses</th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>
    </tr>
    <tr>
        <td>Unprocessed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.$SummaryData[$month]['DirectExpense']['Unprocessed'].'</td>';
                $DirectUn += $SummaryData[$month]['DirectExpense']['Unprocessed'];
            }
         ?>
        <td><?php echo $DirectUn;?></td>
    </tr>
    <tr>
        <td>Processed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.$SummaryData[$month]['DirectExpense']['Processed'].'</td>';
                $DirectPr += $SummaryData[$month]['DirectExpense']['Processed'];
            }
         ?>
        <td><?php echo $DirectPr;?></td>
    </tr>
    
    <tr>
      <th></th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    <tr>
      <th></th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th>  
    </tr>
    
    <tr>
        <th>Indirect Expenses</th>
        <th></th>
        <?php
                foreach($SummaryMonth as $month=>$monthNew)
                {
                    echo '<th></th>';
                }
         ?>
        <th></th> 
    </tr>
    <tr>
        <td>Unprocessed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.$SummaryData[$month]['InDirectExpense']['Unprocessed'].'</td>';
                $InDirectUn += $SummaryData[$month]['InDirectExpense']['Unprocessed'];
            }
         ?>
        <td><?php echo $InDirectUn;?></td>
    </tr>
    <tr>
        <td>Processed</td>
        <td></td>
        <?php
            foreach($SummaryMonth as $month)
            {
                echo '<td>'.$SummaryData[$month]['InDirectExpense']['Processed'].'</td>';
                $InDirectPr += $SummaryData[$month]['InDirectExpense']['Processed'];
            }
         ?>
        <td><?php echo $InDirectPr;?></td>
    </tr>
    
</table>

<?php

        $fileName = "PNL_Summary_Report";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
exit;
?>   