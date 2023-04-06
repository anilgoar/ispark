<?php ?>
<script>
$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
});

function reload(url){
    window.location.href = url;
}

function goBack(){
    window.location="<?php echo $this->webroot;?>ProcessDeductions";  
}

function downloadReport(){
    window.location="<?php echo $this->webroot;?>ProcessDeductions/report?CSN=<?php echo isset($headArr['CostCenter'])?base64_encode($headArr['CostCenter']):"";?>&BRN=<?php echo isset($headArr['BranchName'])?base64_encode($headArr['BranchName']):"";?>";  
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
                    <span>PROCEED DEDUCTION</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con"> 
                
                
                <div style="position: relative;top:-28px;">
                <?php echo $this->Form->create('ProcessDeductions',array('class'=>'form-horizontal','action'=>'process','enctype'=>'multipart/form-data')); ?>
                    <input type="hidden" name="BranchName" value="<?php echo isset($headArr['BranchName'])?$headArr['BranchName']:"";?>" >
                    <input type="hidden" name="CostCenter" value="<?php echo isset($headArr['CostCenter'])?$headArr['CostCenter']:"";?>" >
                    
                    <div class="form-group" >
                        <div class="col-sm-2">Branch</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"><?php echo isset($headArr['BranchName'])?$headArr['BranchName']:"";?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">Cost Center</div>
                        <div class="col-sm-1">-</div>
                       <div class="col-sm-2"><?php echo isset($headArr['CostCenter'])?$headArr['CostCenter']:"";?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">Salary Month</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"><?php echo date('M-Y', strtotime(date('Y-m')." -1 month"));?></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">
                            <input type='button' onclick="goBack()" class="btn btn-info pull-right  btn-new" value="Back">
                        </div>
                        
                    </div>
                    
                    <?php if(!empty($fieldArr)){ ?>
                    
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" > 
                    <thead>
                        <tr>
                            <th style="width:30px;text-align: center;" >SNo</th>
                            <th style="width:40px;text-align: center;">EmpCode</th>
                            <th>EmpName</th>
                            <th style="width:40px;text-align: center;">MobileDeduction</th>
                            <th style="width:40px;text-align: center;">ShortCollection</th>
                            <th style="width:40px;text-align: center;">AssetRecovery</th>
                            <th style="width:40px;text-align: center;">ProfessionalTax</th>
                            <th style="width:40px;text-align: center;">LeaveDeduction</th>
                            <th style="width:40px;text-align: center;">Insurance</th>
                            <th style="width:40px;text-align: center;">OtherDeduction</th>
                            <th >Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $MobDed=0;
                        $SotCol=0;
                        $AstRec=0;
                        $PofTax=0;
                        $LeaDed=0;
                        $Insura=0;
                        $OthDed=0;
                        $i=1; foreach($fieldArr as $val){
                            $MobDed=$MobDed+$val['UploadDeductionMaster']['MobileDeduction'];
                            $SotCol=$SotCol+$val['UploadDeductionMaster']['ShortCollection'];
                            $AstRec=$AstRec+$val['UploadDeductionMaster']['AssetRecovery'];
                            $PofTax=$PofTax+$val['UploadDeductionMaster']['ProfessionalTax'];
                            $LeaDed=$LeaDed+$val['UploadDeductionMaster']['LeaveDeduction'];
                            $Insura=$Insura+$val['UploadDeductionMaster']['Insurance'];
                            $OthDed=$OthDed+$val['UploadDeductionMaster']['OthersDeduction'];
                        ?>
                        <tr>
                            <td style="text-align: center;" ><?php echo $i++;?></td>
                            <td style="text-align: center;"><?php echo $val['UploadDeductionMaster']['EmpCode'];?></td>
                            <td><?php echo $val['UploadDeductionMaster']['EmpName'];?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['MobileDeduction'] !=""){ echo $val['UploadDeductionMaster']['MobileDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ShortCollection'] !=""){ echo $val['UploadDeductionMaster']['ShortCollection'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['AssetRecovery'] !=""){ echo $val['UploadDeductionMaster']['AssetRecovery'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ProfessionalTax'] !=""){ echo $val['UploadDeductionMaster']['ProfessionalTax'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['LeaveDeduction'] !=""){ echo $val['UploadDeductionMaster']['LeaveDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['Insurance'] !=""){ echo $val['UploadDeductionMaster']['Insurance'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['OthersDeduction'] !=""){ echo $val['UploadDeductionMaster']['OthersDeduction'];}else{echo 0;}?></td>
                            
                            
                            <td><?php echo $val['UploadDeductionMaster']['Remarks'];?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="text-align: center;">Total</td>
                            <td style="text-align: center;"><?php echo $MobDed;?></td>
                            <td style="text-align: center;"><?php echo $SotCol;?></td>
                            <td style="text-align: center;"><?php echo $AstRec;?></td>
                            <td style="text-align: center;"><?php echo $PofTax;?></td>
                            <td style="text-align: center;"><?php echo $LeaDed;?></td>
                            <td style="text-align: center;"><?php echo $Insura;?></td>
                            <td style="text-align: center;"><?php echo $OthDed;?></td>
                            <td style="text-align: center;"><?php echo ($MobDed+$SotCol+$AstRec+$PofTax+$LeaDed+$Insura+$OthDed);?></td>
                        </tr>
                    </tbody>           
                </table>
                    
                <div class="form-group">
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-5">
                        <textarea name="Remarks" style="height:80px;" class="form-control" required=""></textarea>
                    </div>
                    <div class="col-sm-6">
                            <input type="button" onclick="downloadReport();" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:10px;" >
                            <?php
                            echo $this->Form->submit('Reject', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;'));
                            echo $this->Form->submit('Proceed', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new')); 

                            ?>
                        </div>
                </div>
         

                    
                    
                   
                    
                <?php }?>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span><?php echo $this->Session->flash(); ?></span>
                        </div>
                    </div>
        
                    <?php echo $this->Form->end(); ?> 
                  
                </div>
            </div>
        </div>
    </div>	
</div>




























