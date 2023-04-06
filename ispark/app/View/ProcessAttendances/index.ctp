<?php ?>
<script>
function ViewProcess(CostCenter){
    $("#loder").show();
    $("#msgerr").remove();
   
    $.post("<?php echo $this->webroot;?>ProcessAttendances/show_report",{CostCenter:CostCenter}, function(data) {
        $("#loder").hide();
        if(data !=""){
            $("#processAttend").html(data);
        }
        else{
            $("#processAttend").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
        } 
    }); 
}

function ExportProcess(CostCenter){
    $("#loder").show();
    $("#loder").hide();
    window.location="<?php echo $this->webroot;?>ProcessAttendances/export_report?CostCenter="+CostCenter;   
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
                    <span>PROCESS ATTENDANCE </span>
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
                <?php echo $this->Form->create('ProcessAttendances',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >         
                            <thead>
                                <tr>                	
                                    <th style="width: 30px;">SNo</th>
                                    <th style="text-align: center;">Cost Center</th>
                                    <th style="text-align: center; width:100px;" >Total Employee</th>
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
                                    <td style="text-align: center;color: green;"><?php echo "PROCESS"?></td>
                                    <?php }else{?>
                                    <td style="text-align: center;color: red;"><?php echo "UNPROCESS";?></td>
                                    <?php }?>
                                    <td style="text-align: center;" ><i onclick="ViewProcess('<?php echo $val['CostCenter'];?>');" style="cursor:pointer;" class="material-icons">pageview</i><!--<a href="<?php $this->webroot;?>OnSiteAttendances/markfield?CSN=<?php echo $cosc;?>">Mark Attendance</a>--></td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total;?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>           
                        </table>
                    </div>
                    <div class="col-sm-2">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:25px;display: none;position:relative;top:25px;" id="loder"  >
                    </div>
                   
                    
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                    <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                </div>
                </div>
                
                
           
                <div class="form-group" style="position: relative;top:-30px;" id="processAttend" ></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



