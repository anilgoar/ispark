<?php
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
$exp    =   explode("Menus?AX=", $_SERVER['HTTP_REFERER']);
$expid  =   end($exp);
if(isset($_REQUEST['id'])){

 $id = $_REQUEST['id'];

}
?>

<script>





function SubmitRemark(tab){ 

    $("#msgerr").remove();

    var remarks1 = $("#remarks1").val();
    var remarks2 = $("#remarks2").val();
    var remarks3 = $("#remarks3").val();


    if(tab=='form1')
    {
        if(remarks1 ===""){
            $("#remarks1").focus();
            $("#remarks1").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Remark.</span>");
            return false;
        }
        $("#form1").submit();

    }else if(tab=='form2')
    {
        if(remarks2 ===""){
            $("#remarks2").focus();
            $("#remarks2").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Remark.</span>");
            return false;
        }
        $("#form2").submit();

    }else{

        if(remarks3 ===""){
            $("#remarks3").focus();
            $("#remarks3").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Remark.</span>");
            return false;
        }
        $("#form3").submit();
    }
    
}
</script>



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
                    <span>Left Employee Remarks</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
           
            
            
            
            <div class="box-content box-con" style="margin-top: 10px;" >
                
                <div class="box-header"  >
                    <div class="box-name">
                        <span>Left Employee Remarks 1</span>
                    </div>
		            <div class="no-move"></div>
                </div>
                
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('EmpAttStatus',array('action'=>'left_emp','class'=>'form-horizontal','id'=>'form1')); ?>
                <?php echo $this->Form->input('idx',array('type'=>'hidden','id'=>'idx','value'=>$id)); ?>
                <?php echo $this->Form->input('tab',array('type'=>'hidden','id'=>'tab','value'=>'form1')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('remarks1',array('type'=>'textarea','label' => false,'class'=>'form-control','id'=>'remarks1','required'=>true)); ?>
                    </div>
              
                    <div class="col-sm-1">
                        <input type="button" onclick="SubmitRemark('form1');" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                </div>                 
                <?php echo $this->Form->end(); ?>
            </div>


            <div class="box-content box-con" style="margin-top: 5px;" >
                
                <div class="box-header"  >
                    <div class="box-name">
                        <span>Left Employee Remarks 2</span>
                    </div>
		            <div class="no-move"></div>
                </div>
                
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('EmpAttStatus',array('action'=>'left_emp','class'=>'form-horizontal','id'=>'form2')); ?>
                <?php echo $this->Form->input('idx',array('type'=>'hidden','id'=>'idx','value'=>$id)); ?>
                <?php echo $this->Form->input('tab',array('type'=>'hidden','id'=>'tab','value'=>'form2')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('remarks2',array('type'=>'textarea','label' => false,'class'=>'form-control','id'=>'remarks2','required'=>true)); ?>
                    </div>
              
                    <div class="col-sm-1">
                        <input type="button" onclick="SubmitRemark('form2');" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                </div>                  
                <?php echo $this->Form->end(); ?>
            </div>

            <div class="box-content box-con" style="margin-top: 5px;">
                
                <div class="box-header">
                    <div class="box-name">
                        <span>Left Employee Remarks 3</span>
                    </div>
		            <div class="no-move"></div>
                </div>
                
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('EmpAttStatus',array('action'=>'left_emp','class'=>'form-horizontal','id'=>'form3')); ?>
                <?php echo $this->Form->input('idx',array('type'=>'hidden','id'=>'idx','value'=>$id)); ?>
                <?php echo $this->Form->input('tab',array('type'=>'hidden','id'=>'tab','value'=>'form3')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('remarks3',array('type'=>'textarea','label' => false,'class'=>'form-control','id'=>'remarks3','required'=>true)); ?>
                    </div>
              
                    <div class="col-sm-1">
                        <input type="button" onclick="SubmitRemark('form3');" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                </div>                  
                <?php echo $this->Form->end(); ?>
            </div>
            
        </div>
    </div>	
</div>
