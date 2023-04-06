<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'M-yy'
    });
});
</script>

<script>
$(document).ready(function(){
    <?php if(isset($branchname)){ ?>
        getBranch('<?php echo $branchname;?>');
    <?php }?>
});
    
function getBranch(BranchName){   
    $("#loder").show();
    $.post("<?php echo $this->webroot;?>EmployeePendingReports/pendingdata",{BranchName:BranchName}, function(data){
        $("#loder").hide();
        $("#PendingData").html(data);
    });   
}





function getpending(BranchName,Status){ 
    $("#loder").show();
    $.post("<?php echo $this->webroot;?>EmployeePendingReports/pendingdetails",{BranchName:BranchName,Status:Status}, function(data){
        $("#loder").hide();
        $("#pendingdetails").html(data);
    });  
}
/*
function suspendedexport(BranchName,CostCenter,Status){ 
    $("#loder").show();
    $.post("<?php echo $this->webroot;?>EmployeePendingReports/suspendedexport",{BranchName:BranchName,CostCenter:CostCenter,Status:Status}, function(data){
        $("#loder").hide();
        $("#suspendeddetails").html(data);
    });  
}
*/
function pendingexport(BranchName,Status){
    
    window.location="<?php echo $this->webroot;?>EmployeePendingReports/exportpendingdetails?BranchName="+BranchName+"&Status="+Status;  
}

function showloder(){
    $('#loder').show();
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
                    <span>EMPLOYEE PENDING</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('EmployeePendingReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','value'=>$this->Session->read('branch_name'),'class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        
                        <!--
                        <input type="submit" name="Submit"  value="Export" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit"  onclick="showloder();"  value="Search" class="btn pull-right btn-primary btn-new" >
                        -->
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                    
                </div>
                
                
               
                <div class="form-group">
                   
                    <div class="col-sm-10" id="PendingData" >
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;">SNo</th>
                                    <th style="text-align: center;width:150px;">Branch</th>
                                    <th style="text-align: center;">Pending JCLR</th>
                                    <th style="text-align: center;">Mark Inactive In Cosec</th>
                                    <th style="text-align: center;">Not in Biometrics</th>
                                    <th style="text-align: center;">Pending For Allocation</th>
                                    <th style="text-align: center;">Suspended</th>
                                    <th style="text-align: center;">Date</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php
                                $n=1; 
                                $PenJclr=0;
                                $MarkInactive=0;
                                $NotInBio=0;
                                $PenAlloc=0;
                                $Suspended=0;
                                
                                foreach ($data as $val){
                                $PenJclr=$PenJclr+$val['PenJclr'];
                                $MarkInactive=$MarkInactive+$val['MarkInactive'];
                                $NotInBio=$NotInBio+$val['NotInBio'];
                                $PenAlloc=$PenAlloc+$val['PenAlloc'];
                                $Suspended=$Suspended+$val['Suspended'];
                                
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['BranchName'];?></td>
                                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','PenAlloc')" href="#"><?php echo $val['PenJclr'];?></a></td>
                                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','MarkInactive')" href="#"><?php echo $val['MarkInactive'];?></a></td>
                                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','NotInBio')" href="#"><?php echo $val['NotInBio'];?></a></td>
                                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','PenJclr')" href="#"><?php echo $val['PenAlloc'];?></a></td>
                                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','Suspended')" href="#"><?php echo $val['Suspended'];?></a></td>
                                    <td style="text-align: center;"><?php if($val['LasAttendanceDate'] !=""){ echo date('d-M-Y',strtotime($val['LasAttendanceDate']));}?></td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center;"><strong>Total</strong></td>
                                    <td style="text-align: center;"><strong><?php echo $PenJclr;?></strong></td>
                                    <td style="text-align: center;"><strong><?php echo $MarkInactive;?></strong></td>
                                    <td style="text-align: center;"><strong><?php echo $NotInBio;?></strong></td>
                                    <td style="text-align: center;"><strong><?php echo $PenAlloc;?></strong></td>
                                    <td style="text-align: center;"><strong><?php echo $Suspended;?></strong></td>
                                    <td></td>
                                </tr>
                            </tbody>   
                        </table>
                        <?php }?>
                    </div>
                    <!--
                    <div class="col-sm-1" style="margin-top:12px;">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                    
                    <div class="col-sm-1" style="margin-top:12px;">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                    -->
                </div>
          
                <div class="form-group">
                    <div class="col-sm-10" id="pendingdetails">
                        
                    </div>
                    
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



