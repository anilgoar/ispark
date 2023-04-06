    <?php// print_r($Jclr);die; ?>

<?php ?>

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
    <h4 class="page-header textClass"> mas Code:<?php echo $Jclr['Jclr']['EmpCode']; ?> </h4>

    <?php echo $this->Form->create('Jclr1',array('class'=>'form-horizontal','action'=>'detailsjclr')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Emp Name:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['EmapName']; ?>
                
                
            </div>    
        </div>
        <label class="col-sm-2 control-label">Father Name:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $Jclr['Jclr']['FatherName']; ?>
                
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Department:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $Jclr['Jclr']['Dept']; ?>
                
            </div>    
        </div>
        <label class="col-sm-2 control-label">Desgination:</label>
        <div class="col-sm-3">
            <div class="input-group">
              <?php echo $Jclr['Jclr']['Desg']; ?>
                
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">DOJ:</label>
        <div class="col-sm-3">
           <div class="input-group">
                <?php	echo $Jclr['Jclr']['DOFJ']; ?>

                
        </div></div>
        
        
        <label class="col-sm-2 control-label">DOB:</label>
        <div class="col-sm-3">
           <div class="input-group">
                <?php	echo $Jclr['Jclr']['DOB']; ?>

                
        </div></div>
         </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Basic:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['Basic']; ?>
                
            </div>
        </div>
        
    
        <label class="col-sm-2 control-label">HRA:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['HRA']; ?>

                
            </div>
        </div>
        </div>
	
	    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Conveince:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['Conv']; ?>
                
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Oth Allw:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['OthAllw']; ?>

                
            </div>
        </div>
         </div>
    


    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Gross:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['Gross']; ?>
                
            </div>
        </div>
   
        <label class="col-sm-2 control-label">PF:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['PF']; ?>

                
            </div>
        </div> </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">ESI:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['ESI']; ?>
                
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Total Deduction:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['TotalDed']; ?>

                
            </div>
        </div> </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Netpay:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['Netpay']; ?>
                
            </div>
        </div>
    
        <label class="col-sm-2 control-label">EmplrPF:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['EmplrPF']; ?>

                
            </div>
        </div></div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">EmplrESI:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['EmplrESI']; ?>
                
            </div>
        </div>
   
        <label class="col-sm-2 control-label">EmplrIns:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['EmplrIns']; ?>

                
            </div>
        </div> </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">CTC:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['CTC']; ?>
                
            </div>
        </div>
    
        <label class="col-sm-2 control-label">ESIS: Yes/No</label>
        <div class="col-sm-3">
            <div class="input-group">
               
                <?php echo $Jclr['Jclr']['ESIS']; ?>

                
            </div>
        </div></div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">PFS:</label>
        <div class="col-sm-3">
            <div class="input-group">
                
                <?php echo $Jclr['Jclr']['PFS']; ?>
                
            </div>
        </div>
   
        <label class="col-sm-2 control-label">ESINo:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['ESINo']; ?>

                
            </div>
        </div> </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">UAN:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['UAN']; ?>
                
            </div>
        </div>
  
        <label class="col-sm-2 control-label">PFNo:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['PFNo']; ?>

                
            </div>
        </div>  </div>
    
   <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Bank:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['Bank']; ?>
                
            </div>
        </div>
  
        <label class="col-sm-2 control-label">A/c No:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['AcNo']; ?>

                
            </div>
        </div>  </div>
    
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">IFSC:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['IFSC']; ?>
                
            </div>
        </div>
  
        <label class="col-sm-2 control-label">ACType:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['ACType']; ?>

                
            </div>
        </div>  </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">BankBranch:</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr['Jclr']['BankBranch']; ?>
                
            </div>
        </div>
  
         </div>
    
    <div class="clearfix"></div>
    
    <?php echo $this->Form->end(); ?>
</div>
<?php if($Jclr['Jclr']['Status'] ==1){ ?>
 <?php echo $this->Form->create('Jclr',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>
                                 Left Employee</span>
		</div>
		
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
 <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Resignation Date</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php	echo $this->Form->input('Resignation', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Resignation Date','onClick'=>"displayDatePicker('data[Jclr][Resignation]');",'value'=>'')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Authentication Code</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Authentication', array('label'=>false,'class'=>'form-control','placeholder'=>'Authentication.','value'=>'')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div> </div>
    <div class="form-group has-info has-feedback">
       <label class="col-sm-2 control-label">Reason</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('Reason', array('label'=>false,'class'=>'form-control','placeholder'=>'Reason','value'=>'')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
       
    </div>
 
 
 
 
                <div class="form-group has-success has-feedback">
               
                    
                   
                
		<div class="clearfix"></div>
		<div class="form-group">
                   <div class="col-sm-2">
                       <button type="Jclr1" class="btn btn-primary btn-label-left"  id="submit">
                            save
			</button>
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); } else {
    

?>
    
    <div class="box-header">
                <div class="box-name">
                    
                    <span>
                                  Employee is Already Left and Left Date is <?php Echo $Jclr['Jclr']['Resignation'];?> </span>
		</div>
		
		<div class="no-move"></div>
            </div>
    
<?php } ?>
