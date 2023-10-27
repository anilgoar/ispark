<script>

function showdata(Type){ 
    $("#msgerr").remove();
    var type=$("#type").val();
    var position=$("#position").val();
    
    if(type ===""){
        $("#type").focus();
        $("#type").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Paper Name.</span>");
        return false;
    }else if(position ==="")
    {
        $("#position").focus();
        $("#position").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select position.</span>");
        return false;
    }

    else{

        $("#loder").show();
        $("#form1").submit();
        
    }
}

function Questionaction(Id){
   
   if(confirm("Are you sure you want to delete this question?")){
       //window.location="<?php// echo $this->webroot;?>InterviewQuestions/delete_question?Id="+Id;
        $.post("delete_question",
        {
            Id:Id
        },
        function(data){

            alert(data);
            $('#'+Id).remove();

        });  
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
            <div class="box-header">
                <div class="box-name">
                    <span>View Question</span>
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
                <?php echo $this->Form->create('InterviewQuestions',array('action'=>'view_question','class'=>'form-horizontal','id'=>'form1')); ?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Paper Name</label>
                    <div class="col-sm-2">
                       <?php echo $this->Form->input('type',array('label' => false,'options'=>$paper_name,'empty'=>'Select','class'=>'form-control','id'=>'type','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">Position</label>
                    <div class="col-sm-2">
                       <?php echo $this->Form->input('position',array('label' => false,'options'=>$positionName,'empty'=>'Select','class'=>'form-control','id'=>'position','required'=>true)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $this->webroot;?>InterviewQuestions"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                         
                        <input type="button" onclick="showdata();" value="Show" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                    <div class="col-sm-1">
                        <img src="http://mascallnetnorth.in/ispark/img/ajax-loader.gif" style="width:35px;display: none;" id="loder">
                    </div>

                    
                </div>
              <?php echo $this->Form->end(); ?>
                <?php if(!empty($data)){ ?>
                <table class = "table table-striped table-hover  responstable" style="margin-top:-100px;" >     
                    <thead>
                        <tr><th colspan="15" style="text-align: center;" >Details</th></tr>
                        <tr>
                            <th style="text-align: center;">SNo.</th>
                            <th style="text-align: center;">Type</th>
                            <th style="text-align: center;">Question Type</th>
                            <th style="text-align: center;">Question</th>
                            <th style="text-align: center;">Assign To Job Role</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($data as $val){
                       ?>
                        <tr id="<?php echo $val['InterviewQuiz']['id'];?>">
                            <td style="text-align: center;"><?php echo $i++;?></td>
                            <td style="text-align: center;"><?php echo $val['InterviewQuiz']['type'];?></td>
                            <td style="text-align: center;"><?php if($val['InterviewQuiz']['quest_type'] == 'Let_it_be'){ echo "Let it be";}else{ echo $val['InterviewQuiz']['quest_type'];};?></td>
                            <td style="text-align: center;"><?php echo $val['InterviewQuiz']['question'];?></td>
                            <td style="text-align: center;"><?php echo $val['InterviewQuiz']['position'];?></td>
                            <td style="text-align: center;">
                                <?php if($val['InterviewQuiz']['type'] == 'MCQ' || $val['InterviewQuiz']['type'] == 'MCQ-Self'){ ?>

                                    <a href="<?php $this->webroot;?>edit_mcq_question?id=<?php echo $val['InterviewQuiz']['id'];?>"><i title="edit"  style="font-size:20px;cursor: pointer;" class="material-icons">edit</i></a>
                                <?php }else{ ?>
                                    <a href="<?php $this->webroot;?>edit_subjective_question?id=<?php echo $val['InterviewQuiz']['id'];?>"><i title="edit"  style="font-size:20px;cursor: pointer;" class="material-icons">edit</i></a>
                                <?php } ?>
                                <i title="Delete" onclick="Questionaction('<?php echo $val['InterviewQuiz']['id'];?>');" style="font-size:20px;cursor: pointer;" class="material-icons">delete_forever</i>
                            
                            </td>
                        </tr>
                    <?php }?>
                </tbody>   
                </table>                
                <?php }?>
                
            </div>
        </div>
    </div>	
</div>








