<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?> 
<script type="text/javascript">
$(function () {
    $(".dateoption").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

window.onload = function() {
<?php foreach($assetData as $key=>$val){?>
getConfig('<?php echo $key;?>');
<?php }?>
sum_cost();
};

function sum_cost(){
    var a   =   parseFloat(($.trim($("#LaptopCost").val())).replace(/[^0-9\.]+/g,""));
    var a   =   isNaN(a)?0:a;
	
	var b   =   parseFloat(($.trim($("#ComputerCost").val())).replace(/[^0-9\.]+/g,""));
    var b   =   isNaN(b)?0:b;
	
	var c   =   parseFloat(($.trim($("#KeyboardCost").val())).replace(/[^0-9\.]+/g,""));
    var c   =   isNaN(c)?0:c;
	
	var d   =   parseFloat(($.trim($("#MouseCost").val())).replace(/[^0-9\.]+/g,""));
    var d   =   isNaN(d)?0:d;
	
	var e   =   parseFloat(($.trim($("#MonitorCost").val())).replace(/[^0-9\.]+/g,""));
    var e   =   isNaN(e)?0:e;
	
	var f   =   parseFloat(($.trim($("#DongleCost").val())).replace(/[^0-9\.]+/g,""));
    var f   =   isNaN(f)?0:f;
	
	var g   =   parseFloat(($.trim($("#MobileCost").val())).replace(/[^0-9\.]+/g,""));
    var g   =   isNaN(g)?0:g;
	
	var h   =   parseFloat(($.trim($("#SimCardCost").val())).replace(/[^0-9\.]+/g,""));
    var h   =   isNaN(h)?0:h;
	
	var i   =   parseFloat(($.trim($("#UPSCost").val())).replace(/[^0-9\.]+/g,""));
    var i   =   isNaN(i)?0:i;
	
    var n	=	(a+b+c+d+e+f+g+h+i);
   
    $("#TotalCost").val(n);
}


function getConfig(Assets){

	var Status	=	$("#"+Assets).is(":checked");
	
	if(Assets =="Laptop"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Coniguration").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Coniguration").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="Computer"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Coniguration").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Coniguration").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="Keyboard"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="Mouse"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="Monitor"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="UPS"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="Dongle"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="Mobile"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"ModelNo").required = true;
			document.getElementById(Assets+"SerialNo").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"ModelNo").required = false;
			document.getElementById(Assets+"SerialNo").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="SimCard"){
		
		if(Status==true){
			document.getElementById(Assets+"Vendor").required = true;
			document.getElementById(Assets+"AllocateDate").required = true;
			document.getElementById(Assets+"Type").required = true;
			document.getElementById(Assets+"Number").required = true;
			document.getElementById(Assets+"Limit").required = true;
			document.getElementById(Assets+"Cost").required = true;
		}
		else{
			document.getElementById(Assets+"Vendor").required = false;
			document.getElementById(Assets+"AllocateDate").required = false;
			document.getElementById(Assets+"Type").required = false;
			document.getElementById(Assets+"Number").required = false;
			document.getElementById(Assets+"Limit").required = false;
			document.getElementById(Assets+"Cost").required = false;
		}
		
	}
	else if(Assets =="Security"){
		
		if(Status==true){
		
			document.getElementById(Assets+"AccountHolder").required = true;
			document.getElementById(Assets+"BankName").required = true;
			document.getElementById(Assets+"AccountNo").required = true;
			document.getElementById(Assets+"ChequeNo").required = true;
			document.getElementById(Assets+"ChequeAmount").required = true;
		}
		else{
			document.getElementById(Assets+"AccountHolder").required = false;
			document.getElementById(Assets+"BankName").required = false;
			document.getElementById(Assets+"AccountNo").required = false;
			document.getElementById(Assets+"ChequeNo").required = false;
			document.getElementById(Assets+"ChequeAmount").required = false;
		}
		
	}
	
	sum_cost();
}

function receive(Assets){
	
	var Status	=	$("#"+Assets+"Receive").is(":checked");
	
	if(Status==true){
			
		$("#"+Assets+"AllocateDiv").hide();
		$("#"+Assets+"ReceiveDiv").show();
		$("#"+Assets).prop('checked', false);
		
		document.getElementById(Assets+"ReceiveDate").required = true;
		document.getElementById(Assets+"ReceiveBy").required = true;
		document.getElementById(Assets+"Remarks").required = true;
	}
	else{
		$("#"+Assets+"ReceiveDiv").hide();
		$("#"+Assets+"AllocateDiv").show();
		$("#"+Assets).prop('checked', true);
		document.getElementById(Assets+"ReceiveDate").required = false;
		document.getElementById(Assets+"ReceiveBy").required = false;
		document.getElementById(Assets+"Remarks").required = false;
		
	}
}


function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
}

</script>
<style>
select{
	height: 23px;
}
#SimCardType{
	width:126px;
}
#SecurityAccountHolder{
	width:204px;
}
#TotalCost{
	margin-left: 32px;
	width: 113px;
}

</style>
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
                    <span>ASSET ALLOTMENTS / RECEIVE</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a class="expand-link"><i class="fa fa-expand"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
				<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
			
				<?php echo $this->Form->create('Assetallotments',array('action'=>'viewdetails','class'=>'form-horizontal')); ?>
				<?php echo $this->Session->flash(); ?>

				<div class="form-group">
					<div class="col-sm-1 pull-left" style="font-weight:bold;">Assets</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:-20px;">Allocate</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:15px;">Receive</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:17px;">Vendor</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:15px;">Date</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:32px;">Model No</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:15px;">Serial No</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:23px;">Configuration</div>
					<div class="col-sm-1 pull-left" style="font-weight:bold;margin-left:55px;">Cost</div>
				</div>
				
				
				
				<?php foreach($assetList as $assets){ ?>
				
				<div class="form-group form-controll"  >
					<div class="col-sm-1 pull-left"><?php echo $assets;?></div>
					<div class="col-sm-1 pull-left">
						<input type="checkbox" name="assets[<?php echo $assets;?>]" id="<?php echo $assets;?>" onClick="getConfig('<?php echo $assets;?>')" <?php echo in_array($assets, $AssetsExist)?'checked':'';?> value="Allocate" >
					</div>
					
					<div class="col-sm-1 pull-left" >
						<?php if(in_array($assets, $AssetsExist)){?>
						<input type="checkbox" name="assets[<?php echo $assets;?>]" id="<?php echo $assets;?>Receive" onClick="receive('<?php echo $assets;?>')" value="Receive" style="margin-left:10px;"  >
						<?php }?>
					</div>
					
					<div class="col-sm-9 pull-left" id="<?php echo $assets;?>AllocateDiv">
						
						<?php if($assets =="SimCard"){?>

						<select name="<?php echo $assets;?>Vendor" id="<?php echo $assets;?>Vendor"   autocomplete="off"  >
							<option value="">Select</option>
							<?php foreach($vendorList1 as $list){?>
							<option <?php echo $assetData[$assets]['Vendor'] ==$list?'selected="selected"':'';?> value="<?php echo $list;?>"><?php echo $list;?></option>
							<?php }?>
							
						</select>
						
						<input type="text" name="<?php echo $assets;?>AllocateDate" id="<?php echo $assets;?>AllocateDate" value="<?php echo $assetData[$assets]['AllocateDate'];?>" class="dateoption"	autocomplete="off"	placeholder="Date" 			 >
						<select name="<?php echo $assets;?>Type" id="<?php echo $assets;?>Type"  autocomplete="off"  >
							<option value="">Select</option>
							<option <?php echo $assetData[$assets]['SimCardType'] =="Prepaid"?'selected="selected"':'';?> value="Prepaid">Prepaid</option>
							<option <?php echo $assetData[$assets]['SimCardType'] =="Postpaid"?'selected="selected"':'';?> value="Postpaid">Postpaid</option>
						</select> 
						
						
						<input type="text" name="<?php echo $assets;?>Number" id="<?php echo $assets;?>Number"	value="<?php echo $assetData[$assets]['SimCardNumber'];?>"	autocomplete="off" placeholder="Number"	 > 
						<input type="text" name="<?php echo $assets;?>Limit"  id="<?php echo $assets;?>Limit"  	value="<?php echo $assetData[$assets]['SimCardLimit'];?>"  	autocomplete="off" placeholder="Limit"	 >  
						<input type="text" name="<?php echo $assets;?>Cost"   id="<?php echo $assets;?>Cost"  	value="<?php echo $assetData[$assets]['SimCardCost'];?>"  	autocomplete="off" placeholder="Cost" 		onkeyup="sum_cost()" onkeypress="return isNumberDecimalKey(event,this)">
						
						<?php }else if($assets =="Security"){?>
	
						<input type="text" name="<?php echo $assets;?>AccountHolder" 	id="<?php echo $assets;?>AccountHolder" value="<?php echo $assetData[$assets]['AccountHolder'];?>" autocomplete="off"  placeholder="Account Holder"	 > 
						<input type="text" name="<?php echo $assets;?>BankName"			id="<?php echo $assets;?>BankName"  	value="<?php echo $assetData[$assets]['BankName'];?>" autocomplete="off" 	placeholder="Bank Name" 		 > 
						<input type="text" name="<?php echo $assets;?>AccountNo"		id="<?php echo $assets;?>AccountNo"   	value="<?php echo $assetData[$assets]['AccountNo'];?>" autocomplete="off" 	placeholder="Account No" 		 > 
						<input type="text" name="<?php echo $assets;?>ChequeNo"			id="<?php echo $assets;?>ChequeNo"  	value="<?php echo $assetData[$assets]['ChequeNo'];?>" autocomplete="off" 	placeholder="Cheque No" 		 >  
						<input type="text" name="<?php echo $assets;?>ChequeAmount"		id="<?php echo $assets;?>ChequeAmount"  value="<?php echo $assetData[$assets]['ChequeAmount'];?>" autocomplete="off" 	placeholder="Cheque Amount" 	 >
						
						<?php }else{?>
						
						<select name="<?php echo $assets;?>Vendor" id="<?php echo $assets;?>Vendor"   autocomplete="off"  >
							<option value="">Select</option>
							<?php foreach($vendorList as $list){?>
							<option <?php echo $assetData[$assets]['Vendor'] ==$list?'selected="selected"':'';?> value="<?php echo $list;?>"><?php echo $list;?></option>
							<?php }?>
						</select>
						
						<input type="text" name="<?php echo $assets;?>AllocateDate" id="<?php echo $assets;?>AllocateDate"  value="<?php echo $assetData[$assets]['AllocateDate'];?>" class="dateoption"	autocomplete="off"	placeholder="Date" 			 >
						<input type="text" name="<?php echo $assets;?>ModelNo" 		id="<?php echo $assets;?>ModelNo"   	value="<?php echo $assetData[$assets]['ModelNo'];?>"					autocomplete="off" 	placeholder="Model No"  	 >
						<input type="text" name="<?php echo $assets;?>SerialNo" 	id="<?php echo $assets;?>SerialNo"  	value="<?php echo $assetData[$assets]['SerialNo'];?>"					autocomplete="off" 	placeholder="Serial No" 	 >
						<input type="text" name="<?php echo $assets;?>Coniguration" id="<?php echo $assets;?>Coniguration"  value="<?php echo $assetData[$assets]['Coniguration'];?>" 					autocomplete="off" 	placeholder="Configuration" 	 >
						<input type="text" name="<?php echo $assets;?>Cost" 		id="<?php echo $assets;?>Cost"  		value="<?php echo $assetData[$assets]['Cost'];?>"					autocomplete="off" 	placeholder="Cost" 				onkeyup="sum_cost()" onkeypress="return isNumberDecimalKey(event,this)">
						
						
						<?php }?>
					</div>
					
					<div class="col-sm-9 pull-left" id="<?php echo $assets;?>ReceiveDiv" style="display:none;" >
						<input type="text" name="<?php echo $assets;?>ReceiveDate" id="<?php echo $assets;?>ReceiveDate" value="<?php echo $assetData[$assets]['ReceiveDate'];?>" class="dateoption"	autocomplete="off"	placeholder="Date"style="width:75px;" 			 >
						<input type="text" name="<?php echo $assets;?>ReceiveBy" id="<?php echo $assets;?>ReceiveBy"  value="<?php echo $assetData[$assets]['ReceiveBy'];?>" 					autocomplete="off" 	placeholder="Receive By"  style="width:255px;"	 >
						<input type="text" name="<?php echo $assets;?>Remarks" 		id="<?php echo $assets;?>Remarks"  		value="<?php echo $assetData[$assets]['Remarks'];?>"					autocomplete="off" 	placeholder="Remarks" style="width:385px;" >
					</div>
					
				</div>
				
				<?php }?>
				
				
				<div class="form-group">
					<div class="col-sm-3 pull-left">Total Cost <input type="text" name="TotalCost"  id="TotalCost" value="<?php echo $assetData['TotalCost'];?>"    autocomplete="off" readonly ></div>
					<div class="col-sm-8">
						<input type="hidden" name="EmpCode" value="<?php echo base64_decode($_REQUEST['EC']);?>" >
						<input type="submit" name="Assets"  value="Submit" class="btn pull-right btn-primary btn-new">
					</div>
				</div>
					
				<?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



