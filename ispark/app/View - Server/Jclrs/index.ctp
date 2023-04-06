
<?php ?>
<script>
        function getData(val)
        {
            //alert(val);
            $.post("Jclrs/save_status",{desgn:val},function(data)
            {$("#mm").html(data);});
        }
        
        
        function getpackageData(val2)
        {
            var dept1 = $("#JclrDept").val();
            
            $.post("Jclrs/showpack",{pack:val2,dept:dept1},function(data)
            {$("#mn").html(data);});
        }
        
        
        
        
        
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

    <?php echo $this->Form->create('Jclr',array('class'=>'form-horizontal','action'=>'index')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Emp Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('EmapName', array('label'=>false,'class'=>'form-control','placeholder'=>'Emp Name','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Father Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('FatherName', array('label'=>false,'class'=>'form-control','placeholder'=>'Father Name','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Department</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('Dept',array('label' => false,'options'=>$package,'class'=>'form-control','empty'=>'Select','onchange'=>'getData(this.value);','id'=>'JclrDept')); ?>
              
            </div>    
        </div>
        <label class="col-sm-2 control-label">Desgination</label>
        <div class="col-sm-3">
            <div class="input-group">
              <?php echo $this->Form->input('Desg',array('label' => false,'options'=>array('SR TEAM LEADER'=>'SR TEAM LEADER','SR QUALITY ANALYST'=>'SR QUALITY ANALYST','QUALITY ANALYST'=>'QUALITY ANALYST','BACKEND EXECUTIVE'=>'BACKEND EXECUTIVE','CUST SERV EXE'=>'CUST SERV EXE','CUSTOMER SUPPORT EXECUTIVE'=>'CUSTOMER SUPPORT EXECUTIVE','DST EXECUTIVE'=>'DST EXECUTIVE','FIELD EXECUTIVE'=>'FIELD EXECUTIVE','FIELD OFFICER'=>'FIELD OFFICER','MIS EXECUTIVE'=>'MIS EXECUTIVE','QUALITY AUDITOR'=>'QUALITY AUDITOR','SR EXECUTIVE'=>'SR EXECUTIVE','SR QUALITY AUDITOR'=>'SR QUALITY AUDITOR','STORE BACKEND EXECUTIVE'=>'STORE BACKEND EXECUTIVE','DIRECT SALES EXECUTIVE'=>'DIRECT SALES EXECUTIVE','TEAM LEADER'=>'TEAM LEADER'),'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>
               
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Package</label>
        <div class="col-sm-3">
            <div id="mm" >
                <?php	echo $this->Form->input('CTC', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Package','required'=>true)); ?>

                
        </div></div>
        <label class="col-sm-2 control-label">Gender</label>
        <div class="col-sm-3">
           
                <?php	echo $this->Form->input('Gender', array('label'=>false,'options'=>array('Male'=>'Male','Female'=>'Female'),'class'=>'form-control','empty'=>'Select')); ?>
                
            
        </div>
    </div>
	
	    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Blood Group</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Blood', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Blood',)); ?>

               
            </div>
        </div>
        <label class="col-sm-2 control-label">Qualification</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Qualification', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Qualification',)); ?>
               
            </div>
        </div>
    </div>
    


    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">DOB</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('DOB', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'DOB','onClick'=>"displayDatePicker('data[Jclr][DOB]');",'required'=>true)); ?>

               
            </div>
        </div>
         <label class="col-sm-2 control-label">DOJ</label>
        <div class="col-sm-3">
           <div class="input-group">
                <?php	echo $this->Form->input('DOFJ', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'DOJ','onClick'=>"displayDatePicker('data[Jclr][DOFJ]');",'required'=>true)); ?>

                
        </div></div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Permanent Address</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('PerAdrress', array('type'=>'textarea','label'=>false,'class'=>'form-control','value'=>'')); ?>

               
            </div>
        </div>
        <label class="col-sm-2 control-label">Present address</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('PresAdrress', array('type'=>'textarea','label'=>false,'class'=>'form-control','value'=>'',)); ?>
                
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">City</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('perCity', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Permanent City',)); ?>

               
            </div>
        </div>
        <label class="col-sm-2 control-label">City</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('presCity', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'City',)); ?>
                
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">State</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('perState', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'State.',)); ?>

                
            </div>
        </div>
        <label class="col-sm-2 control-label">State</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('presState', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'State',)); ?>
               
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Pin Code</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('perPincode', array('label'=>false,'class'=>'form-control','onKeyPress'=>'return checkNumber(this.value,event)','value'=>'','placeholder'=>'Pin Code.',)); ?>

               
            </div>
        </div>
        <label class="col-sm-2 control-label">Pin Code</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('prespincode', array('label'=>false,'class'=>'form-control','onKeyPress'=>'return checkNumber(this.value,event)','value'=>'','placeholder'=>'Pin Code',)); ?>
               
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Mobile</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php	echo $this->Form->input('perMobile', array('label'=>false,'class'=>'form-control','onKeyPress'=>'return checkNumber(this.value,event)','value'=>'','placeholder'=>'Mobile',)); ?>

               
            </div>
        </div>
        <label class="col-sm-2 control-label">Mobile</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('presMobile', array('label'=>false,'class'=>'form-control','onKeyPress'=>'return checkNumber(this.value,event)','value'=>'','placeholder'=>'Mobile',)); ?>
               
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Pan No</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php	echo $this->Form->input('panno', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Pan No',)); ?>

               
            </div>
        </div>
        <label class="col-sm-2 control-label">Adhar No</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('adharno', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Adhar No',)); ?>
               
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Nominee Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php	echo $this->Form->input('NomineeName', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Nominee Name',)); ?>

               
            </div>
        </div>
        <label class="col-sm-2 control-label">Nominee DOB</label>
        <div class="col-sm-3">
            <div class="input-group">
               
                <?php	echo $this->Form->input('NomneeDOB', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'DOB','onClick'=>"displayDatePicker('data[Jclr][NomneeDOB]');",'required'=>true)); ?>
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Relation With Nominee</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php	echo $this->Form->input('RelationWithNomnee', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Relation With Nominee',)); ?>

               
            </div>
        </div></div>
    <div id ='mn'></div>
    <div class="form-group has-info has-feedback">
        <div class="col-sm-3">
            <div class="input-group">
                <input type='submit' class="btn btn-info" value="Save">
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    </div>

    <?php echo $this->Form->end(); ?>
