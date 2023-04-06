<?php
echo $this->Form->create('Provision');
?>




<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>View & Approve Provision </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
               <h4><?php echo $this->Session->flash(); ?></h4> 
		<table border="2" class = "table table-striped table-hover  responstable">
                    <thead>
                        <tr style="text-align:center">
                            <td ><b>Select</b></td>
                            
                            <td ><b>Branch</b></td> 
                            <td ><b>Finance Year</b></td>
                            <td ><b>Finance Month</b></td>
                            <td ><b>Cost Center</b></td>
                            <td ><b>Process</b></td>
                            <td ><b>Old Provision</b></td>
                            <td ><b>New Provision</b></td>
                            <td ><b>Difference</b></td>
                            <td ><b>Reason</b></td>
                            <td ><b>Request By</b></td>
                            <td ><b>Request Date</b></td>
                            
                        </tr>
                    </thead>
                   <tbody>
                    <?php $i=1; $Total=0;//print_r($ExpenseReport);
                    foreach($data as $dt)
                    {
                        echo "</tr>";
                            echo "<td>".'<input type="checkbox" name="check[]" value="'.$dt['pre']['id'].'" />'."</td>";
                            
                            echo "<td>".$dt['pre']['branch_name']."</td>";
                            echo "<td>".$dt['pre']['finance_year']."</td>";
                            echo "<td>".$dt['pre']['month']."</td>";
                            echo "<td>".$dt['pre']['cost_center']."</td>";
                            echo "<td>".$dt['pre']['process_name']."</td>";
                            echo "<td>".$dt['pre']['old_provision']."</td>";
                            echo "<td>".$dt['pre']['provision']."</td>";
                            echo "<td>".($dt['pre']['provision']-$dt['pre']['old_provision'])."</td>";
                            echo "<td>".$dt['pre']['remarks']."</td>";
                            echo "<td>".$dt['tu']['emp_name']."</td>";
                            echo "<td>".$dt['0']['entrydate']."</td>";
                            
                        echo "</tr>";
                    }

                    ?>
                    </tbody>
                </table>
               <div class="form-group">
                  <label class="col-sm-2 control-label"></label>
                  <div class="col-sm-2">
                      <button type="submit" name="Approve" value="Approve" class="btn btn-primary" >Approve</button>
                      <button type="submit" name="DisApprove" value="Reject" class="btn btn-primary" >Reject</button>
                  </div>
                  <div class="col-sm-2">
                      
                  </div>
               </div>
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>

 

