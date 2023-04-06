<?php ?>
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
                    <span>DISCARD APPROVED LEAVES </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
               
                <?php echo $this->Form->create('LeaveManagements',array('class'=>'form-horizontal','action'=>'discardapprovedleave','id'=>'showDetails')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Emp Code</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmpCode',array('label' => false,'class'=>'form-control','placeholder'=>'Emp Code','id'=>'EmpCode','required'=>true)); ?>
                    </div>
                     <div class="col-sm-2">
                         <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php echo $this->Form->submit('Show', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?> 
                    </div>
                    
                </div>
                
        
                
                
                <?php if(!empty($data)){ ?>
                <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
                    <thead>
                        <tr>
                         <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="text-align: center;">Emp Code</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;">Leave From</th>
                            <th style="text-align: center;">Leave To</th>
                            <th style="text-align: center;">Reason</th>
                            
                            <th style="text-align: center;">CL</th>
                            <th style="text-align: center;">ML</th>
                           
                            <th style="text-align: center;">EL</th>
                            <th style="text-align: center;">MTRL</th>
                            <th style="text-align: center;">LWP</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($data as $val){?>
                    <tr>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['LeaveManagementMaster']['Id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;"><?php echo $val['LeaveManagementMaster']['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $val['LeaveManagementMaster']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($val['LeaveManagementMaster']['LeaveFrom'])) ;?></td>
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($val['LeaveManagementMaster']['LeaveTo'])) ;?></td>
                         <td style="text-align: center;"><?php echo $val['LeaveManagementMaster']['Purpose'];?></td>
                        <td style="text-align: center;"><?php if($val['LeaveManagementMaster']['CL'] !=""){ echo $val['LeaveManagementMaster']['CL'];}else{echo "0";}?></td>

                      <td style="text-align: center;"><?php if($val['LeaveManagementMaster']['ML'] !=""){ echo $val['LeaveManagementMaster']['ML'];}else{echo "0";}?></td>

                        <td style="text-align: center;"><?php if($val['LeaveManagementMaster']['EL'] !=""){ echo $val['LeaveManagementMaster']['EL'];}else{echo "0";}?></td>

                        <td style="text-align: center;"><?php if($val['LeaveManagementMaster']['MTRL'] !=""){ echo $val['LeaveManagementMaster']['MTRL'];}else{echo "0";}?></td>

                        <td style="text-align: center;"><?php if($val['LeaveManagementMaster']['LWP'] !=""){ echo $val['LeaveManagementMaster']['LWP'];}else{echo "0";}?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Reason</label>
                    <div class="col-sm-4">
                        <textarea name="DiscartReason"  id="DiscartReason" class="form-control" required="" ></textarea>
                    </div> 
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <?php echo $this->Form->submit('Discard', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;'));?>
                    </div>
                    <div class="col-sm-10">
                    <span><?php echo $this->Session->flash(); ?></span>
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



