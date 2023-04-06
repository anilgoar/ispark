<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		
	</div>
</div>
<?php echo $this->Form->create('BillGenerations',array('class'=>'form-horizontal')); ?>
<div class="row">
    <div class="col-xs-12">
        
<div class="box">
    <div class="box-header">
        <div class="box-name">
            <i class="fa fa-edit"></i>
            <span>P&L Details Update</span>
        </div>
				
        <div class="no-move"></div>
    </div>
<?php //print_r($branch_master); ?>

<br/>
<?php echo $this->Session->flash(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><b style="font-size:14px">Branch</b></label>	
                    <div class="col-sm-4">
                        <?php	echo $this->Form->input('branch', array('label'=>false,'class'=>'form-control',
                        'options'=>$branch_master,'required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><b style="font-size:14px">Finance Year</b></label>	
                    <div class="col-sm-2">
                        <?php	echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control',
                        'options'=>$financeYearArr,'required'=>true)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><b style="font-size:14px">Finance Month</b></label>	
                    <div class="col-sm-2">
                        <?php	echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','value'=>$mnt,'empty'=>'Select',
                        'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),'required'=>true)); ?>
                    </div>
                </div>				
	
</div>
        
    </div>
</div
<div class="row">
    <div class="col-xs-12">
    <div class="box">
        <div class="box-content no-padding">
            <table class="table">
                <thead>
                    <tr>
                        <th style="text-align: center;">Revenue Provision</th>
                        <th style="text-align: center;">Salary Provision</th>
                        <th style="text-align: center;">IDC Provision</th>
                        <th style="text-align: center;">Payment Updation</th>
                        <th style="text-align: center;">Last Updated Date</th>
                    </tr>
                </thead>
                <tbody>
                <td style="text-align: center;">
                    <input type="checkbox" name="Revenue_Provision" id="Revenue_Provision" value="1" <?php if($record['BranchBillingDetUpd']['Revenue_Provision']=='1') echo 'checked'; ?> />
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="Salary_Provision" id="Revenue_Provision" value="1" <?php if($record['BranchBillingDetUpd']['Salary_Provision']=='1') echo 'checked'; ?> />
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="IDC_Provision" id="Revenue_Provision" value="1" <?php if($record['BranchBillingDetUpd']['IDC_Provision']=='1') echo 'checked'; ?> />
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="Payment_Updation" id="Revenue_Provision" value="1" <?php if($record['BranchBillingDetUpd']['Payment_Updation']=='1') echo 'checked'; ?> />
                </td>
                <th><span id="created_at"></span></th>
                </tbody>
            </table>
            
            <div class="form-group">
                <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-2">
                        <button type="submit" name="button" value="Save" class="btn btn-primary">Update</button>
                        <a href="/ispark/FinanceReports" class="btn btn-primary btn-label-left">Back</a> 
                    </div>
                </div>
            <br/>
            <br/>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>