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
                    <span>Employee Attrition Report</span>
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
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <select name="Type" id="Type" class="form-control" required="">
                            <option value="">Select</option>
                            <option value="Branch" <?php if($Type=='Branch') { echo "Selected";} ?>>Branch</option>
                            <option value="CostCenter" <?php if($Type=='CostCenter') { echo "Selected";} ?>>Cost Center</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select name="Month" id="Month" class="form-control" required="">
                            <option value="">Select</option>
                            <option value="01" <?php if($Month=='01') { echo "Selected";} ?>>Jan</option>
                            <option value="02" <?php if($Month=='02') { echo "Selected";} ?>>Feb</option>
                            <option value="03" <?php if($Month=='03') { echo "Selected";} ?>>Mar</option>
                            <option value="04" <?php if($Month=='04') { echo "Selected";} ?>>Apr</option>
                            <option value="05" <?php if($Month=='05') { echo "Selected";} ?>>May</option>
                            <option value="06" <?php if($Month=='06') { echo "Selected";} ?>>Jun</option>
                            <option value="07" <?php if($Month=='07') { echo "Selected";} ?>>Jul</option>
                            <option value="08" <?php if($Month=='08') { echo "Selected";} ?>>Aug</option>
                            <option value="09" <?php if($Month=='09') { echo "Selected";} ?>>Sep</option>
                            <option value="10" <?php if($Month=='10') { echo "Selected";} ?>>Oct</option>
                            <option value="11" <?php if($Month=='11') { echo "Selected";} ?>>Nov</option>
                            <option value="12" <?php if($Month=='12') { echo "Selected";} ?>>Dec</option>
                        </select>
                    </div>
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select name="Year" id="Year" class="form-control" required="">
                            <option value="">YEAR</option>
                            <option value="2019" <?php if($Year=='2019') { echo "Selected";} ?>>2019</option>
                            <option value="2018" <?php if($Year=='2018') { echo "Selected";} ?>>2018</option>
                            <option value="2017" <?php if($Year=='2017') { echo "Selected";} ?>>2017</option>
                            <option value="2016" <?php if($Year=='2016') { echo "Selected";} ?>>2016</option>
                        </select>
                    </div>
                    
                    
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Employee Type</label>
                    <div class="col-sm-2">
                        <select name="EmpType" id="Type" class="form-control" required="">
                            <option value="">Select</option>
                            <option value="ALL" <?php if($EmpType=='ALL') { echo "Selected";} ?>>ALL</option>
                            <option value="OnSite" <?php if($EmpType=='OnSite') { echo "Selected";} ?>>On Site</option>
                            <option value="Field" <?php if($EmpType=='Field') { echo "Selected";} ?>>Field</option>
                            <option value="InHouse" <?php if($EmpType=='InHouse') { echo "Selected";} ?>>InHouse</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                           <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />  
                        
                        
                        <input type="submit" name="Submit"  value="Export" class="btn pull-right btn-primary btn-new"  style="margin-left: 5px;">
                        <input type="submit" name="Submit"  value="Show" class="btn pull-right btn-primary btn-new" >
                    </div>
                    <div class="col-sm-1">
                        
                    </div>
                    <div class="col-sm-1">
                   
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6" >
                        
                        <?php if(!empty($DataArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:50px;">SNo</th>
                                    <th style="text-align: center;width:150px;">BranchName</th>
                                    <?php if($Type=='CostCenter') { ?>
                                    <th style="text-align: center;">CostCenter</th>
                                    <?php } ?>
                                    <th style="text-align: center;">Opening</th>
                                    <th style="text-align: center;">Joined</th>
                                    <th style="text-align: center;">Left</th>
                                    <th style="text-align: center;">Closing</th>
                                    <th style="text-align: center;">Attrition</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; 
                                
                                $total_opens=0;
                                $total_joins=0;
                                $total_lefts=0;
                                $total_close=0;
                                $total_perse=0;
                                foreach ($DataAr as $dt){
                                    $opens=$dt['0']['opening'];
                                    $joins=$dt['0']['Joined'];
                                    $lefts=$dt['0']['LeftE'];
                                    $close=$dt['0']['Closing']-$dt['0']['LeftE'];
                                    $total=($close+$opens)/2;
                                    $perse=($lefts*100)/$total;
                                    
                                    $total_opens=$total_opens+$opens;
                                    $total_joins=$total_joins+$joins;
                                    $total_lefts=$total_lefts+$lefts;
                                    $total_close=$total_close+$close;
                                    $total_perse=$total_perse+$perse;

                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $n++;?></td>
                                        <td style="text-align: center;"><?php echo $dt['jclr']['BranchName'];?></td>
                                        <?php if($Type=='CostCenter') { ?>
                                    <td style="text-align: center;"><?php echo $dt['jclr']['CostCenter'];?></td>
                                    <?php } ?>
                                        
                                        <td style="text-align: center;"><a href="#" onclick="get_details_source('<?php echo $dt['jclr']['BranchName']; ?>','<?php echo $dt['jclr']['CostCenter']; ?>','<?php echo $Date; ?>','<?php echo $Type; ?>','<?php echo $EmpType; ?>','opening')" ><?php echo $opens;?></a></td>
                                        <td style="text-align: center;"><a href="#" onclick="get_details_source('<?php echo $dt['jclr']['BranchName']; ?>','<?php echo $dt['jclr']['CostCenter']; ?>','<?php echo $Date; ?>','<?php echo $Type; ?>','<?php echo $EmpType; ?>','Joined')" ><?php echo $dt['0']['Joined'];?></a></td>
                                        <td style="text-align: center;"><a href="#" onclick="get_details_source('<?php echo $dt['jclr']['BranchName']; ?>','<?php echo $dt['jclr']['CostCenter']; ?>','<?php echo $Date; ?>','<?php echo $Type; ?>','<?php echo $EmpType; ?>','LeftE')" ><?php echo $lefts;?></a></td>
                                        <td style="text-align: center;"><a href="#" onclick="get_details_source('<?php echo $dt['jclr']['BranchName']; ?>','<?php echo $dt['jclr']['CostCenter']; ?>','<?php echo $Date; ?>','<?php echo $Type; ?>','<?php echo $EmpType; ?>','Closing')" ><?php echo $close;?></a></td>
                                        
                                        
                                        
                                        <td style="text-align: center;"><?php echo round($perse,2);?> %</td>
                                    </tr>
                                
                                <?php }?>
                                    <tr>
                                        <td style="text-align: center;width:50px;">Total</td>
                                        <td style="text-align: center;width:50px;"></td>
                                        <?php if($Type=='CostCenter') { ?>
                                        <td style="text-align: center;width:50px;"></td>
                                        <?php } ?>
                                        <td style="text-align: center;width:50px;"><?php echo $total_opens;?></td>
                                        <td style="text-align: center;width:50px;"><?php echo $total_joins;?></td>
                                        <td style="text-align: center;width:50px;"><?php echo $total_lefts;?></td>
                                        <td style="text-align: center;width:50px;"><?php echo $total_close;?></td>
                                        <td style="text-align: center;width:50px;"><?php echo round($total_perse,2);?> %</td>
                                    </tr>
                            </tbody>   
                        </table>
                        <?php } ?>
                        
                    </div>
                </div>
               <div class="form-group">
                   <div class="col-sm-10" style="overflow-y:auto;height: 250px;" id="SourceDetails">
                        
                        
                        
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

<script>
    function get_details_source(Branch,CostCenter,Date,Type,EmpType,status)
    {
        $.post("<?php echo $this->webroot;?>"+"EmployeeSourceMasters/show_detail_attr",
        {
            Branch: Branch,
            CostCenter:CostCenter,
            Date:Date,
            Type:Type,
            EmpType:EmpType,
            status:status
        },
        function(data,status){
           $('#SourceDetails').html(data); 
           //alert(data);
        });
    }
</script>

