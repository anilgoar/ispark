<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
 function get_Empcode(val)
        {
            //alert(val);
            $.post("LeaveTrackers/empcode",{EmapName:val},function(data)
            {$("#emp").html(data);});
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
<?php echo $this->Form->create('LeaveTrackers',array('class'=>'form-horizontal','action'=>'index','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Incentive Update</span>
                    

		</div>
                
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
                
            </div>
            <div class="box-content">
                
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Employe</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php
               //print_r($Data1);die;
              // foreach($Data as $d){
               echo $this->Form->input('EmapName',array('label' => false,'options'=>$Data2,'class'=>'form-control','empty'=>'Select','onchange'=>"get_Empcode(this.value);"));  ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div> </div>
        <label class="col-sm-2 control-label">EmpCode</label>
        <div class="col-sm-3">
            <div class="input-group"><div id="emp">
               <?php echo $this->Form->input('EmpCode',array('label' => false,'options'=>$Data1,'class'=>'form-control','empty'=>'Select')); ?>
                </div>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
                </div>
		<div class="form-group has-info has-feedback">
        
        <label class="col-sm-2 control-label">Leave Balance Date</label>
        <div class="col-sm-3">
            <div class="input-group">
                  <?php	echo $this->Form->input('leavedate', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'LeaveBalanceDate','onClick'=>"displayDatePicker('data[LeaveTrackers][leavedate]');",'required'=>true)); ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div></div>
            

                </div>

		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                       <input type="submit" class="btn btn-info"  name='Show' value="Save" >
                    </div>
		</div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>





<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Leave Balance</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
				
					<thead>
						<tr class="active">
							<td>EmpCode</td>
							<td>Pl Balnce</td>
							<td>Cl Balance</td>
							<td>El Balnce</td>
                                                        <td>Month</td>
                                                        <td>Edit</td>
						</tr>
                                        </thead>
                                        <tbody>
						<?php //print_r($leave);die;
                                                foreach ($leave as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
							<td><code><?php echo $post['LeavesManager']['EmpCode']; ?></td>
							<td><code><?php echo $post['LeavesManager']['BalancePl']; ?></td>
							<td><code><?php echo $this->Html->link('Edit',array('controller'=>'Addclients','action'=>'edit','?'=>array('id'=>$post['Addclient']['id']),'full_base' => true)); ?></code></td>
                                                         <td><code><?php echo $post['Addclient']['client_status']=='1'?'Active':DeActive; ?></td>   
						</tr>
						<?php endforeach; ?>
						<?php unset($Addclient); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>