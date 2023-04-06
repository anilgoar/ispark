<?php ?>

<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>Edit Investment</span>
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
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('Branch',array('label' => false,'Branch'=>'Branch','class'=>'form-control','options'=>$branch_master,'empty'=>'Select','required'=>true,'onchange'=>'getForEmployeeInvestment(this.value)')); ?>
                    </div>
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTc%3D"' type="button" value="Back" class="btn btn-primary btn-new pull-right"/>
                    </div>
                    </div>
                <?php echo $this->Form->end(); ?>

            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('BranchEmployeeMovements',array('class'=>'form-horizontal')); ?>	
                <?php    
if(!empty($data))
{
?>
                <div style="overflow:auto;" id="EmpDet">
       <table class = "table table-striped table-hover  responstable"  >     
<thead>      
    <tr>
    <th>EmpCode</th>
    <th>EmpName</th>
    <th>Investment Under Section24</th>
    <th>Investment Under Chapter6</th>
    <th><input type="checkbox" name="all" id="all" value="all" /> Approved/Not Approved</th>
    <th>Detail</th>
    </tr>
    </thead> 
     <tbody>  
         
<?php
    foreach($data as $SD)
    {
        
?>
 <tr>
    <td><?php echo $SD['Masjclrentry']['EmpCode'];?></td>
    <td><?php echo $SD['Masjclrentry']['EmpName'];?></td>
    <td><?php echo $SD['0']['Section24'];?></td>
    <td></td>
    <td align="center"><input type="checkbox" name="AprInvest" id="AprInvest" value="" /></td>
    <td>
        <?php echo $this->Html->link(''.'Details',array('controller'=>'BranchEmployeeMovements','action'=>'view_edit_investment','?'=>array('EmpCode'=>$SD['Masjclrentry']['EmpCode']),'full_base' => true),array('escape'=>false)); ?>
    </td>
</tr>   
    
<?php
    }
?>
     </tbody>
    </table>
    </div>
<?php    
}
?>
<div class="clearfix"></div>
<?php 

echo $this->Form->end();
?>
            </div>
        </div>
    </div>	
</div>


<script>
function getForEmployeeInvestment(Branch)
{
    Jajax('Branch',Branch,'BranchEmployeeMovements/get_emp_for_investment','EmpDet');
}

function Jajax(key,value,url,id)
{
   $.post("<?php echo $this->webroot;?>"+url,
    {
        Branch: value
    },
    function(data,status){
        $("#"+id).empty();
        $("#"+id).html(data);
    });  
}
</script>
