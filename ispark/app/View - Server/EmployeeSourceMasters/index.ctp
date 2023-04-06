<?php ?>
<script>
    /*
function searchEmployee(){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var SourceType=$("#SourceType").val();
    var SourceName=$("#SourceName").val();
    
    if(SourceType ===""){
        $("#SourceType").focus();
        $("#SourceType").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select SourceType.</span>");
        return false;
    }
    else if(SourceName ===""){
        $("#SourceName").focus();
        $("#SourceName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter SourceName.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>EmployeeDetails/show_employee",{BranchName:BranchName,SearchType:SearchType,SearchValue:$.trim(SearchValue)}, function(data) {
            if(data !=""){
                $("#divEmployee").html(data);
            }
            else{
                $("#divEmployee").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
            }
        });
    }
}
*/

function getBranch(){
    $("#EmployeeSource").submit();
}
   
function deleteSource(path,action){
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
                    <span>EMPLOYEE SOURCE</span>
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
                <?php echo $this->Form->create('EmployeeSourceMasters',array('action'=>'index','id'=>'EmployeeSource','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select Branch','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">SourceType</label>
                    <div class="col-sm-2">
                        <select id="SourceType" name="SourceType" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option value="Source">Source</option>
                            <option value="CONSULTANT">CONSULTANT</option>
                            <option value="NAUKRI.COM">NAUKRI.COM</option>
                            <option value="EMPLOYEE REFERRAL">EMPLOYEE REFERRAL</option>
                            <option value="WALK IN">WALK IN</option>
                            <option value="ADVERTISEMENT">ADVERTISEMENT</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">SourceName</label>
                    <div class="col-sm-3">
                        <input type="text" id="SourceName" name="SourceName" autocomplete="off" class="form-control"  required="" >
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10">
                        <?php if(!empty($DataArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:50px;">SNo</th>
                                    <th style="text-align: center;width:150px;">BranchName</th>
                                    <th style="text-align: center;">SourceType</th>
                                    <th >SourceName</th>
                                    <th style="text-align: center;width:50px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($DataArr as $val){?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['EmployeeSourceMasters']['BranchName'];?></td>
                                    <td style="text-align: center;"><?php echo $val['EmployeeSourceMasters']['SourceType'];?></td>
                                    <td ><?php echo $val['EmployeeSourceMasters']['SourceName'];?></td>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="deleteSource('<?php $this->webroot;?>EmployeeSourceMasters/deletesource?id=<?php echo $val['EmployeeSourceMasters']['Id'];?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                                        
                                    </td>
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



