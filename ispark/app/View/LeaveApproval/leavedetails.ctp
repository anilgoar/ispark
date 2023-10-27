<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#LeaveFrom").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#LeaveTo").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<script>
function goBack(){
    window.location="<?php echo $this->webroot;?>LeaveManagements";  
}
</script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
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
            <div class="box-header"  >
                <div class="box-name">
                    <span>LEAVE REPORTS</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
               
                <?php echo $this->Form->create('LeaveManagements',array('class'=>'form-horizontal','action'=>'leavedetails','id'=>'showDetails')); ?>
                <span style="position: relative;left:170px;" ><?php echo $this->Session->flash(); ?></span>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">FromDate</label>
                    <div class="col-sm-2">
                        <input type="text" name="LeaveFrom" id="LeaveFrom" value="<?php echo isset($fromdate)?date('d-M-Y',strtotime($fromdate)):'';?>" class="form-control" required=""  >
                    </div>
                    <label class="col-sm-1 control-label">ToDate</label>
                    <div class="col-sm-2">
                        <input type="text" name="LeaveTo" id="LeaveTo" value="<?php echo isset($todate)?date('d-M-Y',strtotime($todate)):'';?>" autocomplete="off" class="form-control" required=""  >
                    </div>
                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <select name="TypeName" id="TypeName" autocomplete="off" class="form-control" required="" >
                            <option value="">Select Type</option>
                            <option <?php if(isset($typename) && $typename =="Approved"){echo "selected='selected'";} ?> value="Approved">Approved</option>
                            <option <?php if(isset($typename) && $typename =="Not Approved"){echo "selected='selected'";} ?> value="Not Approved">Not Approved</option>
                            <option <?php if(isset($typename) && $typename =="Both"){echo "selected='selected'";} ?> value="Both">Both</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php echo $this->Form->submit('Export', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left: 5px;'));?>
                        <?php echo $this->Form->submit('View', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                        
                    </div>
                </div>
                

                <?php if(!empty($data)){ ?>
                <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
                    <thead>
                        <tr>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Emp Code</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;">Process</th>
                            <th style="text-align: center;">Leave From</th>
                            <th style="text-align: center;">Leave To</th>
                            <th style="text-align: center;">Purpose</th>
                            <th style="text-align: center;">Address</th>
                            <th style="text-align: center;">Contact No</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">CL</th>
                            <th style="text-align: center;">ML</th>
                           
                            <th style="text-align: center;">EL</th>
                            <th style="text-align: center;">MTRL</th>
                            <th style="text-align: center;">PTRL</th>
                            <th style="text-align: center;">LWP</th>
                            <th style="text-align: center;">Total Leave</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php 
                    foreach ($data as $val){
                        $row=$val['LeaveManagementMaster'];
                        $total=($row['CL']+$row['ML']+$row['DL']+$row['EL']+$row['MTRL']+$row['PTRL']+$row['LWP']);
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $row['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo $row['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $row['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $row['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($row['LeaveFrom'])) ;?></td>
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($row['LeaveTo'])) ;?></td>
                        <td style="text-align: center;"><?php echo $row['Purpose'];?></td>
                        <td style="text-align: center;"><?php echo $row['Address'];?></td>
                        <td style="text-align: center;"><?php echo $row['Contace'];?></td>
                        <td style="text-align: center;"><?php echo $row['Status'];?></td>
                        <td style="text-align: center;"><?php if($row['CL'] !=""){ echo $row['CL'];}else{echo 0;}?></td>
          
                     <td style="text-align: center;"><?php if($row['ML'] !=""){ echo $row['ML'];}else{echo 0;}?></td>
                      
                        <td style="text-align: center;"><?php if($row['EL'] !=""){ echo $row['EL'];}else{echo 0;}?></td>
                      
                        <td style="text-align: center;"><?php if($row['MTRL'] !=""){ echo $row['MTRL'];}else{echo 0;}?></td>
                   
                        <td style="text-align: center;"><?php if($row['PTRL'] !=""){ echo $row['PTRL'];}else{echo 0;}?></td>
                    
                        <td style="text-align: center;"><?php if($row['LWP'] !=""){ echo $row['LWP'];}else{echo 0;}?></td>
                        <td style="text-align: center;"><?php echo $total;?></td>  
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                <?php }else{?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                       <span>No Record for Approval.</span>
                    </div>
                </div>
                <?php }?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



