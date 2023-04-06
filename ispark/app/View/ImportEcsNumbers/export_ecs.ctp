<?php
$backurl=$this->webroot."Menus?AX=MTA3";
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#FromDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#ToDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
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
                    <span>EXPORT ECS NUMBER</span>
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
                <?php echo $this->Form->create('ImportEcsNumbers',array('action'=>'export_ecs_details','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">FromDate</label>
                    <div class="col-sm-2">
                        <input type="text" name="FromDate" id="FromDate"  class="form-control" required=""  >
                    </div>
                    <label class="col-sm-1 control-label">ToDate</label>
                    <div class="col-sm-2">
                        <input type="text" name="ToDate" id="ToDate"  autocomplete="off" class="form-control" required=""  >
                    </div>
                    
                    <div class="col-sm-2">
                        <input type="submit"  value="Export" class="btn pull-right btn-primary btn-new">
                    </div> 
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>





<!--
<script>
function ViewFinalize(CostCenter,Id){
    $("#loder").show();
    $("#msgerr").remove();
   
    $.post("<?php echo $this->webroot;?>FinalizeAttendances/show_report",{CostCenter:CostCenter,Id:Id}, function(data) {
        $("#loder").hide();
        if(data !=""){
            $("#processAttend").html(data);
        }
        else{
            $("#processAttend").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
        } 
    }); 
}

function ExportFinalize(CostCenter){
    $("#loder").show();
    $("#loder").hide();
    window.location="<?php echo $this->webroot;?>FinalizeAttendances/export_report?CostCenter="+CostCenter;   
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
                    <span>FINALIZE ATTENDANCE </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('FinalizeAttendances',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >         
                            <thead>
                                <tr>                	
                                    <th style="width: 30px;">SNo</th>
                                    <th >Cost Center</th>
                                    <th style="text-align: center; width:120px;" >Total Employee</th>
                                    <th style="text-align: center;width:100px;" >Status</th>
                                    <th style="text-align: center;width: 40px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total=0;
                                $i=1; foreach ($fieldArr as $val){
                                $total=$total+$val['TotalEmp'];
                                $cosc = base64_encode($val['CostCenter']);
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $i++;?></td>
                                    <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                                    <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                                    <?php if($val['Status'] > 0){?>
                                    <td style="text-align: center;color: green;"><?php echo "FINALIZE"?></td>
                                    <?php }else{?>
                                    <td style="text-align: center;color: red;"><?php echo "PROCESS";?></td>
                                    <?php }?>
                                    <td style="text-align: center;" ><i onclick="ViewFinalize('<?php echo $val['CostCenter'];?>','<?php echo $val['Id'];?>');" style="cursor:pointer;" class="material-icons">pageview</i></td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total;?></td>
                                    <td></td>
                                </tr>
                            </tbody>           
                        </table>
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:25px;display: none;position:relative;top:25px;" id="loder"  >
                    </div>
                     <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=Mg%3D%3D"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="position:relative;top:25px;" />
                    </div>
                </div>
           
                <div class="form-group" style="position: relative;top:-60px;" id="processAttend" ></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
-->


