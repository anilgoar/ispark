


<script>

   
  $( function() {
  $('#level_"<?php $row['tbl_ExpenseUnitMaster']['ExpenseUnitId'] ?>"').click( function() {
      $(this).toggleClass('on');
} )
});    

</script>



<script>
    
       

    
    
    
    
 function GetExpenseSubHeading(ExpenseHead){
      $.post("<?php echo $this->webroot;?>GRNs/getexpencesub",{ExpenseHead:ExpenseHead},function(data){
        	$('#GRNExpenseSubHeadingMaster').html(data);}
		);
         var ExpenseSubHead= document.getElementById("GRNExpenseSubHeadingMaster").value='';
       //var ExpenseSubHead = $('#GRNExpenseSubHeadingMaster').val()='';
  //alert('ExpenseSubHead');
        //var ExpenseSubHead = $('#GRNExpenseSubHeadingMaster').val();
        if(ExpenseSubHead ==''){
        $("#dCostCenter").html("");
        $("#dExpenseUnit").html("");
    // $("#dExpenseUnit").innerHTML == '';
     return true;
    }
        
        
  }
  
 function SetExpenseSubHeading(ExpenseSubHead){
     var branch = $('#GRNBranch').val();
     var Month = $('#GRNMonth').val();
     var Year = $('#GRNYear').val();
     var ExpenseHead = $('#GRNExpenseHeadingMaster').val();
     
     if(branch === ""){
      alert("Please Select Branch");
      return false;
  }
     if(Year === ""){
      alert("Please Select Year");
      return false;
  }
     if(Month === ""){
      alert("Please Select Month");
      return false;
  }
     if(ExpenseHead === ""){
      alert("Please Select Expense Head");
      return false;
  }
     if(ExpenseSubHead === ""){
      alert("Please Select Expense Sub Head");
      return false;
  }
  var ExpenseSubHead= document.getElementById("GRNExpenseSubHeadingMaster").value ;
  
  //alert(ExpenseSubHead);
        if(ExpenseSubHead ==''){
        
     document.getElementById("dCostCenter").innerHTML == ""; 
    }
      
  
        $.post("<?php echo $this->webroot;?>GRNs/setExpenseSub",{branch:branch,Month:Month,Year:Year,ExpenseHead:ExpenseHead,ExpenseSubHead:ExpenseSubHead},function(data){
        	$('#dGRNDetails').html(data);
            }
		);
setTimeout(function(){
        SetExpenseUnit();
        }, 1000);

    
    }
    
    function SetCostCenter(ExpenseUnit){
        
     var BusinessCaseStatus = $('#GRNBusinessCaseStatus').val();
       
        if(BusinessCaseStatus === "Business Case Found"){
     var branch = $('#GRNBranch').val();
     var Month = $('#GRNMonth').val();
     var Year = $('#GRNYear').val();
     var ExpenseHead = $('#GRNExpenseHeadingMaster').val();
     var ExpenseSubHead = $('#GRNExpenseSubHeadingMaster').val();
     var ExpenseUnitValue = "";
     if($('#GRN_' + ExpenseUnit).length){
     ExpenseUnitValue = $('#GRN_' + ExpenseUnit).val();
 }
     var PrevUnit = "";
     var PrevExpenseUnitValue = "";
     var CostCenterCount = "";
     var CostCenterAmountSum = 0;
     var CostCenterValues = new Array();
     CostCenterValues[0] = 0;
     
            if($('#GRNPrevUnit').length && $('#GRNCurrUnit').length){
                if($('#GRNCurrUnit').val() === ""){
                document.getElementById('GRNCurrUnit').value = ExpenseUnit;
            }
            else if($('#GRNCurrUnit').val() !== ExpenseUnit){
                document.getElementById('GRNPrevUnit').value  = $('#GRNCurrUnit').val();
                document.getElementById('GRNCurrUnit').value = ExpenseUnit; 
        }
    }

     if($('#GRNPrevUnit').length){
     PrevUnit = $('#GRNPrevUnit').val();
         if($('#GRN_' + PrevUnit).length){
     PrevExpenseUnitValue = $('#GRN_' + PrevUnit).val();
       }
   }
            if($('#GRNCostCenterCount').length){
     CostCenterCount = $('#GRNCostCenterCount').val();
     var i = 0;
//     if($('#GRN_' + PrevUnit).length){
     if($('#GRN_' + ExpenseUnit).length){
     for(i = 0; i< CostCenterCount; i++){
         CostCenterValues[i] = $('#GRNCC_' + i).val();
         CostCenterAmountSum = CostCenterAmountSum + parseFloat(CostCenterValues[i]);
            }
                     if(parseFloat(CostCenterAmountSum) != parseFloat(PrevExpenseUnitValue)){
                         alert("Mismatch in Expense Unit Amount and CostCenter Amount");
                         return false;
                     }
       }
            }
     $('#dShowGRNEntryDetails').show();
     if(ExpenseSubHead == ''){
         document.getElementById("dCostCenter").innerHTML == ""; 
     }
     else{
        $.post("<?php echo $this->webroot;?>GRNs/setCostCenter",{branch:branch,Month:Month,Year:Year,ExpenseHead:ExpenseHead,ExpenseSubHead:ExpenseSubHead,ExpenseUnit:ExpenseUnit,ExpenseUnitValue:ExpenseUnitValue,PrevUnit:PrevUnit,PrevExpenseUnitValue:PrevExpenseUnitValue,CostCenterCount:CostCenterCount,CostCenterValues:CostCenterValues},function(data){
                    $('#dCostCenter').show();
        	$('#dCostCenter').html(data);
            }
		);
    }
    
    }
  }

    function SetExpenseUnit(){
    var BusinessCaseStatus = $('#GRNBusinessCaseStatus').val();
     var branch = $('#GRNBranch').val();
     var Month = $('#GRNMonth').val();
     var Year = $('#GRNYear').val();
     var ExpenseHead = $('#GRNExpenseHeadingMaster').val();
     var ExpenseSubHead = $('#GRNExpenseSubHeadingMaster').val();
     var GRNType = document.getElementById('GRNGRNtype').value;

    if(BusinessCaseStatus === "Business Case Found"){
        
     $('#dShowGRNEntryDetails').show();
        $.post("<?php echo $this->webroot;?>GRNs/setExpenseUnit",{branch:branch,Month:Month,Year:Year,ExpenseHead:ExpenseHead,ExpenseSubHead:ExpenseSubHead},function(data){
        	$('#dExpenseUnit').html(data);
            }
		);
                    $('#dExpenseUnit').show();
            if(GRNType !== "Vendor"){
            $('#dShowVendor').hide();
        }
        else{
            $('#dShowVendor').show();
        }
    }
    else{
            $('#dShowGRNEntryDetails').hide();
    }
    
setTimeout(function(){
    if(document.getElementById("dExpenseUnit").innerHTML == "" || null)
    //if($('#dExpenseUnit').html() === "")
    {
            SetCostCenter("");
        }
        
        }, 1000);        
    }

function setGRNtype(GRNtype){
     $('#dShowVendor').hide();
     var branch = $('#GRNBranch').val();
     if(branch === ""){
      alert("Please Select Branch");
      return false;
  }
  
  if(GRNtype === "Vendor"){
    $.post("<?php echo $this->webroot;?>GRNs/setGRNtypeDesign",{GRNtype:GRNtype,branch:branch},
        function(data){	
            $('#dShowVendor').show();
            $('#GRNVendors').html(data);
        }               
    );	
   }
}

    function saveData(){
     var BusinessCaseStatus = $('#GRNBusinessCaseStatus').val();
        if(BusinessCaseStatus === "Business Case Found"){
     var branch = $('#GRNBranch').val();
     var Month = $('#GRNMonth').val();
     var Year = $('#GRNYear').val();
     var ExpenseHead = $('#GRNExpenseHeadingMaster').val();
     var ExpenseSubHead = $('#GRNExpenseSubHeadingMaster').val();
     var GRNType = document.getElementById('GRNGRNtype').value;
     var GRNAmount = $('#GRNAmount').val();
     var GRNDate = $('#GRNDate').val();
     var GRNStatus = document.getElementById('GRNGRNStatus').value;
     var GNVendors = "";
     var BillNumber = "";
     var BillDate = "";
     var GRNRemarks = $('#GRNRemarks').val();
     var ExpenseUnit = "";
     if($('#GRNCurrUnit').length){
        ExpenseUnit = $('#GRNCurrUnit').val();
 }
        //var ApprovedAmount = $('#GRNApprovedAmount').val();
        //var ConsumedAmount = $('#GRNConsumedAmount').val();
        var BalanceAmount = $('#GRNBalanceAmount').val();
     
        if(parseFloat(BalanceAmount) < parseFloat(GRNAmount)){
         alert("GRN Amount Cannot be more than Balance Amount");
         return;
        }
     
        if(GRNType === ""){
          GRNType = $('#GRNtype').val();
     alert("Please Select GRN Type");
     return;
        }
        
     if(GRNAmount === "" || parseFloat(GRNAmount) === 0){
         alert("Please Enter GRN Amount");
         return;
            }
     if(GRNDate === ""){
         alert("Please Enter GRN Date");
         return;
            }
     
     if(GRNType === "Vendor"){
     GNVendors = document.getElementById('GRNVendors').value;
     BillNumber = $('#BillNumber').val();
     BillDate = $('#BillDate').val();

        if(GNVendors === ""){
         alert("Please Select Vendor");
         return;
            }
        if(BillNumber === ""){
         alert("Please Enter Bill Number");
         return;
            }
        if(BillDate === ""){
         alert("Please Enter Bill Date");
         return;
            }
        }
    var CostCenterCount = "";
        var CostCenterAmountSum = 0;
     var CostCenterValues = new Array();
     CostCenterValues[0] = 0;
            if($('#GRNCostCenterCount').length){
     CostCenterCount = $('#GRNCostCenterCount').val();
     
     var i = 0;
     for(i = 0; i< CostCenterCount; i++){
         CostCenterValues[i] = $('#GRNCC_' + i).val();
         CostCenterAmountSum = CostCenterAmountSum + parseFloat(CostCenterValues[i]);
            }
       }
            $.post("<?php echo $this->webroot;?>GRNs/saveData",{branch:branch,Month:Month,Year:Year,ExpenseHead:ExpenseHead,ExpenseSubHead:ExpenseSubHead,ExpenseUnit:ExpenseUnit,CostCenterValues:CostCenterValues,GRNType:GRNType,GRNAmount:GRNAmount,GRNDate:GRNDate,GRNStatus:GRNStatus,GNVendors:GNVendors,BillNumber:BillNumber,BillDate:BillDate,GRNRemarks:GRNRemarks},
            function(data){
                    $('#dSubmitMsg').show();
        	$('#dSubmitMsg').html(data);
            }
            );
    }
  
  }
  
  function downloadData(){
     var branch = $('#GRNBranch').val();
     var Month = $('#GRNMonth').val();
     var Year = $('#GRNYear').val();

     if(branch === ""){
         alert("Please Select Branch.");
         return;
     }
     if(Year === ""){
         alert("Please Select Year.");
         return;
     }
     if(Month === ""){
         alert("Please Select Month.");
         return;
     }

            $.post("<?php echo $this->webroot;?>GRNs/downloadData",{branch:branch,Month:Month,Year:Year},
            function(data){
                    $('#dFinal').show();
        	$('#dFinal').html(data);
            }
            );
  
    }

  </script>


<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>

<div class="box-content">
				<h4 class="page-header">GRN Entry</h4>
				
					<span style="color:green"><?php echo $this->Session->flash(); ?></span>
					<?php echo $this->Form->create('GRN',array('class'=>'form-horizontal', 'default' => false)); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'empty'=>'Select Branch','required'=>true,'class'=>'form-control')); ?>
						</div>
						<label class="col-sm-2 control-label">GRN Type</label>
						<div class="col-sm-4">
                                                   <?php $grnType = array('Imprest'=>'Imprest','Vendor'=>'Vendor','Salary'=>'Salary'); ?>
							<?php echo $this->Form->input('GRNtype',array('label'=>false,'options'=>$grnType,'empty'=>'Select GRN Type','onChange'=>'setGRNtype(this.value)','required'=>false,'class'=>'form-control','multiple'=>false)); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                       <label class="col-sm-2 control-label">Year</label>
						<div class="col-sm-4">
												
                                                <?php $c= date('Y');
												 $year =array($c-1=>$c-1,$c=>$c,$c+1=>$c+1); ?>
					<?php echo $this->Form->input('Year',array('label'=>false,'options'=>$year,'empty'=>'Select Year','required'=>true,'class'=>'form-control','multiple'=>false )); ?>
                                        </div>

						<label class="col-sm-2 control-label">Month</label>
						<div class="col-sm-4">
                                    <?php $m = 1; $month = array($m=>date('F', mktime(0,0,0,$m)),$m+1=>date('F', mktime(0,0,0,$m+1)),$m+2=>date('F', mktime(0,0,0,$m+2)),$m+3=>date('F', mktime(0,0,0,$m+3)),$m+4=>date('F', mktime(0,0,0,$m+4)),$m+5=>date('F', mktime(0,0,0,$m+5)),$m+6=>date('F', mktime(0,0,0,$m+6)),$m+7=>date('F', mktime(0,0,0,$m+7)),$m+8=>date('F', mktime(0,0,0,$m+8)),$m+9=>date('F', mktime(0,0,0,$m+9)),$m+10=>date('F', mktime(0,0,0,$m+10)),$m+11=>date('F', mktime(0,0,0,$m+11))); ?>
						
					<?php echo $this->Form->input('Month',array('label'=>false,'options'=>$month,'empty'=>'Select Month','required'=>true,'class'=>'form-control','multiple'=>false )); ?>

						</div>


					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Expense Head</label>
						
						<div class="col-sm-4">
						
					<?php echo $this->Form->input('ExpenseHeadingMaster',array('label'=>false,'options'=>$expenseheadingmaster,'empty'=>'Select Expense Head','onChange'=>'GetExpenseSubHeading(this.value)','required'=>false,'class'=>'form-control','multiple'=>false )); ?>

						</div>
						

						<label class="col-sm-2 control-label">Expense Sub Head</label>
						<div class="col-sm-4"><div id = 'exsub'>
						<?php
						if(empty($expensesubheadingmaster))
						{
						$expensesubheadingmaster = '';
						}
						?>
					<?php echo $this->Form->input('ExpenseSubHeadingMaster',array('label'=>false,'options'=>$expensesubheadingmaster,'empty'=>'Select Expense Sub Head','onChange'=>'SetExpenseSubHeading(this.value)','required'=>false,'class'=>'form-control','multiple'=>false )); ?>

						</div></div>
                                    </div>

                                            <div id="dGRNDetails"> </div>
                                        
                                        <div id="dShowVendor" style="display:none">                                    

                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Vendor</label>
						<div class="col-sm-4">
                                            <?php
						if(empty($GRNVendors))
						{
						$GRNVendors = '';
						}
						?>
					<?php echo $this->Form->input('GRNVendors',array('label' => false,'options'=>$GRNVendors,'empty' => 'Select Vendors','id'=>'GRNVendors' ,'class'=>'form-control' ,'required'=>false)); ?>
						</div>
					</div>
                                            <div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label">Bill Number</label>
                                                <div class="col-sm-4">
                                          <?php echo $this->Form->input('BillNumber',array('label' => false,'value'=>'','id'=>'BillNumber' ,'class'=>'form-control' ,'required'=>false)); ?>  </div>
                                                <label class="col-sm-2 control-label">Bill Date</label>
                                                <div class="col-sm-4">
                                          <?php echo $this->Form->input('BillDate',array('label' => false,'id'=>'BillDate' ,'onClick'=>"displayDatePicker('data[GRN][BillDate]');",'class'=>'form-control','required'=>false)); ?> 
                                                </div>
                                            </div>
                                            
                                        </div>
                                         
                                            <div id="dShowGRNEntryDetails" style="display:none">                                    

                                            <div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label">Amount</label>
                                                <div class="col-sm-4">
                                          <?php echo $this->Form->input('GRNAmount',array('label' => false,'value'=>'','id'=>'GRNAmount' ,'class'=>'form-control' ,'required'=>false)); ?>  </div>
                                                <label class="col-sm-2 control-label">Date</label>
                                                <div class="col-sm-4">
                                          <?php echo $this->Form->input('GRNDate',array('label' => false,'id'=>'GRNDate' ,'onClick'=>"displayDatePicker('data[GRN][GRNDate]');",'class'=>'form-control','required'=>false)); ?> 
                                                </div>
                                            </div>
                                        <div class="form-group has-success has-feedback">
                                       <label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
												
                                                <?php $grn_status =array('Open'=>'Open','Close'=>'Close'); ?>
					<?php echo $this->Form->input('GRNStatus',array('label'=>false,'options'=>$grn_status,'required'=>false,'class'=>'form-control','multiple'=>false )); ?>
                                        </div>
						</div>

                                        <div class="form-group has-success has-feedback">
						<div class="col-sm-6">
                                                    <div id="dExpenseUnit" style="display:none"></div>
						</div>
						<div class="col-sm-6">
                                                    <div id="dCostCenter" style="display:none"></div>
						</div>
                                            
					</div>
                                                

                                        <div class="form-group has-success has-feedback">
						<div class="col-sm-6">
                                                    <div id="dSubmitMsg" style="display:none"></div>
						</div>
                                            </div>
                                            
                                        <div class="form-group has-success has-feedback">
                                       <label class="col-sm-2 control-label">Remarks</label>
						<div class="col-sm-10">
					<?php echo $this->Form->input('Remarks',array('label'=>false,'type' => 'textarea','class'=>'form-control')); ?>
                                                </div>
						<div class="col-sm-4">
                                         <?php echo $this->Form->button('Submit Details',array('id' => 'SaveData', 'onClick'=>'saveData();','class' => 'btn btn-primary btn-label-left')); ?>
                                        </div>
						</div>
                                                
                                            </div>
                                       
                                               
                                        <div class="form-group has-success has-feedback">
    				<div class="col-sm-4">
                                         <?php echo $this->Form->button('Download Data',array('id' => 'DownloadData', 'onClick'=>'downloadData();','class' => 'form-control')); ?>
					</div>
                                       </div>
                                            <div id ="dFinal"></div>
                                            
                                          <div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
						</div>
					</div>


				<?php echo $this->Form->end(); ?>


</div>
