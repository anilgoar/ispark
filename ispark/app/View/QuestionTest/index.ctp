
<!-- File: /app/View/UserType/index.ctp -->
<script>
    function checkNumber(val,evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        else if(val.length>1 &&  (charCode> 47 && charCode<58) || charCode == 8 || charCode == 110 )
        {
            if(val.indexOf(".") >= 0 && val.indexOf(".") <= 3 || charCode == 8 ){
                 
            }
        }
	return true;
}
    
</script>    


<div class="box-content">
    <div class="text-center">
        <h3 class="page-header">Test Paper</h3>
    </div>
    <?php
       echo  $this->Session->flash();
    ?>
    <?php echo $this->Form->create('QuestionTest',array('autocomplete'=>"off")); ?>
            <div class="form-group">
            <?php echo $this->Form->input('name',array('class'=>'form-control','value'=>'','required'=>true)); ?>
            </div>
            <div class="form-group">
            <?php echo $this->Form->input('mob_no',array('class'=>'form-control','onkeypress'=>'return checkNumber(this.value,event)','value'=>'','required'=>true)); ?>
            </div>
            <div class="form-group">
            <?php echo $this->Form->input('post',array('class'=>'form-control','value'=>'','required'=>true)); ?>
            </div>
            <div class="text-center">
                <input type="submit" name="submit" value="Login" class="btn btn-info" />
            </div>
    <?php echo $this->Form->end(); ?>
</div>