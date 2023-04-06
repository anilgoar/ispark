<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#InstallationDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
}

function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}
</script>
<style>
.form-group .form-control, .form-group .input-group {
    margin-bottom: -20px;
}
.form-horizontal .control-label {
    padding-top: 0px;
}
.control-label{
    font-size: 11px;
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
<div class="col-xs-12 col-sm-12">
    <div class="box" >
        <div class="box-header"  >
            <div class="box-name">
                <span>Sent Mail For GRN Payment</span>
            </div>
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
            <div class="no-move"></div>
        </div>
          
        <div class="box-content box-con">
            <div style="margin-left:0px;" ><?php echo $this->Session->flash(); ?></div>
            <?php if(isset($RowData['Id']) && $RowData['Id'] !=""){?>
            <?php echo $this->Form->create('AutomailMasters',array('class'=>'form-horizontal','action'=>'update','onSubmit'=>'return validateOdApply()')); ?>
            <?php }else{?>
            <?php echo $this->Form->create('AutomailMasters',array('class'=>'form-horizontal','action'=>'index','onSubmit'=>'return validateOdApply()')); ?>
            <?php }?>
        
   
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Branch&nbsp;Name</label>
                <div class="col-sm-5">
                    <select  name="data[AutomailMasters][Branch_Name]" id="Branch_Name" class="form-control" required="" >
                        <option value="">Select</option>
                        <?php foreach($branchName as $key=>$val){?>
                        <option <?php if(isset($RowData['Branch_Name']) && $RowData['Branch_Name']==$val){echo "selected='selected'";} ?> value="<?php echo $val;?>"><?php echo $val;?></option>
                        <?php }?>
                    </select>
               </div>
                
                <label for="pwd" class="control-label col-sm-1">Bill Type</label>
                <div class="col-sm-2">
                    <select  name="data[AutomailMasters][Bill_Type]" id="Bill_Type" class="form-control" required="" >
                        <option value="">Select</option>
                        <?php foreach($head as $key=>$val){?>
                        <option <?php if(isset($RowData['Bill_Type']) && $RowData['Bill_Type']==$key.'#####'.$val){echo "selected='selected'";} ?> value="<?php echo $key.'#####'.$val;?>"><?php echo $val;?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
			
		
			<div class="form-group">
				<label for="pwd" class="control-label col-sm-1">Email&nbsp;Id</label>
                <div class="col-sm-5">
					<textarea name="data[AutomailMasters][Email_Id]" id="Email_Id" placeholder="xyz@gmail.com,abc@gmail.com" class="form-control" required="" ><?php echo isset($RowData['Email_Id'])?$RowData['Email_Id']:"";?></textarea>
					<br/><br/><span>Note - User comma for multiple email id.</span>
                </div>
				
				
				<label for="pwd" class="control-label col-sm-1">Status</label>
                <div class="col-sm-2">
                    <select  name="data[AutomailMasters][Status]" id="Status" class="form-control" required="" >
						<option <?php if(isset($RowData['Status']) && $RowData['Status']=="Active"){echo "selected='selected'";} ?>  value="Active">Active</option>
					    <option <?php if(isset($RowData['Status']) && $RowData['Status']=="Dactive"){echo "selected='selected'";} ?>  value="Dactive">Dactive</option>
                    </select>
                </div>
				
            </div>
			
			
            <div class="form-group" style="margin-bottom:-10px;">
                <div class="col-sm-12 text-right">   
                    <?php if(isset($RowData['Id']) && $RowData['Id'] !=""){?>
                        <input type="hidden" name="data[AutomailMasters][Id]" id="Id" value="<?php echo isset($RowData['Id'])?$RowData['Id']:"";?>" class="form-control">
                        <a href="<?php echo $this->webroot;?>AutomailMasters"><button type="button" class="btn" style="background-color: #436e90;color:#FFF;" >Add New</button></a>
                        <button type="submit" class="btn" style="background-color: #436e90;color:#FFF;" >Update</button>
                        <?php }else{?>
                        <button type="submit" class="btn" style="background-color: #436e90;color:#FFF;">Submit</button>
						<a href="/ispark/FinanceReports" class="btn" style="background-color: #436e90;color:#FFF;">Back</a>
						
                    <?php } ?> 
                </div>   
            </div>
			
			<?php if(!empty($data)){?>
			
			<div class="box-content box-con table-responsive" style="overflow-x:auto;padding: 1px;" >
             <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">    
                <thead>
                    <tr style="background-color: #436e90;color:#FFF;">
						<th style="text-align: center;width:30px;">SrNo</th>
                        <th>Branch&nbsp;Name</th>
                        <th style="text-align: center;">Bill Type</th>
                        <th style="text-align: center;">Email&nbsp;Id</th>
                        <th style="text-align: center;">Status</th>
						<!--
                       <th style="text-align: center;">Create&nbsp;Date</th>
                       <th style="text-align: center;">Create&nbsp;By</th>
                       <th style="text-align: center;">Update&nbsp;Date</th>
                       <th style="text-align: center;">Update&nbsp;By</th>
					   -->
                       <th style="text-align: center;width:50px;">Action</th>
                    </tr>
                </thead>
                <tbody>         
                <?php $i=1; foreach($data as $row){
					?> 
                <tr>
					<td style="text-align: center;"><?php echo $i++;?></td>
                    <td><?php echo $row['AutomailGrnpaymentMaster']['Branch_Name']?></td>
                    <td style="text-align: center;"><?php echo end(explode("#####",$row['AutomailGrnpaymentMaster']['Bill_Type']))?></td>
                    <td style="text-align: center;"><?php echo $row['AutomailGrnpaymentMaster']['Email_Id']?></td>
                    <td><?php echo $row['AutomailGrnpaymentMaster']['Status']?></td>
					<!--
                    <td style="text-align: center;"><?php echo $row['AutomailGrnpaymentMaster']['Create_Date'] !=""?date('d-m-Y',strtotime($row['AutomailGrnpaymentMaster']['Create_Date'])):"";?></td>
                    <td style="text-align: center;"><?php echo $row['AutomailGrnpaymentMaster']['Create_By']?></td>           
                    <td style="text-align: center;"><?php echo $row['AutomailGrnpaymentMaster']['Update_Date'] !=""?date('d-m-Y',strtotime($row['AutomailGrnpaymentMaster']['Update_Date'])):"";?></td>
                    <td style="text-align: center;"><?php echo $row['AutomailGrnpaymentMaster']['Update_By']?></td>
					-->
                    <td style="text-align: center;">
                        <a href="<?php echo $this->webroot;?>AutomailMasters/index?Id=<?php echo $row['AutomailGrnpaymentMaster']['Id']?>"> <span class="fa fa-edit" style="font-size:15px;" ></span></a>
                        <a href="<?php echo $this->webroot;?>AutomailMasters/delete?Id=<?php echo $row['AutomailGrnpaymentMaster']['Id']?>" onclick="return confirm('Are you sure you want to delete this record?');" > <span class="fa fa-trash" style="font-size:15px;" ></span></a>
                    </td>
                </tr>
                <?php }?>
            </tbody>   
            </table>
        </div>
		<?php }?>
			
            <?php echo $this->Form->end(); ?>
        </div>
   </div>
</div>
        