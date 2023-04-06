<?php

?>
 
<style>
    table td{margin: 5px;}
</style>

<?php echo $this->Form->create('Save',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Save Status</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
<div class="form-group has-success has-feedback">
  
  <label class="col-sm-2 control-label">Employee Code</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('EmapCode', array('label'=>false,'class'=>'form-control','placeholder'=>'EmapCode')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
 
 </div>
 
 
 
 
               
                    
                   
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label"></label> 
                    <div class="col-sm-2">
                        <button type="Save" class="btn btn-info">Submit</button>
                       
                    </div>  
                    
                    <label class="col-sm-2 control-label"></label> 
                    <div class="col-sm-2">
                        
                    </div> 
                </div> 
                
                
		
                
       <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>



        
  

    <div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			
			<div class="box-content">
			
				<h4 class="page-header">File Details</h4>
                                <?php  foreach($find as $post):   $imagepath=$show.$post['Docfile']['filename']; ?>
				<img src=<?php echo "http://mascallnetnorth.in/ispark/". $imagepath;?> alt="BankCopyNotFound" width="900" height="300">
                                <?php  endforeach;?>
			</div>
		</div>
	</div>
</div> 
     <?php echo $this->Form->end(); ?>   
        
        <?php  if(!empty($Jclr)){ if(!empty($find)){ if($Jclr['Jclr']['AccountApprove']==0){ echo $this->Form->create('Jclr',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <div id="msg">
                    <span>
                        Bank Details</span></div>
		</div>
		
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
 <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">AcNo</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('AcNo', array('label'=>false,'required'=>true,'class'=>'form-control','placeholder'=>'AcNo','value'=>$Jclr['Jclr']['AcNo'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Bank</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Bank', array('label'=>false,'required'=>true,'class'=>'form-control','placeholder'=>'Bank.','value'=>$Jclr['Jclr']['Bank'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div> </div>
    <div class="form-group has-info has-feedback">
       <label class="col-sm-2 control-label">IFSC</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('IFSC', array('label'=>false,'required'=>true,'class'=>'form-control','placeholder'=>'IFSC Code','value'=>$Jclr['Jclr']['IFSC'])); ?>
 <?php	echo $this->Form->input('EmapCode', array('type' =>'hidden','label'=>false,'value'=>$emp,'class'=>'form-control','placeholder'=>'EmapCode')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
       <label class="col-sm-2 control-label">A/c Type</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('ACType', array('label'=>false,'required'=>true,'options'=>array('Saving'=>'Saving','Current'=>'Current'),'class'=>'form-control','empty'=>'Type','value'=>$Jclr['Jclr']['ACType'])); ?>
 
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
    </div>
 
 
 
 <div class="form-group has-info has-feedback">
  
 <label class="col-sm-2 control-label">Branch Name</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('BankBranch', array('label'=>false,'class'=>'form-control','required'=>true,'placeholder'=>'Type','value'=>$Jclr['Jclr']['BankBranch'])); ?>
 
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
    
 <label class="col-sm-2 control-label">Remark</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('Remark', array('label'=>false,'type'=>'textarea','required'=>true,'class'=>'form-control','placeholder'=>'Remarks','value'=>$Jclr['Jclr']['BankBranch'])); ?>
 
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>


 </div>
                
                <div class="form-group has-success has-feedback">
                    <div class="form-group">
                   <div class="col-sm-4">
                       <div style="margin-left:40px;"><input type="radio" name="action" value="1"> <span style="color:green;">Approve</span></div><br>
                       <div style="margin-left:40px;"> <input type="radio" name="action" value="2" ><span style="color:red;"> DisApprove</span></div>
                    
                   </div></div></div>      
                <div class="clearfix"></div>
		<div class="form-group has-success has-feedback">
		<div class="form-group">
                   <div class="col-sm-4">
                       <button type="Jclr" class="btn btn-primary btn-label-left" onclick="return send();"id="Approve">
                            Action
			</button>
                    
                
                      
                   </div>
		</div>
            </div>
        </div>
    </div>
</div>
        <?php echo $this->Form->end(); } else{ ?>
    
    <div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			
			<div class="box-content">
			
				<h4 class="page-header">Account Details is Already Validate.</h4>
                                
			</div>
		</div>
	</div>
</div> 
        <?php }} else{ ?>
    
    <div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			
			<div class="box-content">
			
				<h4 class="page-header">File Not Found.</h4>
                                
			</div>
		</div>
	</div>
</div> 
        <?php }} ?>
    
   
    <script>
        function send() {
                var genders = document.getElementsByName("action");
                if (genders[0].checked == true) {
                    confirm("Are You Sure For Approval");
                } else if (genders[1].checked == true) {
                    confirm("Are You Sure For DissApprove");
                } else {
                    // no checked
                    var msg = '<span style="color:red;">You must select One Radio Button Approve or DisApprove!</span><br /><br />';
                    document.getElementById('msg').innerHTML = msg;
                    return false;
                }
                return true;
            }

            function reset_msg() {
                document.getElementById('msg').innerHTML = '';
            }
        </script>