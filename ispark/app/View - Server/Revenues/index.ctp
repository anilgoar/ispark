<?php //print_r($branch_master); ?>
<script>
function get_revenue_branch(month)
{
    var company = document.getElementById("AddcompanyCompanyName").value;
    var branch = document.getElementById("AddcompanyBranchName").value;
    var finance = document.getElementById("AddcompanyFinanceYear").value;
    
    
    
    $.post("Revenues/get_revenue_cost",
    {
        company: company,
        branch: branch,
        finance:finance,
        month: month
    },
    function(data, status)
    {
        
        document.getElementById("cost_center").innerHTML = data; 
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
        <div id="social" class="pull-right">
            <a href="#"><i class="fa fa-google-plus"></i></a>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
	</div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Revenue Send To BPS</span>
                </div>
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
		<a class="close-link"><i class="fa fa-times"></i></a>
            </div>
            <div class="no-move"></div>
			</div>
			<div class="box-content">
                            <?php echo $this->Session->flash(); ?>
                                <?php echo $this->Form->create(array('controller'=>'Revenues','class'=>'form-horizontal')); ?>
                                            	<div class="form-group has-info has-feedback">
							<label class="col-sm-3 control-label"><b style="font-size:14px"> Select Company </b></label>
                                                        <div class="col-sm-3">
                                                        <?php	echo $this->Form->input('company_name', array('label'=>false,'options'=>array('All'=>'All','Mas Callnet India Pvt Ltd'=>'Mas Callnet India Pvt Ltd','IDC'=>'ISPARK Dataconnect Pvt. Ltd.'),'empty'=>'Select Company','required'=>false,'class'=>'form-control')); ?>
                                                        </div>    
						<label class="col-sm-2 control-label"><b style="font-size:14px">
							 Select Branch </b></label>
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('branch_name', array('label'=>false,'options'=>$branch_name,'empty'=>'Select Branch','required'=>false,'class'=>'form-control','onChange'=>'download(this)')); ?>
						</div>
						</div>
                                                <div class="form-group has-info has-feedback">
						<label class="col-sm-3 control-label"><b style="font-size:14px"> Select Finance year </b></label>
                                                <div class="col-sm-3">
                                        	<?php	echo $this->Form->input('finance_year', array('label'=>false,'options'=>array('2014-15'=>'2014-15','2015-16'=>'2015-16','2016-17'=>'2016-17'),'empty'=>'Select Finance Year','class'=>'form-control')); ?>
						</div>
                                                
						<label class="col-sm-2 control-label"><b style="font-size:14px"> Month </b></label>
                                                <div class="col-sm-3">
                                        	<?php	$month = array('All'=>'All','Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                                echo $this->Form->input('month', array('label'=>false,'options'=>$month,'empty'=>'Select','class'=>'form-control','onChange'=>'get_revenue_branch(this.value)')); ?>
						</div>
                                                </div>
                                                
                                                <div class="form-group has-info has-feedback">
						<label class="col-sm-3 control-label"><b style="font-size:14px"> Select Cost Center </b></label>
                                                <div class="col-sm-3"><div id="cost_center">
                                        	<?php	echo $this->Form->input('cost_center', array('label'=>false,'options'=>'','empty'=>'Select Cost Center','class'=>'form-control')); ?>
						</div>
                                                </div>
						
                                                </div>
                                                    <div class="form-group has-info has-feedback">
                                                        <label class="col-sm-3 control-label"><b style="font-size:14px">  </b></label><div class="col-sm-3">	<input type="submit" class="btn btn-info" value="search"></div>
                                                    </div>
                                            <?php echo $this->Form->end();?>
                                    </div>
					<div id="mm">
<?php                                            
if(!empty($data))
{
?>
                                            <form method="Post" class="form-horizontal" action="Revenues/send_bps" >                                    
                                            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active','','info'); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>Branch Name</th>
							<th>Month</th>
							<th>Cost Center</th>
							<th>Provision</th>
							<th>Processed</th>
                                                        <th>Un - Processed</th>
							<th colspan="2">Action</th>
						</tr>
						<?php if(isset($data)){ $i = 0; foreach ($data as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>" align="center">
							<?php $id= $post['pm']['Id']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['pm']['branch_name']; ?></td>
							<td><?php echo $post['pm']['month']; ?></td>
							<td><?php echo $post['pm']['cost_center']; ?></td>
							<td><?php echo $post['pm']['provision']; ?></td>
							<td><?php echo $post['tab']['raised']; ?></td>
							<td><?php echo $post['pm']['provision_balance']; ?></td>
                                                        <td><input type="checkbox" name="check[]" value="<?php echo $id.'#'.($post['tab']['raised']+$post['pm']['provision_balance']);?>"></td>
							
						</tr>
                                                <?php endforeach; } ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>
                                            <input type="submit" value="Send" class="btn btn-info">    
                           </form> 
<?php
}
?>                                            
                                    </div>
                    </div>
               </div>
	</div>
			
