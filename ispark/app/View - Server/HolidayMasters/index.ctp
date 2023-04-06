<?php ?>
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#HolydayDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<script>
function validateDepartment(){
    $("#msgerr").remove();
    var Department=$("#Department").val();
    
    if(Department ===""){
        $("#Department").focus();
        $("#Department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter department.</span>");
        return false;
    }
    else{
        return true;
    }
}
    
function addNew(){
    window.location="<?php echo $this->webroot;?>HolidayMasters";
}


function viewHoliday(){
    $("#msgerr").remove();
    var branch =$("#BranchName").val();
    var YearName =$("#YearName").val();
    
    if(branch ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch.</span>");
        return false; 
    }
    else{
       window.location="<?php echo $this->webroot;?>HolidayMasters?BRANCH="+branch+"&YEAR="+YearName; 
    }
}

function actionlist(path,action){
    if(action =="edit"){
        window.location=path;
    }
    else if(action =="delete"){
        if(confirm('Are you sure you want to delete this list?')){
            window.location=path;
        }
    }
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
                    <span>HOLIDAY LIST </span>
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
                <?php echo $this->Form->create('HolidayMasters',array('action'=>'index','class'=>'form-horizontal','id'=>'HoliddayList','onSubmit'=>'return validateDepartment()')); ?>
                <div class="form-group"> 
                     <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>isset($row['BranchName'])?$row['BranchName']:'','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                     
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="YearName" id="YearName" >
                            <option value="<?php echo date("Y",strtotime("+1 year"));?>" ><?php echo date("Y",strtotime("+1 year"));?></option>
                            <option value="<?php echo date('Y');?>" ><?php echo date('Y');?></option>
                            <option value="<?php echo date("Y",strtotime("-1 year"));?>" ><?php echo date("Y",strtotime("-1 year"));?></option>
                        </select>
                    </div> 
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="viewHoliday();"  value="View" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                    </div>
                     
                </div>
                
                <div class="form-group">
                    
                    <label class="col-sm-1 control-label">Date</label>
                    <div class="col-sm-2">
                        <input type="text" id="HolydayDate" name="HolydayDate" required="" value="<?php echo isset($row['HolydayDate'])?date('d-M-Y',strtotime($row['HolydayDate'])):'';?>" autocomplete="off" class="form-control" >
                    </div>  

                    <label class="col-sm-1 control-label">Occasion</label>
                    <div class="col-sm-2">
                        <input type="text" id="Occasion" name="Occasion" required="" value="<?php echo isset($row['Occasion'])?$row['Occasion']:'';?>" autocomplete="off" class="form-control" >
                    </div>
                     
                    <label class="col-sm-1 control-label">IsRestricted?</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="Restricted" >
                            <option <?php if($row['Restricted']=="Yes"){echo "selected='selected'";}?> value="Yes" >Yes</option>
                            <option <?php if($row['Restricted']=="No"){echo "selected='selected'";}?> value="No" >No</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-2">
                        <?php if(isset($row)){?>
                        <input type="hidden" name="HolidayListId" value="<?php echo isset($row['Id'])?$row['Id']:'';?>" >
                        <input type="button" onclick="addNew();"  value="Add New" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input type="submit" name="submit"  value="Update" class="btn pull-right btn-primary btn-new"  >
                        <?php }else{?>
                        <input type="submit"  name="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                        <?php }?>
                    </div>
                </div>
                
                
               
                
                <div class="form-group">
                    <div class="col-sm-9">
                        <?php if(!empty($DataArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="width:100px;text-align: center;">Location</th>
                                    <th style="width:100px;text-align: center;">Day</th>
                                    <th style="width:100px;text-align: center;">Date</th>
                                    <th style="width:100px;text-align: center;">Occasion</th>
                                    <th style="width:100px;text-align: center;">Restricted</th>
                                    <?php if($this->Session->read('role')=='admin' && $this->Session->read('branch_name') =="HEAD OFFICE"){?>
                                    <th style="width:40px;text-align: center">Action</th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php foreach ($DataArr as $val){?>
                                <tr>
                                    <td style="text-align: center;" ><?php echo $val['HolidayMaster']['BranchName'];?></td>
                                    <td style="text-align: center;"><?php echo $val['HolidayMaster']['HolydayDay'];?></td>
                                    <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['HolidayMaster']['HolydayDate']));?></td>
                                    <td style="text-align: center;"><?php echo $val['HolidayMaster']['Occasion'];?></td>
                                    <td style="text-align: center;"><?php echo $val['HolidayMaster']['Restricted'];?></td>
                                    <?php if($this->Session->read('role')=='admin' && $this->Session->read('branch_name') =="HEAD OFFICE"){?>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>HolidayMasters?id=<?php echo base64_encode($val['HolidayMaster']['Id']);?>','edit');" class="material-icons" style="font-size:20px;cursor: pointer;" >mode_edit</i></span>
                                        <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>HolidayMasters/deletesource?id=<?php echo base64_encode($val['HolidayMaster']['Id']);?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                                    </td>
                                    <?php }?>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                        <?php } ?>
                        
                    </div>
                </div>
               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



