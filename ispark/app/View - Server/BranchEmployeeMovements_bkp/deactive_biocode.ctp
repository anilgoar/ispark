<?php ?>

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

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>Pending Batch Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <h3><?php echo $this->Session->flash(); ?></h3>
                <?php echo $this->Form->create('BranchEmployeeMovements',array('class'=>'form-horizontal')); ?>
                    <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('Branch',array('label' => false,'Branch'=>'Branch','class'=>'form-control','options'=>$branch_master,'empty'=>'Select','required'=>true,'onchange'=>'getForEmployeeMove(this.value)')); ?>
                    </div>
                    <label class="col-sm-1 control-label">BatchCode:</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('BatchCode',array('label' => false,'id'=>'cost_center','class'=>'form-control','options'=>"",'empty'=>'Select')); ?>
                    </div>
                    <div class="col-sm-3">
                       Not Assigned in Any Batch: <input type="checkbox" id="NoBatch" name="NotBatch" value="1" />
                    </div>
                    
                    </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                         <input type="submit" name="submit" value="View" class="btn pull-right btn-primary btn-new">
                         

                    </div>
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('BranchEmployeeMovements',array('class'=>'form-horizontal')); ?>	
                <?php    
if(!empty($SearchDetails))
{
?>
    <div class="form-group">
                   <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-5">
                <table class = "table table-striped table-hover  responstable" style="margin-top:-30px;" >     
<thead>      
    <tr>
        <th style="text-align: center;">BioCode</th>
    <th>EmpName</th>
    <th style="text-align: center;width:30px;" >&#10004;</th>
    </tr>
    </thead> 
     <tbody>  
         
<?php
    foreach($SearchDetails as $SD)
    {
        $EmpCode= $SD['Masjclrentry']['EmpCode'];
?>
<tr>
    <td><?php echo $SD['Att']['BioCode'];?></td>
    <td><?php echo $SD['Att']['EmpName'];?></td>
    <td><center><input class="checkbox" type="checkbox" value="<?php echo $SD['Att']['BioCode'];?>" name="check[]"></center></td>
</tr>   
    
<?php
    }
?>
     </tbody>
    </table>
                    </div>
         </div>
        
<?php    
}
?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-5">
                            <?php echo $this->Form->textArea('Remarks',array('label' => false,'class'=>'form-control','placeholder'=>'Reason','rows'=>5,'required'=>true)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-4">
                        <input type="submit" name="submit" class="btn pull-right btn-primary btn-new" value="Delete" />
                    </div>
                </div>
                <div class="clearfix"></div>
<?php 

echo $this->Form->end();
?>
            </div>
        </div>
    </div>	
</div>


<script>
function getForDeactiveBioCode(Branch)
{
    Jajax('Branch',Branch,'BranchEmployeeMovements/get_Batch','cost_center');
}

function Jajax(key,value,url,id)
{
   $.post("<?php echo $this->webroot;?>"+url,
    {
        Branch: value
    },
    function(data,status){
        var text='<option value="">Select</option>';
        var json = jQuery.parseJSON(data);
        for(var i in json)
        {
            text += '<option value="'+i+'">'+json[i]+'</option>';
        }
        //alert(text);
        $("#"+id).empty();
        $("#"+id).html(text);
    });  
}
</script>
