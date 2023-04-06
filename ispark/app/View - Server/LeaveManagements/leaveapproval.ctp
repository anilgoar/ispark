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
                    <span>LEAVE APPROVAL</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
                <?php echo $this->Form->create('LeaveManagements',array('class'=>'form-horizontal','action'=>'leaveapproval','id'=>'showDetails')); ?>
                <?php if(!empty($data)){ ?>
                <table class = "table table-striped table-hover  responstable" >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="text-align: center;width:30px;">SNo</th>
                            
                            <th style="text-align: center;width:70px;">Emp Code</th>
                            <th>Emp Name</th>
                            
                            <th style="text-align: center;width:70px;">Leave From</th>
                            <th style="text-align: center;width:70px;">Leave To</th>
                            <th style="text-align: center;width:30px;">CL</th>
                            <th style="text-align: center;width:30px;">ML</th>
                            <th style="text-align: center;width:30px;">EL</th>
                            <th style="text-align: center;width:30px;">MTRL</th>
                            <th style="text-align: center;width:30px;">PTRL</th>
                            <th style="text-align: center;width:30px;">LWP</th>
                            <th style="text-align: center;width:30px;">Total</th>
                            <th>Reason</th>
                            
                        </tr>
                    </thead>
                    <tbody>         
                    <?php 
                    $i=1;
                    foreach ($data as $val){
                        $row=$val['LeaveManagementMaster'];
                        $total=($row['CL']+$row['ML']+$row['DL']+$row['EL']+$row['MTRL']+$row['PTRL']+$row['LWP']);
                    ?>
                    <tr>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $row['Id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;"><?php echo $i++;?></td>
                       
                        <td style="text-align: center;"><?php echo $row['EmpCode'];?></td>
                        <td><?php echo $row['EmpName'];?></td>
                       
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($row['LeaveFrom'])) ;?></td>
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($row['LeaveTo'])) ;?></td>
                        
                        <td style="text-align: center;"><?php echo isset($row['CL'])?$row['CL']:0; ?></td>
                        <td style="text-align: center;"><?php echo isset($row['ML'])?$row['ML']:0; ?></td>
                        <td style="text-align: center;"><?php echo isset($row['EL'])?$row['EL']:0; ?></td>
                        <td style="text-align: center;"><?php echo isset($row['MTRL'])?$row['MTRL']:0; ?></td>
                        <td style="text-align: center;"><?php echo isset($row['PTRL'])?$row['PTRL']:0; ?></td>
                        <td style="text-align: center;" ><?php echo isset($row['LWP'])?$row['LWP']:0; ?></td>
                       
                       
                        
                        
                        <td style="text-align: center;"><?php echo $total;?></td>
                        <td><?php echo $row['Purpose'];?></td>
                        
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php 
                        //echo $this->Form->submit('Back', array('div'=>false,'onclick'=>'goBack();', 'type'=>'button','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;'));
                        echo $this->Form->submit('Not Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
                        echo $this->Form->submit('Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));                   
                        ?>
                    </div>
                    
                   
                    <div class="col-sm-10">
                    <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>
                
                <?php }else{?>
                <div class="form-group">
                    <div class="col-sm-6">
                       <span>No Record for Approval.</span>
                    </div>
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php 
                        //echo $this->Form->submit('Back', array('div'=>false,'onclick'=>'goBack();', 'type'=>'button','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;'));
                        ?> 
                    </div>
                </div>
                <?php }?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



