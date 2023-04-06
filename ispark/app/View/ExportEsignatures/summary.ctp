<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
$(function (){
    $("#SalaryMonth").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function incentiveBreakupDetails(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    var SalaryMonth=$("#SalaryMonth").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select CostCenter.</span>");
        return false;
    }
    else if(SalaryMonth ===""){
        $("#SalaryMonth").focus();
        $("#SalaryMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select SalaryMonth.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>IncentiveUploadApproveReports/showbreakupdetails",{BranchName:BranchName,CostCenter:CostCenter,SalaryMonth:SalaryMonth}, function(data) {
            if(data !=""){
                $("#divBreakupDetails").html(data);
            }
            else{
                $("#divBreakupDetails").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
            }
        });
    }
    
}


function getBranch(BranchName){
    $.post("<?php echo $this->webroot;?>ExportEmployeeDetails/getcostcenter",{BranchName:BranchName}, function(data) {
        $("#CostCenter").html(data);
    });
}

function get_esin_details(branch_name){

	$.post("<?php echo $this->webroot;?>ExportEsignatures/get_esin_details",{branch_name:branch_name,type:'view'}, function(data) {
		$("#show_summary").click();
		$("#branchname").val(branch_name);
        $("#summary_details").html(data);
    });
}

function export_esin_details(){
	var branch_name			=	$("#branchname").val();
	window.location.href	=	"<?php echo $this->webroot;?>ExportEsignatures/get_esin_details?branch_name="+branch_name+"&type=export";
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
                    <span>E-Signature Summary</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('ExportEsignatures',array('action'=>'summary','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$branch,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
					
					<div class="col-sm-2">
                        <input onclick='return window.location="Menus?AX=MTUw"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <!--<input type="submit" name="submit" value="Export" class="btn pull-right btn-primary btn-new" style='margin-left:5px;'>-->
						<input type="submit" name="submit" value="View" class="btn pull-right btn-primary btn-new"  >
	
                    </div>
                </div>
			
                
                <?php echo $this->Session->flash(); ?>
				
				
                
                <?php echo $this->Form->end(); ?>
                
            </div>
        </div>
    </div>	
</div>

<p   data-toggle="modal" data-target="#myModal" id="show_summary" ></p>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90% !important;" >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Summary Details</h4>
      </div>
      <div class="modal-body form-horizontal">
        <div id="summary_details" style="overflow-y: scroll;height:350px;" ></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-default" onclick="export_esin_details()" >Export</button>
		<input type="hidden" id="branchname" >;
      </div>
    </div>

  </div>
</div>



