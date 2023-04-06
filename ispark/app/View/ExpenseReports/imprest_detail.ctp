<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
function getImprestManager()
{
    var BranchId=$("#BranchId").val();
  $.post("get_imprest_manager",
            {
             BranchId: BranchId,
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#ImprestManagerId").empty();
                $("#ImprestManagerId").html(text);
                
            });  
}
</script>

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
                    
                    <span>Imprest Detail</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-group has-success has-feedback">
                 <?php echo $this->Form->create('Expense',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Branch Master</label>
                        <div class="col-sm-4">
                        <?php	
                            echo $this->Form->input('BranchId', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','Id'=>'BranchId','onChange'=>'getImprestManager()','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-2 control-label">Date From</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('DateFrom', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'DateFrom','onClick'=>"displayDatePicker('data[Expense][DateFrom]')",'required'=>true));
                                ?>
                        </div> 
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Imprest Manager</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('ImprestManagerId', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Select Imprest Manager','id'=>'ImprestManagerId','required'=>true));
                                ?>
                        </div>
                        <label class="col-sm-2 control-label">Date To</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('DateTo', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'To','id'=>'DateTo','onClick'=>"displayDatePicker('data[Expense][DateTo]')",'required'=>true));
                                ?>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                            <input type="submit" name="Show" value="Show" onclick="return imrest_detail_validate('Show')" class="btn btn-primary" />
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" name="Export" value="Export" onclick="return imrest_detail_validate('Export')" class="btn btn-primary" />
                        </div>
                        <div class="col-sm-1">
                           <a href="/ispark/FinanceReports" class="btn btn-primary btn-label-left">Back</a> 
                        </div>
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                    </div>
		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content" id="data">
                <table border="2">
                    <tr><th>Branch</th><td><?php echo $Branch; ?></td></tr>
                    <tr><th>Imprest Manager</th><td><?php echo $ImprestManager; ?></td></tr>
                    <tr><th>Opening Balance</th><td><?php echo $opening; ?></td></tr>
                    <tr><th>Closing Balance</th><td><?php echo $closing; ?></td></tr>
                </table>
                <br><br><br>
                <table border="2" class="table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Date</th>
                            <th>GRN</th>
                            <th>Exp. Head</th>
                            <th>Exp. SubHead</th>
                            <th>INFLOW</th>
                            <th>OUTFLOW</th>
                            <th>Balance</th>
                            <th>Mode</th>
                            <th>Chq No</th>
                            <th>Bank</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $TotalInflow = 0; $TotalOutflow=0;//print_r($ExpenseReport); exit;
                                foreach($data as $imp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$imp['date']."</td>";
                                        echo "<td>".$imp['grn']."</td>";
                                        echo "<td>".$imp['head']."</td>";
                                        echo "<td>".$imp['subhead']."</td>";
                                        echo "<td>".(!empty($imp['inflow'])?$imp['inflow']:0)."</td>";
                                        echo "<td>".(!empty($imp['outflow'])?$imp['outflow']:0)."</td>";
                                        echo "<td>".(!empty($imp['balance'])?$imp['balance']:0)."</td>";
                                        echo "<td>".$imp['PaymentMode']."</td>";
                                        echo "<td>".$imp['PaymentNo']."</td>";
                                        echo "<td>".$imp['BankId']."</td>";
                                        echo "<td>".$imp['remarks']."</td>";
                                    echo "</tr>";
                                    $TotalInflow +=  !empty($imp['inflow'])?$imp['inflow']:0;
                                    $TotalOutflow += !empty($imp['outflow'])?$imp['outflow']:0;
                                }
                                echo '<tr><td colspan="4"></td><td>Total</td><td>'.$TotalInflow.'</td><td>'.$TotalOutflow.'</td><td colspan="5"></td></tr>';
                        ?>
                    </tbody>
                </table>    
            
		

		
					
		
            <div class="clearfix"></div>
            <div class="form-group">
                    
            </div>
            </div>
        </div>
    </div>
</div>

