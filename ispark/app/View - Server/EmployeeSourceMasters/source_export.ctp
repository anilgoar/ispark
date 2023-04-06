<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
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
<script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'd-M-yy'
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
                    <span>Employee Source Export</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('EmployeeSourceMasters',array('id'=>'EmployeeSource','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select Branch','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">FromDate</label>
                    <div class="col-sm-2">
                        <input type="text" id="FromDate" name="FromDate" autocomplete="off" value="<?php echo $OldFrom;?>" class="form-control datepik"  required="" >
                    </div>
                    
                    <label class="col-sm-1 control-label">ToDate</label>
                    <div class="col-sm-2">
                        <input type="text" id="ToDate" name="ToDate" autocomplete="off" value="<?php echo $OldTo;?>" class="form-control datepik"  required="" >
                    </div>   
                </div>
                
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Status</label>
                    <div class="col-sm-2">
                        <select name="status" id="status" class="form-control">
                            <option value="ALL" <?php if($status=='All') echo "Selected"; ?>>ALL</option>
                            <option value="1" <?php if($status=='1') echo "Selected"; ?>>Active</option>
                            <option value="0" <?php if($status=='0') echo "Selected"; ?>>Left</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit"  value="Export" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit"  value="Show" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                <div class="form-group">
                  
                    <div class="col-sm-9" style="overflow-y:auto;">
                        
                        <?php if(!empty($BranchArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:50px;">SNo.</th>
                                    <th style="text-align: center;width:150px;">Branch Name</th>
                                    <th style="text-align: center;">Source</th>
                                    <th style="text-align: center;">0-30</th>
                                    <th style="text-align: center;">30-90</th>
                                    <th style="text-align: center;">90-180</th>
                                    <th style="text-align: center;">180Above</th>
                                    <th style="text-align: center;">Total</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $inswise=array(); $n=1; $days=array("0-30","31-90","91-180","180Above"); 
                                foreach ($BranchArr as $br){?>
                                    <?php foreach($SourceArr as $source) {?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $n++;?></td>
                                        <td style="text-align: center;"><?php echo $br;?></td>
                                        <td style="text-align: center;"><a href="#" onclick="get_details_source_branch('<?php echo $br; ?>','<?php echo $source; ?>','<?php echo $FromDate; ?>','<?php echo $ToDate; ?>','<?php echo $status; ?>')" ><?php echo $source;?></a></td>
                                    <?php $t1=0; foreach($days as $d){?>
                                        <td style="text-align: center;" >
                                            <?php if(!empty($DataArr[$br][$source][$d])){
                                                $t1=$t1+$DataArr[$br][$source][$d];
                                                
                                                $inswise[$d]+=$DataArr[$br][$source][$d];
                                            ?>
                                                <a href="#" onclick="get_details_source('<?php echo $br; ?>','<?php echo $source; ?>','<?php echo $d; ?>','<?php echo $FromDate; ?>','<?php echo $ToDate; ?>','<?php echo $status; ?>')" ><?php echo $DataArr[$br][$source][$d];  ?></a>
                                            <?php }else{echo 0;} ?>    
                                        </td>    
                                    <?php }?>
                                        <td style="text-align:center;" ><?php if($t1 !=0){ echo $t1;}else{echo 0;}?></td>
                                    </tr>
                                        <?php } ?>
                                
                                <?php }?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;" ><strong>Total</strong></td>
                                    <?php $ft=0; foreach($days as $d){$ft=$ft+$inswise[$d];?>
                                    <td style="text-align: center;" ><strong><?php if($inswise[$d] !=""){echo $inswise[$d];}else{echo 0; }?></strong></td> 
                                    <?php }?>
                                    <td style="text-align:center;"><strong><?php echo $ft;?></strong></td>
                                </tr>
                            </tbody>   
                        </table>
                        <?php } ?>
                        
                    </div>
                    
                </div>
                <div class="form-group">
                    <div class="col-sm-9"  id="SourceDetails">
                </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

<script>
    function get_details_source(Branch,Source,Day,FromDate,ToDate,status){
        $.post("<?php echo $this->webroot;?>"+"EmployeeSourceMasters/show_detail",{
            Branch: Branch,
            Source:Source,
            Day:Day,
            FromDate:FromDate,
            ToDate:ToDate,
            status:status
        },
        function(data,status){
           $('#SourceDetails').html(data); 
        });
    }
    
    function export_details_source(Branch,Source,Day,FromDate,ToDate,status){
        
        
        window.location="<?php echo $this->webroot;?>EmployeeSourceMasters/export_detail?Branch="+Branch+"&Source="+Source+"&Day="+Day+"&FromDate="+FromDate+"&ToDate="+ToDate+"&status="+status;
    }
    
    
    function get_details_source_branch(Branch,Source,FromDate,ToDate,status)
    {
        $.post("<?php echo $this->webroot;?>"+"EmployeeSourceMasters/show_detail_branch",
        {
            Branch: Branch,
            Source:Source,
            FromDate:FromDate,
            ToDate:ToDate,
            status:status
        },
        function(data,status){
           $('#SourceDetails').html(data); 
           //alert(data);
        });
    }
    
    function export_details_source_branch(Branch,Source,FromDate,ToDate,status){
        window.location="<?php echo $this->webroot;?>EmployeeSourceMasters/export_detail_branch?Branch="+Branch+"&Source="+Source+"&FromDate="+FromDate+"&ToDate="+ToDate+"&status="+status;
    }
</script>

