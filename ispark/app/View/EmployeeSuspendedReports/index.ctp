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
    $.post("<?php echo $this->webroot;?>EmployeeSuspendedReports/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function getsuspended(BranchName,CostCenter,Status){ 
    $("#loder").show();
    $.post("<?php echo $this->webroot;?>EmployeeSuspendedReports/suspendeddetails",{BranchName:BranchName,CostCenter:CostCenter,Status:Status}, function(data){
        $("#loder").hide();
        $("#suspendeddetails").html(data);
    });  
}
/*
function suspendedexport(BranchName,CostCenter,Status){ 
    $("#loder").show();
    $.post("<?php echo $this->webroot;?>EmployeeSuspendedReports/suspendedexport",{BranchName:BranchName,CostCenter:CostCenter,Status:Status}, function(data){
        $("#loder").hide();
        $("#suspendeddetails").html(data);
    });  
}
*/
function suspendedexport(BranchName,CostCenter,Status){
    
    window.location="<?php echo $this->webroot;?>EmployeeSuspendedReports/suspendedexport?BranchName="+BranchName+"&CostCenter="+CostCenter+"&Status="+Status;  
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
                    <span>EMPLOYEE SUSPENDED</span>
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
                <?php echo $this->Form->create('EmployeeSuspendedReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit"  value="Export" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit"  onclick="showloder();"  value="Search" class="btn pull-right btn-primary btn-new" >
                        
                    </div>
                    
                </div>
                
              
               
                <div class="form-group">
                    <div class="col-sm-9" <?php if($totalcount >15){?> style="overflow-y: scroll;height:300px;"<?php }?> >
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:30px;">SNo</th>
                                    <th style="text-align: center;width:200px;">Branch</th>
                                    <th style="text-align: center;width:200px;">CostCenter</th>
                                    <th style="text-align: center;width:80px;">Active</th>
                                    <th style="text-align: center;width:100px;">Suspended</th>
                                    <th style="text-align: center;width:100px;">LongLeave</th>
                                    <th style="text-align: center;width:100px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php
                                $n=1; 
                                $Active=0;
                                $Suspended=0;
                                $LongLeave=0;
                                $Total=0;
                                
                                foreach ($data as $val){
                                $Active=$Active+$val['Active'];
                                $Suspended=$Suspended+$val['Suspended'];
                                $LongLeave=$LongLeave+$val['LongLeave'];
                                $Total=$Total+$val['Total'];
                                
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['BranchName'];?></td>
                                    <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                                    <td style="text-align: center;"><a onclick="getsuspended('<?php echo $val['BranchName'];?>','<?php echo $val['CostCenter'];?>','Active')" href="#"><?php echo $val['Active'];?></a></td>
                                    <td style="text-align: center;"><a onclick="getsuspended('<?php echo $val['BranchName'];?>','<?php echo $val['CostCenter'];?>','Suspended')" href="#"><?php echo $val['Suspended'];?></a></td>
                                    <td style="text-align: center;"><a onclick="getsuspended('<?php echo $val['BranchName'];?>','<?php echo $val['CostCenter'];?>','LongLeave')" href="#"><?php echo $val['LongLeave'];?></a></td>
                                    <td style="text-align: center;"><a onclick="getsuspended('<?php echo $val['BranchName'];?>','<?php echo $val['CostCenter'];?>','Total')" href="#"><?php echo $val['Total'];?></a></td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align: center;"><strong>Total</strong></td>
                                    <td style="text-align: center;"><strong><?php echo $Active;?></strong></td>
                                    <td style="text-align: center;"><strong><?php echo $Suspended;?></strong></td>
                                    <td style="text-align: center;"><strong><?php echo $LongLeave;?></strong></td>
                                    <td style="text-align: center;"><strong><?php echo $Total;?></strong></td>
                                </tr>
                            </tbody>   
                        </table>
                        <?php }?>
                    </div>
                    
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-9" id="suspendeddetails">
                        
                    </div>
                    
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



