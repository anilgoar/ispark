<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
         function getData(val)
        {
            //alert(val);
            $.post("save_doc",{branch_name:val},function(data)
            {$("#nn").html(data);});
            getData1(val);
        }
         function getData1(val)
        {
            //alert(val);
            $.post("view",{branch_name:val},function(data)
            {$("#mm").html(data);});
        }
        </script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			
<div class="box-content box-con">
    <h4 class="textClass">Activity Entry<?php echo $this->Session->flash(); ?></h4>

    <?php echo $this->Form->create('Activitys',array('class'=>'form-horizontal','action'=>'edit')); ?>
     <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <select name="Branch" id="Title" class="form-control" required="" >
                            <option value='<?php echo $ind[0]['Act']['Branch'] ?>' selected="selected"><?php echo $ind[0]['Act']['Branch'] ?></option>
                            <option value=''>Select Branch</option>
                            <?php foreach($branch_data as $bc){ if($bc['Actdata']['BranchName']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['BranchName'] ?>" ><?php echo $bc['Actdata']['BranchName'] ?></option>
                            <?php   }} ?>
                           
                           
                        </select>
                    </div>
                    <div id="mm">
                    <label class="col-sm-2 control-label">Group</label>
                    <div class="col-sm-3">
                        <select name="Group" id="EmpType" class="form-control"  >
                            <option value='<?php echo $ind[0]['Act']['Group'] ?>'><?php echo $ind[0]['Act']['Group'] ?></option>
                            <option value="">Select</option>
                             <?php foreach($branch_data as $bc){ if($bc['Actdata']['Group']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Group'] ?>" ><?php echo $bc['Actdata']['Group'] ?></option>
                            <?php   }} ?> 
                        </select>
                    </div></div>
                </div>
      <div id="nn">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Client</label>
                    <div class="col-sm-3">
                       <select name="Client" id="Client" class="form-control"  >
                            <option value='<?php echo $ind[0]['Act']['Client'] ?>'><?php echo $ind[0]['Act']['Client'] ?></option>
                            <option value="">Select</option>
                             <?php foreach($branch_data as $bc){ if($bc['Actdata']['Client']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Client'] ?>" ><?php echo $bc['Actdata']['Client'] ?></option>
                            <?php  } } ?> </select>
                       
                    </div>

                    <label class="col-sm-2 control-label">Project</label>
                    <div class="col-sm-3">
                       <select name="Project" id="Project" class="form-control"  >
                            <option value='<?php echo $ind[0]['Act']['Project'] ?>'><?php echo $ind[0]['Act']['Project'] ?></option>
                            <option value="">Select</option>
                              <?php foreach($branch_data as $bc){ if($bc['Actdata']['Project']!='') {  ?>
                               <option value="<?php echo $bc['Actdata']['Project'] ?>" ><?php echo $bc['Actdata']['Project'] ?></option>
                            <?php  } } ?> </select>
                        
                        
                    </div>

                </div>
    
                <div class="form-group">
                    <label class="col-sm-2 control-label">Module</label>
                    <div class="col-sm-3">
                        <select name="Module" id="Module" class="form-control"  >
                            <option value='<?php echo $ind[0]['Act']['Module'] ?>'><?php echo $ind[0]['Act']['Module'] ?></option>
                            <option value="">Select</option>
                             <?php foreach($branch_data as $bc){ if($bc['Actdata']['Module']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Module'] ?>" ><?php echo $bc['Actdata']['Module'] ?></option>
                            <?php  } } ?>
                        </select>
                    </div>

                    <label class="col-sm-2 control-label">Activity</label>
                    <div class="col-sm-3">
                        <input type="hidden" name ="datid" value="<?php echo $ind[0]['Act']['id'] ?>" >
                         <select name="Activity" id="Activity" class="form-control"  >
                            <option value='<?php echo $ind[0]['Act']['Activity'] ?>'><?php echo $ind[0]['Act']['Activity'] ?></option>
                            <option value="">Select</option>
                             <?php foreach($branch_data as $bc){ if($bc['Actdata']['Activity']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Activity'] ?>" ><?php echo $bc['Actdata']['Activity'] ?></option>
                            <?php   }} ?>
                        </select>
                    </div>
                </div>
      </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Remarks</label>
                    <div class="col-sm-3">
                        <textarea name="Remarks" id ='Remarks'   rows="5" cols="80" required=""><?php echo $ind[0]['Act']['Remarks'] ?></textarea>
                    </div>
                    </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Time</label>
                    <div class="col-sm-3">
                        <select name="Time" id="Time" class="form-control"  required="">
                            <option value='<?php echo $ind[0]['Act']['SpentTime'] ?>'><?php echo $ind[0]['Act']['SpentTime'] ?></option>
                            <option value="">Select</option>
                            <option value="00:15">00:15</option>
                            <option value="00:30">00:30</option>
                            <option value="00:45">00:45</option>
                            <option value="01:00">01:00</option>
                            <option value="01:15">01:15</option>
                            <option value="01:30">01:30</option>
                            <option value="01:45">01:45</option>
                            <option value="02:00">02:00</option>
                            <option value="02:15">02:15</option>
                            
                            <option value="02:30">02:30</option>
                            <option value="02:45">02:45</option>
                            <option value="03:00">03:00</option>
                            <option value="03:15">03:15</option>
                            <option value="03:30">03:30</option>
                            <option value="03:45">03:45</option>
                            <option value="04:00">04:00</option>
                            <option value="04:15">04:15</option>
                            <option value="04:30">04:30</option>
                            
                             
                            <option value="04:45">04:45</option>
                            <option value="05:00">05:00</option>
                            <option value="05:15">05:15</option>
                            <option value="05:30">05:30</option>
                            <option value="05:45">05:45</option>
                            <option value="06:00">06:00</option>
                            <option value="06:15">06:15</option>
                            <option value="06:30">06:30</option>
                             <option value="06:45">06:45</option>
                             
                             <option value="07:00">07:00</option>
                            <option value="07:15">07:15</option>
                            <option value="07:30">07:30</option>
                            <option value="07:45">07:45</option>
                            <option value="08:00">08:00</option>
                            <option value="08:15">08:15</option>
                            <option value="08:30">08:30</option>
                             <option value="08:45">08:45</option>
                             
                             <option value="09:00">09:00</option>
                            <option value="09:15">09:15</option>
                            <option value="09:30">09:30</option>
                            <option value="09:45">09:45</option>
                            <option value="10:00">10:00</option>
                            <option value="10:15">10:15</option>
                            <option value="10:30">10:30</option>
                             <option value="10:45">10:45</option>
                        </select>
                    </div>
                </div>
                
                
    
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-3">
            
                <input type='submit' class="btn btn-info" value="Save" style="margin-left:109px;">
            
        </div>
    </div>
   
    <div class="clearfix"></div>
   
    <?php echo $this->Form->end(); ?>
    
</div></div></div></div>