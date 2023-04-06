
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
    <h4 class="page-header textClass">Connectivity Soft<?php echo $this->Session->flash(); ?></h4>

    <?php echo $this->Form->create('Connectivitie',array('class'=>'form-horizontal','action'=>'index')); ?>
    
     <?php
                         if(!empty($branchName))
                            {
                                
                                echo ' <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch</label>';
                                echo '<div class="col-sm-3">
            <div class="input-group">';
                                echo $this->Form->input('Branch',array('label'=>false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','required'=>true));
                                echo ' <span class="input-group-addon"><i class="fa fa-group"></i></span></div>    
        </div>
    </div>';
                        }
                        
                           
                        
                        ?>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Connectivity Type</label>
        <div class="col-sm-3">
            <div class="input-group">
                
                <?php echo $this->Form->input('ConnectivityType',array('label' => false,'options'=>array('ILL'=>'ILL','PRI'=>'PRI','SIM'=>'SIM','MPLS'=>'MPLS','NPLS'=>'NPLS','P2P'=>'P2P','BroadBand'=>'BroadBand'),'class'=>'form-control','empty'=>'Select','id'=>'ConnectivityType')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Consumer code</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Cunsumercode', array('label'=>false,'class'=>'form-control','placeholder'=>'Cunsumer code','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Relationship No</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('RelationshipNo', array('label'=>false,'class'=>'form-control','placeholder'=>'Relationship No','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Tariff Plan</label>
        <div class="col-sm-3">
            <div class="input-group">
              <?php	echo $this->Form->input('TariffPlan', array('label'=>false,'class'=>'form-control','placeholder'=>'Tariff Plan','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Billing to (address)</label>
        <div class="col-sm-3">
           <div class="input-group">
                <?php	echo $this->Form->input('BillingAddress', array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Billing to (address)','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
        <label class="col-sm-2 control-label">Billing Period</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('BillingPeriod', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Billing Period','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
	
	    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Billing Type</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('BillingType', array('label' => false,'options'=>array('Monthly'=>'Monthly','Quaterly'=>'Quaterly','yearly'=>'yearly'),'class'=>'form-control','empty'=>'Select','id'=>'BillingType')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">Bandwidth</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Bandwidth', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Bandwidth','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
    


    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Plan Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('PlanName', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Plan Name','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">Bill date</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Billdate', array('type'=>'text','label'=>false,'class'=>'form-control','value'=>'','onclick'=>"displayDatePicker('data[Connectivitie][Billdate]');",'placeholder'=>'Bill Date','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Bill Due date</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('BillDuedate', array('type'=>'text','label'=>false,'class'=>'form-control','value'=>'','onclick'=>"displayDatePicker('data[Connectivitie][BillDuedate]');",'placeholder'=>'Bill Due date','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">security deposit</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('securitydeposit', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'security deposit','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Contact Person</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('ContactPerson', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Contact Person','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">Mobile No</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('MobileNo', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Mobile No','onKeyPress'=>'return checkNumber(this.value,event)','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">User name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Username', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'User name.','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">Ownership</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Ownership', array('label' => false,'options'=>array('company'=>'company','individuals'=>'individuals'),'class'=>'form-control','empty'=>'Select','id'=>'Ownership')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Rembursment</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Rembursment', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Rembursment.','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
         <label class="col-sm-2 control-label">Active Plan</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('ActivePlan', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Active Plan','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
       
        <label class="col-sm-2 control-label">Approved Amount</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('ApprovedAmount', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Approved Amount','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
    
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
  
