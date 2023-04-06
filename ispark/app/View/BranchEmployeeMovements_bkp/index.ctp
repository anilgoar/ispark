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
                    <span>Employee Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('BranchEmployeeMovements',array('class'=>'form-horizontal')); ?>
                    <div class="form-group">
                    <label class="col-sm-2 control-label">Employee Code:</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('EmpCode',array('label' => false,'class'=>'form-control','id'=>'EmpCode','placeholder'=>'Employee Code','autocomplete'=>'off','required'=>true)); ?>
                    </div>
                    <div class="col-sm-1">
                         <input type="submit" name="submit" value="View" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
 

               <?php echo $this->Form->end(); ?>
                
                

<?php if(!empty($SearchDetails)){?>
<table class = "table table-striped table-hover  responstable" >     
    <thead>      
        <tr>
            <th style="text-align: center;width:40px;" >SrNo</th>
            <th style="text-align: center;">BranchName</th>
            <th style="text-align: center;width:100px;">EmpCode</th>
            <th>EmpName</th>
            <th>FatherName</th>
            <th style="text-align: center;width:80px;">DOB</th>
            <th style="text-align: center;width:80px;">DOJ</th>
            <th style="text-align: center;">Desig</th>
        </tr>
    </thead> 
     <tbody>  
         
<?php $i=1; foreach($SearchDetails as $SD){
        $EmpCode= $SD['Masjclrentry']['EmpCode'];
?>
 <tr>
    <td style="text-align: center;"><?php echo $i++;?></td>
    <td style="text-align: center;"><?php echo $SD['Masjclrentry']['BranchName'];?></td>
    <td style="text-align: center;"><?php echo $SD['Masjclrentry']['EmpCode'];?></td>
    <td><?php echo $SD['Masjclrentry']['EmpName'];?></td>
    <td ><?php echo $SD['Masjclrentry']['Father'];?></td>
    <td style="text-align: center;"><?php echo $SD['Masjclrentry']['DOB'];?></td>
    <td style="text-align: center;"><?php echo $SD['Masjclrentry']['DOJ'];?></td>
    <td style="text-align: center;"><?php echo $SD['Masjclrentry']['Desgination'];?></td>
    </tr>   
    
<?php
    }
?>
     </tbody>
    </table>
   
<?php } ?>

                
                
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('BranchEmployeeMovements',array('class'=>'form-horizontal')); ?>	
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('Branch',array('label' => false,'Branch'=>'Branch','class'=>'form-control','options'=>$branch_master,'empty'=>'Select','required'=>true,'onchange'=>'getForEmployeeMove(this.value)')); ?>
                    </div>
                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('cost_center',array('label' => false,'id'=>'cost_center','class'=>'form-control','options'=>"",'empty'=>'Select',"required"=>true)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Month</label>
                    <div class="col-sm-2">
                            <?php echo $this->Form->input('month',array('label' => false,'month'=>'month','class'=>'form-control','options'=>array('CM'=>'Current Month Attendance','PM'=>'Previous Month Attendance'),'empty'=>'Select')); ?>
                    </div>
                    <label class="col-sm-2 control-label">User ID:</label>
                    <div class="col-sm-2">
                            <?php echo $this->Form->input('Email',array('label' => false,'id'=>'email','class'=>'form-control','options'=>"",'empty'=>'Select')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Reason</label>
                    <div class="col-sm-6">
                            <?php echo $this->Form->textArea('reason',array('label' => false,'class'=>'form-control','placeholder'=>'Reason','rows'=>3,'required'=>true)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="submit" class="btn pull-right btn-primary btn-new" value="Move" />
                    </div>
                    <div class="col-sm-2" style="display:none">
                    <?php    echo $this->Form->input('EmpCode',array('type'=>'text','value'=>$EmpCode,'required'=>true)); ?>
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
function getForEmployeeMove(Branch)
{
    Jajax('Branch',Branch,'BranchEmployeeMovements/get_cost_center','cost_center');
    Jajax('Branch',Branch,'BranchEmployeeMovements/get_emails','email');
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
