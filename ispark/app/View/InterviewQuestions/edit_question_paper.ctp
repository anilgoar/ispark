<?php 
echo $this->Html->script('jquery.timepicker');
echo $this->Html->script('jquery.timePicker.js');
?>
<style type="text/css">
div.time-picker {
  position: absolute;
  height: 191px;
  width:4em; /* needed for IE */
  overflow: auto;
  background: #fff;
  border: 1px solid #aaa;
  z-index: 99;
  margin: 0;
}
div.time-picker-12hours {
  width:8em; /* needed for IE */
}

div.time-picker ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
div.time-picker li {
  cursor: pointer;
  height: 20px;
  font: 12px/1 Helvetica, Arial, sans-serif;
  padding: 4px 3px;
}
div.time-picker li.selected {
  background: #0063CE;
  color: #fff;
}
</style>

<script>

$(function () {
    $("#exam_time1").timePicker( {step:15});
});

function isNumberKey(e,t)
{
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
        return true;

    }
    catch (err) {
        alert(err.Description);
    }
}



function SubmitForm()
{ 

    $("#msgerr").remove();

    var position         = $("#position").val();
    // var type             = $("#type").val();
    var paper_name       = $("#paper_name").val();
    var exam_time        = $("#exam_time1").val();
    var paper_marks        = $("#paper_marks").val();
    var priority         = $("#priority").val();
   

    if(position ===""){
        $("#position").focus();
        $("#position").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Position.</span>");
        return false;
    }
    // else if(type ===""){
    //     $("#type").focus();
    //     $("#type").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Type.</span>");
    //     return false;
    // }

    else if(paper_name ===""){
        $("#paper_name").focus();
        $("#paper_name").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Paper Name.</span>");
        return false;
    }
    else if(exam_time ===""){
        $("#exam_time1").focus();
        $("#exam_time1").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Paper Time.</span>");
        return false;
    }
    else if(paper_marks ===""){
        $("#paper_marks").focus();
        $("#paper_marks").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Marks.</span>");
        return false;
    }
    else if(priority ===""){
        $("#priority").focus();
        $("#priority").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Priority.</span>");
        return false;
    }
    else{

        $("#form1").submit();
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
                    <span>Edit Question Paper</span>
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
                <?php echo $this->Form->create('InterviewQuestion',array('action'=>'edit_question_paper','class'=>'form-horizontal','id'=>'form1')); ?>

                <div class="form-group">
                    <label class="col-sm-1 control-label">Position</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('close_id',array('label'=>false,'type'=>'hidden','class'=>'form-control','id'=>'close_id','value'=>$edit_Ques['QuestionPaper']['id']));?>
                        <?php echo $this->Form->input('position',array('label' => false,'options'=>$positionName,'empty'=>'Select','class'=>'form-control','id'=>'position','required'=>true,'value'=>$edit_Ques['QuestionPaper']['position'])); ?>
                    </div>

                    <!-- <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <?php //echo $this->Form->input('type',array('label' => false,'options'=>['MCQ'=>'MCQ','Subjective'=>'Subjective'],'empty'=>'Select','class'=>'form-control','id'=>'type','value'=>$edit_Ques['QuestionPaper']['type'],'required'=>true)); ?>
                    </div> -->

                    <label class="col-sm-2 control-label">Question Paper Name</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('paper_name',array('label' => false,'class'=>'form-control','id'=>'paper_name','PlaceHolder'=>'Question Paper Name','required'=>true,'value'=>$edit_Ques['QuestionPaper']['paper_name'])); ?>
                    </div>

                    <label class="col-sm-1 control-label">Time</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('exam_time',array('label'=>false,'class'=>'form-control','id'=>'exam_time1','empty'=>'Select Exam Time','autocomplete'=>'off','required'=>true,'value'=>$edit_Ques['QuestionPaper']['paper_time'])); ?>
                        <!-- <input type="text" name="exam_time" id="exam_time"  class="form-control" required=""  > -->
                    </div>

                    
                </div> 
                
                
                <div class="form-group">

                    <label class="col-sm-1 control-label">Total Marks</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('paper_marks',array('label' => false,'class'=>'form-control','id'=>'paper_marks','PlaceHolder'=>'Total Marks','onkeypress'=>'return isNumberKey(event,this)','value'=>$edit_Ques['QuestionPaper']['paper_marks'],'required'=>true)); ?>
                    </div>

                    <label class="col-sm-2 control-label">Sequence</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('priority',array('label' => false,'class'=>'form-control','id'=>'priority','options'=>[''=>'Select Priority','1'=>'1','2' => '2','3'=>'3','4' => '4','5' => '5','6'=>'6','7' => '7','8'=>'8','9' => '9','10' => '10'],'required'=>true,'value'=>$edit_Ques['QuestionPaper']['priority'])); ?>
                    </div>
              
                    <div class="col-sm-1">
                        <input type="button" onclick="SubmitForm();" value="Update" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                    </div>
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $this->webroot;?>InterviewQuestions/add_question_paper"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>                 
                <?php echo $this->Form->end(); ?>
                 
            </div>

            
            
        </div>
    </div>	
</div>


