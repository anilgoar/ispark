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
    
function getBranch(branch){
    $("#showDetails").submit();
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
                    <span>GENERATE EMPLOYEE CODE </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('EmpcodeGenerates',array('class'=>'form-horizontal','action'=>'index','id'=>'showDetails')); ?>
                <input type="hidden" id="ApproveStatus" >
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;" >SNo</th>
                            <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="text-align: center;width:120px;">Emp Type</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;width:80px;">DOJ</th>
                            <th style="text-align: center;width:120px;">Desig</th>
                            <!--
                            <th style="text-align: center;width:120px;">Depart</th>
                            -->
                            <th style="text-align: center;width:120px;">CostCenter</th>
                            <th style="text-align: center;width:30px;">Band</th>
                            <th style="text-align: center;width:30px;">Basic</th>
                            <th style="text-align: center;width:30px;">Gross</th>
                            <th style="text-align: center;width:70px;">PF Elig</th>
                            <th style="text-align: center;width:70px;">ESIC Elig</th>
                            <th style="text-align: center;width:60px;">CTC</th>
                            
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($OdArr as $val){$Desgination=$val['NewjclrMaster']['Desgination'];?>
                    <?php //if($Desgination=="Executive - Voice" || $Desgination=="Sr. Executive - Voice" || $Desgination=="Executive - Field" || $Desgination=="Sr.Executive - Field"){ ?>
                    <?php if($Desgination=="EXECUTIVE"){ ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['NewjclrMaster']['id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['EmpType'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['EmpName'];?></td>
                        <td style="text-align: center;"><?php if($val['NewjclrMaster']['DOJ'] !=""){echo date('d M y',strtotime($val['NewjclrMaster']['DOJ']));}?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Desgination'];?></td>
                        <!--
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Dept'];?></td>
                        -->
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Band'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['bs'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Gross'];?></td>
                        <td ><center><input class="checkbox" <?php if($val['NewjclrMaster']['EmpType']=="ONROLL"){?>checked<?php }?>  disabled type="checkbox"> </center></td>
                        <td ><center><input class="checkbox" <?php if($val['NewjclrMaster']['EmpType']=="ONROLL"){?>checked<?php }?>  disabled type="checkbox"> </center></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['CTC'];?></td>
                       
                    </tr>
                    <?php }?>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <?php 
                        echo $this->Form->submit('Generate Employee Code', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));                   
                        ?>
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



