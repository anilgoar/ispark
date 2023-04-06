    <?php// print_r($Jclr);die; ?>

<?php ?>
<script>
function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        
            else{
              
                 return true; 
           
           
        }
        }
	
</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
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


<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">JCLR Entry <?php echo $this->Session->flash(); ?></h4>

    <?php echo $this->Form->create('Jclr',array('class'=>'form-horizontal','action'=>'update_cost')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Emp Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('EmapName', array('label'=>false,'class'=>'form-control','placeholder'=>'Emp Name','value'=>$Jclr['Jclr']['EmapName'],'required'=>true)); ?>
                <?php	echo $this->Form->input('EmpCode', array('label'=>false,'type'=>'hide','class'=>'form-control','placeholder'=>'Emp Name','value'=>$Jclr['Jclr']['EmpCode'],'required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Father Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('FatherName', array('label'=>false,'class'=>'form-control','value'=>$Jclr['Jclr']['FatherName'],'placeholder'=>'Father Name','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Department</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('Dept',array('label' => false,'options'=>array('VALIDATION'=>'VALIDATION','VALIDATION FIELD'=>'VALIDATION FIELD','VALIDATION QUALITY'=>'VALIDATION QUALITY','VODAFONE NODAL HELPDESK'=>'VODAFONE NODAL HELPDESK','VODAFONE PNG'=>'VODAFONE PNG','VODAFONE QUALITY'=>'VODAFONE QUALITY','VODAFONE QUALITY (CSO)'=>'VODAFONE QUALITY (CSO)','VODAFONE QUALITY CNC'=>'VODAFONE QUALITY CNC','VODAFONE SALES-QUALITY'=>'VODAFONE SALES-QUALITY'),'value'=>$Jclr['Jclr']['Dept'],'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Desgination</label>
        <div class="col-sm-3">
            <div class="input-group">
              <?php echo $this->Form->input('Desg',array('label' => false,'options'=>array('SR TEAM LEADER'=>'SR TEAM LEADER','SR QUALITY ANALYST'=>'SR QUALITY ANALYST','QUALITY ANALYST'=>'QUALITY ANALYST','BACKEND EXECUTIVE'=>'BACKEND EXECUTIVE','CUST SERV EXE'=>'CUST SERV EXE','CUSTOMER SUPPORT EXECUTIVE'=>'CUSTOMER SUPPORT EXECUTIVE','DST EXECUTIVE'=>'DST EXECUTIVE','FIELD EXECUTIVE'=>'FIELD EXECUTIVE','FIELD OFFICER'=>'FIELD OFFICER','MIS EXECUTIVE'=>'MIS EXECUTIVE','QUALITY AUDITOR'=>'QUALITY AUDITOR','SR EXECUTIVE'=>'SR EXECUTIVE','SR QUALITY AUDITOR'=>'SR QUALITY AUDITOR','STORE BACKEND EXECUTIVE'=>'STORE BACKEND EXECUTIVE','DIRECT SALES EXECUTIVE'=>'DIRECT SALES EXECUTIVE','TEAM LEADER'=>'TEAM LEADER'),'class'=>'form-control','empty'=>'Select','value'=>$Jclr['Jclr']['Desg'],'id'=>'finance_year')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">DOJ</label>
        <div class="col-sm-3">
           <div class="input-group">
                <?php	echo $this->Form->input('DOFJ', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'DOJ','onClick'=>"displayDatePicker('data[Jclr][DOFJ]');",'value'=>$Jclr['Jclr']['DOFJ'],'required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
        
        
        <label class="col-sm-2 control-label">DOB</label>
        <div class="col-sm-3">
           <div class="input-group">
                <?php	echo $this->Form->input('DOB', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'DOB','onClick'=>"displayDatePicker('data[Jclr][DOB]');",'value'=>$Jclr['Jclr']['DOB'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
         </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Basic</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Basic', array('label'=>false,'class'=>'form-control','onKeyPress'=>'return checkNumber(this.value,event)','placeholder'=>'Basic','value'=>$Jclr['Jclr']['Basic'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
        
    
        <label class="col-sm-2 control-label">HRA</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('HRA', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'HRA','value'=>$Jclr['Jclr']['HRA'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
        </div>
	
	    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Conveince</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Conv', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'Conv','value'=>$Jclr['Jclr']['Conv'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Oth Allw</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('OthAllw', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'Oth Allw','value'=>$Jclr['Jclr']['OthAllw'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
         </div>
    


    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Gross</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Gross', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'Gross','value'=>$Jclr['Jclr']['Gross'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">PF</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('PF', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'PF','value'=>$Jclr['Jclr']['PF'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div> </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">ESI</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('ESI', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'ESI','value'=>$Jclr['Jclr']['ESI'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Total Deduction</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('TotalDed', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'Total Ded.','value'=>$Jclr['Jclr']['TotalDed'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div> </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Netpay</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Netpay', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'Netpay','value'=>$Jclr['Jclr']['Netpay'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    
        <label class="col-sm-2 control-label">EmplrPF</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('EmplrPF', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'EmplrPF.','value'=>$Jclr['Jclr']['EmplrPF'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div></div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">EmplrESI</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('EmplrESI', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'EmplrESI','value'=>$Jclr['Jclr']['EmplrESI'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">EmplrIns</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('EmplrIns', array('label'=>false,'class'=>'form-control','onKeyPress'=>'return checkNumber(this.value,event)','placeholder'=>'EmplrIns.','value'=>$Jclr['Jclr']['EmplrIns'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div> </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">CTC</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('CTC', array('label'=>false,'onKeyPress'=>'return checkNumber(this.value,event)','class'=>'form-control','placeholder'=>'CTC','value'=>$Jclr['Jclr']['CTC'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    
        <label class="col-sm-2 control-label">ESIS: Yes/No</label>
        <div class="col-sm-3">
            <div class="input-group">
               
                <?php echo $this->Form->input('ESIS',array('label' => false,'options'=>array('Yes'=>'Yes','No'=>'No'),'value'=>$Jclr['Jclr']['ESIS'],'class'=>'form-control','empty'=>'Select','id'=>'ESIS')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div></div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">PFS</label>
        <div class="col-sm-3">
            <div class="input-group">
                
                <?php echo $this->Form->input('PFS',array('label' => false,'options'=>array('Yes'=>'Yes','No'=>'No'),'value'=>$Jclr['Jclr']['PFS'],'class'=>'form-control','empty'=>'Select','id'=>'PFS')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">ESINo</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('ESINo', array('label'=>false,'class'=>'form-control','placeholder'=>'ESINo.','value'=>$Jclr['Jclr']['ESINo'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div> </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">UAN</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('UAN', array('label'=>false,'class'=>'form-control','placeholder'=>'UAN','value'=>$Jclr['Jclr']['UAN'])); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
  
        <label class="col-sm-2 control-label">PFNo</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('PFNo', array('label'=>false,'class'=>'form-control','placeholder'=>'PFNo.','value'=>$Jclr['Jclr']['PFNo'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>  </div>
    
    <div class="form-group has-info has-feedback">
        <div class="col-sm-3">
            <div class="input-group">
                <input type='submit' class="btn btn-info" value="Save">
            </div>
        </div>
        
    </div>
    
    <div class="clearfix"></div>
    
    <?php echo $this->Form->end(); ?>
</div>