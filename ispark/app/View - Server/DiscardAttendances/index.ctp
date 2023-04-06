<?php ?>
<script>
function deleteProcess(Id){
    if(confirm('Are you sure you want to discard this record?')){
        window.location="<?php echo $this->webroot;?>DiscardAttendances/delete_attendance?Id="+Id;   
    }
}

function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>DiscardAttendances/getdiscard",{BranchName:BranchName}, function(data){
        $("#DiscardData").html(data);
    });  
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
                    <span> DISCARD PROCESS ATTENDANCE </span>
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
                
                
                
                <?php echo $this->Form->create('DiscardAttendances',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-6" id="DiscardData" >
                        <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >         
                            <thead>
                                <tr>                	
                                    <th style="width: 30px;">SNo</th>
                                    <th style="text-align: center; ">Cost Center</th>
                                    <th style="text-align: center; width:150px;" >Total Employee</th>
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
                                    <td style="text-align: center;" ><i onclick="deleteProcess('<?php echo $val['Id'];?>');" style="cursor:pointer;" class="material-icons">delete_forever</i></td>
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



