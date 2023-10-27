<script>
function validatdGrnBranch(){  
    var UserId = $('#UserId').val();
    var all_location_id = document.querySelectorAll('input[name="branch[]"]:checked');
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }
      
    if(UserId==''){
        alert("Please select User");
        return false;
    }
    else if(aIds==''){
        alert("Please select branch.");
        return false;
    }
    else{
      return true; 
    }   
}

function get_branch(val)
{
   $.post("get_branch",
            {
             userid:val,
            },
            function(data,status){
                $('#br').html(data);
               
            }); 
}
</script>

<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>

<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">GRN Branch Access</h4>
    <?php echo '<font color="green">'.$this->Session->flash().'</font>'; ?>
    <?php echo $this->Form->create('GrnBranches',array('action'=>'grn_branch_access','class'=>'form-horizontal','onsubmit'=>'return validatdGrnBranch()')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">User Id</label>
        <div class="col-sm-6">
            <div class="input-group">
                <?php echo $this->Form->input('UserId',array('label' => false,'options'=>$user_master,'class'=>'form-control','empty'=>'Select User','id'=>'UserId','onchange'=>"get_branch(this.value)",'required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
            </div>    
        </div>
    </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch Name</label>
        <div class="col-sm-6">
            <div class="input-group" >
                <div style="overflow-y: scroll;height:300px;width:200px;" id="br">
                <?php foreach($branch_master as $k=>$v) { ?>  
                <input type="checkbox" name="branch[]" value='<?php echo $k ?>' /> <?php echo $v; ?> <br/>
                <?php } ?> 
                </div>
            </div>    
        </div>
    </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-2">
            <button type='submit' class="btn btn-info" value="Save" onclick="return validate_imprest()">Submit</button>
            <a href="/ispark/Menuisps/sub?AX=NjA=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
        </div>
    </div>
 
    <?php echo $this->Form->end(); ?>
</div>










