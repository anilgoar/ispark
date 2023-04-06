<?php ?>
<script>    
function goBack(){
    window.location="<?php echo $this->webroot;?>OdApprovalDisapprovals";  
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
                    <span>Discard Approved OD</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
                <?php echo $this->Form->create('OdApprovalDisapprovals',array('class'=>'form-horizontal','action'=>'oddisapproval','id'=>'showDetails')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                <label class="col-sm-2 control-label">Emp Code</label>
                    <div class="col-sm-3">
                        <input type="text" name="searchEmp" value="<?php echo isset($searchEmp)?$searchEmp:''?>" autocomplete="off" placeholder="Emp code" class="form-control" required="" >
                    </div> 
                 <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php 
                        echo $this->Form->submit('Search', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new')); 
                        //echo $this->Form->submit('Go Back', array('div'=>false,'onclick'=>'goBack();', 'type'=>'button','class'=>'btn btn-primary pull-left btn-new','style'=>'margin-left:10px;'));
                        ?> 
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                    <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>

                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th>Emp Code</th>
                            <th>Emp Name</th>
                            <th>Designation</th>
                            <th>Branch</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($OdArr as $val){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['OdApplyMaster']['Id'];?>" name="check[]"></center></td>
                        <td><?php echo $val['OdApplyMaster']['EmpCode'];?></td>
                        <td><?php echo $val['OdApplyMaster']['EmpName'];?></td>
                        <td><?php echo $val['OdApplyMaster']['Designation'];?></td>
                        <td><?php echo $val['OdApplyMaster']['BranchName'];?></td>
                        <td><?php echo date('d-M-y',strtotime($val['OdApplyMaster']['StartDate'])) ;?></td>
                        <td><?php echo date('d-M-y',strtotime($val['OdApplyMaster']['EndDate'])) ;?></td>
                        <td><?php echo $val['OdApplyMaster']['Reason'];?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <div class="col-sm-4">
                        <textarea name="DiscardReason" class="form-control pull-right" placeholder="Enter Discard Reason" required="" ></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <?php echo $this->Form->submit('Discard', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                </div>
                
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



