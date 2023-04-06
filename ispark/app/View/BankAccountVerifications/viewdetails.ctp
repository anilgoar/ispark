<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 
    && (charCode < 48 || charCode > 57))
     return false;

    return true;
}

function isAplha(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode == 32 || charCode == 46 || (charCode > 64 && charCode < 91) 
    || (charCode > 96 && charCode < 123))
    {
     return true;
    }
    return false;
}

function isAplhaNum(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if ( (charCode > 64 && charCode < 91) 
    || (charCode > 96 && charCode < 123) 
    || (charCode>47 && charCode<58))
    {
     return true;
    }
    return false;
}

function acc_verify()
{
    $("#msgerr").remove();
    var ben_name = "";
    var ben_phone = "";
    var bank_account = "";
    var ifsc = "";
    
    ben_name = $('#ben_name').val();
    ben_phone = $('#ben_phone').val();
    bank_account = $('#bank_account').val();
    ifsc = $('#ifsc').val();
    
     msg_hide = true;
    
    if(ben_name=='')
    {
        $("#ben_name").focus();
        $("#ben_name").after("<span id='msgerr' style='color:red;'>Please Fill Name</span>");
        return false;
    }
    else if(ben_phone=='')
    {
        $("#ben_phone").focus();
        $("#ben_phone").after("<span id='msgerr' style='color:red;'>Please Fill Phone No.</span>");
        return false;
    }
    else if(ben_phone.length!=10)
    {
        $("#ben_phone").focus();
        $("#ben_phone").after("<span id='msgerr' style='color:red;'>Please Fill Right Phone No.</span>");
        return false;
    }
    else if(bank_account=='')
    {
        $("#bank_account").focus();
        $("#bank_account").after("<span id='msgerr' style='color:red;'>Please Fill Bank Account</span>");
        return false;
    }
    else if(ifsc=='')
    {
        $("#ifsc").focus();
        $("#ifsc").after("<span id='msgerr' style='color:red;'>Please Fill IFSC</span>");
        return false;
    }
    else
    {
        $("#msgerr").remove();
        $.post('<?php echo $actual_link;?>/paypikm/bank-acc-verify-ajax',
        {
            ben_name:ben_name,
            ben_phone:ben_phone,
            bank_account:bank_account,
            ifsc:ifsc
        }, function(data){
            $("#respTrxn").html(data);
        });
        return false;
    }
   
    
}
</script> 

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 10px;
  height: 10px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
                    <span>Account Details</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a class="expand-link"><i class="fa fa-expand"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
				<div class="no-move"></div>
            </div>
            <div class="box-content box-con form-horizontal">
              
                <?php echo $this->Form->create('BankAccountVerifications',array('action'=>'viewdetails','return acc_verify()','class'=>'form-horizontal'));?>
					
						<?php echo $this->Session->flash();?>
						<div class="form-group">
							<div class="col-sm-1 pull-left" >Name</div>
							<div class="col-sm-3 pull-left">
								<input type="text" name="ben_name" onKeypress="return isAplha(event)" maxlength="100" id="ben_name" value="<?php echo isset($data['AccHolder'])?$data['AccHolder']:''?>" class="form-control" autocomplete="off" placeholder="Name" required="" readonly="" >
							</div>
							
							<div class="col-sm-1 pull-left" >Phone</div>
							<div class="col-sm-3 pull-left">
								<input type="text" name="ben_phone" onKeypress="return isNumberKey(event)" maxlength="10" id="ben_phone" value="<?php echo isset($data['Mobile'])?$data['Mobile']:''?>" class="form-control" autocomplete="off" placeholder="Phone" required="" readonly="" >
							</div>
							
							</div>
						
						<div class="form-group">
							
							<div class="col-sm-1 pull-left" >Bank&nbsp;Account</div>
							<div class="col-sm-3 pull-left">
								<input type="text" name="bank_account" onKeypress="return isNumberKey(event)" id="bank_account" value="<?php echo isset($data['AcNo'])?$data['AcNo']:''?>" class="form-control" autocomplete="off" placeholder="Bank Account" required="" readonly="" >
							</div>
							
							<div class="col-sm-1 pull-left" >IFSC</div>
							<div class="col-sm-3 pull-left">
								<input type="text" name="ifsc" maxlength="11" onKeypress="return isAplhaNum(event)" id="ifsc" value="<?php echo isset($data['IFSCCode'])?$data['IFSCCode']:''?>" class="form-control" autocomplete="off" placeholder="IFSC" required="" readonly="" >
							</div>
					
							<div class="col-sm-4 pull-left">
								<input type='hidden' name='EmpCode' value='<?php echo base64_decode($_REQUEST['EC']);?>' >
								<input type="submit" name="Submit"  value="Submit" style="width:80px;"  >
								<input type="button" name="Submit"  value="Back" style="width:80px;" onclick='return window.location="<?php echo $this->webroot;?>BankAccountVerifications"' >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-12">
								<div id="respTrxn"></div>
							</div>
						</div>
						
							<?php if(!empty($row)){?>
				

				<table class = "table table-striped table-hover  responstable">     
                <thead>
                    <tr>
                        <th style='text-align:center;'>EmpCode</th>
                        <th style='text-align:center;'>Name</th>
                        <th style='text-align:center;'>Phone</th>
                        <th style='text-align:center;'>Account</th>
                        <th style='text-align:center;'>IFSC</th>
                        <th style='text-align:center;'>Status</th>
                        <th style='text-align:center;'>Date</th>
                    </tr> 
                </thead>
                <tbody>
                    
                    <tr>
                        <td style='text-align:center;'><?php echo $row['emp_code']?></td>
						<td style='text-align:center;'><?php echo $row['ben_name']?></td>
                        <td style='text-align:center;'><?php echo $row['phone']?></td>
                        <td style='text-align:center;'><?php echo $row['acc_no']?></td>
                        <td style='text-align:center;'><?php echo $row['ifsc']?></td>
                        <td style='text-align:center;'><?php echo $row['cf_status']?></td>
                        <td style='text-align:center;'><?php echo $row['created_at']!=""?date("d-M-Y H:i:s"):""?></td> 
                    </tr>
                    
                </tbody>
            </table>
			
			<?php }?>
                   
                </div>
				
			
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



