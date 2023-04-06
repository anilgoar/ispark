
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

<?php 

echo $this->Form->create('Connectivities',array('class'=>'form-horizontal','action'=>'get_report11')); 

?>
<div class="form-group has-info has-feedback">

 <?php
                         if(!empty($branchName))
                            {
                                
                              
        
                              
foreach($branchName as $bb){ ?>
                               
<input type = 'checkbox' name = 'Branch[]' value = '<?php echo $bb; ?>' ><?php echo $bb; ?><br>
                              
<?php
       
  
                       } }
                        
                           
                        
                        ?>

 <label class="col-sm-2 control-label">Report Type</label>
        <div class="col-sm-3">
            <div class="input-group">
                
                <?php echo $this->Form->input('rtype',array('label' => false,'options'=>array('HardWare'=>'HardWare','Connectivity'=>'Connectivity','Mobile Data'=>'Mobile Data'),'class'=>'form-control','empty'=>'Select','id'=>'rtype')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    
 </div>

		<div class="form-group has-info has-feedback">								
			
			<label class="col-sm-2 control-label">start date</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Sdate', array('type'=>'text','label'=>false,'class'=>'form-control','value'=>'','onclick'=>"displayDatePicker('data[Connectivities][Sdate]');",'placeholder'=>'Start Date','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
			<label class="col-sm-2 control-label">End date</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Edate', array('type'=>'text','label'=>false,'class'=>'form-control','value'=>'','onclick'=>"displayDatePicker('data[Connectivities][Edate]');",'placeholder'=>'End Date','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div></div>
		<div class="clearfix"></div>
		<div class="form-group">
			<div class="col-sm-2">
                                <input type="submit" class="btn btn-info"  name='export' value="Export" >
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

