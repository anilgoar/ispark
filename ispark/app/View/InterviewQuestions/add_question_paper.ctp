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

$(function () {
    $("#exam_time1").timePicker( {step:15});
});



function SubmitForm()
{ 

    $("#msgerr").remove();

    var position         = $("#position").val();
    var paper_name       = $("#paper_name").val();
    var exam_time        = $("#exam_time1").val();
    var paper_marks        = $("#paper_marks").val();
    var priority         = $("#priority").val();
   

    if(position ===""){
        $("#position").focus();
        $("#position").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Position.</span>");
        return false;
    }

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

function Questionaction(Id){
   
    if(confirm("Are you sure you want to delete this question?")){
        window.location="<?php echo $this->webroot;?>InterviewQuestions/delete_question_paper?Id="+Id;
    }
 
}

function QuestionEdit(id,position,paper_name,paper_time,priority)
{
    $("#close_id").val(id);
    $("#edit_position").val(position);
    $("#edit_paper_name").val(paper_name);
    //$("#edit_exam_time").val(paper_time);
    $("#edit_priority").val(priority);


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
                    <span>Add Question Paper</span>
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
                <?php echo $this->Form->create('InterviewQuestion',array('action'=>'add_question_paper','class'=>'form-horizontal','id'=>'form1')); ?>

                <div class="form-group">
                    <label class="col-sm-1 control-label">Position</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('position',array('label' => false,'options'=>$positionName,'empty'=>'Select','class'=>'form-control','id'=>'position','required'=>true)); ?>
                    </div>

                    <!-- <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <?php //echo $this->Form->input('type',array('label' => false,'options'=>['MCQ'=>'MCQ','Subjective'=>'Subjective'],'empty'=>'Select','class'=>'form-control','id'=>'type','required'=>true)); ?>
                    </div> -->

                    <label class="col-sm-2 control-label">Question Paper Name</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('paper_name',array('label' => false,'class'=>'form-control','id'=>'paper_name','PlaceHolder'=>'Question Paper Name','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">Time</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('exam_time',array('label'=>false,'class'=>'form-control','id'=>'exam_time1','empty'=>'Select Exam Time','autocomplete'=>'off','required'=>true)); ?>
                        <!-- <input type="text" name="exam_time" id="exam_time"  class="form-control" required=""  > -->
                    </div>

                    
                </div> 
                
                <div class="form-group">


                    <label class="col-sm-1 control-label">Total Marks</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('paper_marks',array('label' => false,'class'=>'form-control','id'=>'paper_marks','PlaceHolder'=>'Total Marks','onkeypress'=>'return isNumberKey(event,this)','required'=>true)); ?>
                    </div>

                    <!-- <label class="col-sm-2 control-label">Sequence</label>
                    <div class="col-sm-2">
                        <?php //echo $this->Form->input('priority',array('label' => false,'class'=>'form-control','id'=>'priority','options'=>[''=>'Select Sequence','1'=>'1','2' => '2','3'=>'3','4' => '4','5' => '5','6'=>'6','7' => '7','8'=>'8','9' => '9','10' => '10'],'required'=>true)); ?>
                    </div> -->
              
                    <div class="col-sm-1">
                        <input type="button" onclick="SubmitForm();" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                    </div>
                    <div class="col-sm-1">
                    <input onclick='return window.location="<?php echo $this->webroot;?>InterviewQuestions"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>                 
                <?php echo $this->Form->end(); ?>
                 
                <?php if(!empty($Question_Arr)) {?>
                <div class="form-group" style="overflow-y:scroll;height:500px;">
                    <table class = "table table-striped table-hover  responstable">     
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Position</th>
                                <th>Question Paper Name</th>
                                <th>Time</th>
                                <th>Total Marks</th>
                                <th>Sequence</th>                              
                                <th>Create Date</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody> 
                        
                            <?php $n=1; foreach($Question_Arr as $data){ ?>
                            <tr>
                                <td><?php echo $n++;?></td>
                                <td><?php echo $data['QuestionPaper']['position'];?></td>
                                <td><?php echo $data['QuestionPaper']['paper_name'];?></td>
                                <td><?php echo $data['QuestionPaper']['paper_time'];?></td>
                                <td><?php echo $data['QuestionPaper']['paper_marks'];?></td>
                                <td><?php echo $data['QuestionPaper']['priority'];?></td>
                                <td><?php echo date_format(date_create($data['QuestionPaper']['created_at']),"d-M-Y");?></td>
                                <td style="text-align: center;">
                                <!-- <?php //if($data['QuestionPaper']['type'] == 'MCQ'){?>
                                    <a href="<?php //$this->webroot;?>mcq_question"><i style="font-size:20px;cursor: pointer;" class="fa fa-plus"></i></a>
                                <?php// }else{?>
                                    <a href="<?php //$this->webroot;?>subjective_question"><i style="font-size:20px;cursor: pointer;" class="fa fa-plus"></i></a>
                                <?php // }?> -->
                                <a href="<?php $this->webroot;?>edit_question_paper?id=<?php echo $data['QuestionPaper']['id'];?>"><i title="edit"  style="font-size:20px;cursor: pointer;" class="material-icons">edit</i></a>
                                <i title="Delete" onclick="Questionaction('<?php echo $data['QuestionPaper']['id'];?>');" style="font-size:20px;cursor: pointer;" class="material-icons">delete_forever</i>
                                
                            </tr>

                            <?php }?>
                        
                        </tbody>   
                    </table>
                </div>
                <?php }?>
            </div>

            
            
        </div>
    </div>	
</div>



<!-- <div class="modal fade" id="catdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #436E90;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Edit Question Paper</h2>      
            </div>
            <?php echo $this->Form->create('InterviewQuestion',array('id'=>'form_file','action'=>'edit_question_paper',"class"=>"form-horizontal row-border")); ?> 
                
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                             <div class="row"> 
                                <div class="col-md-12"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Position</label>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('close_id',array('label'=>false,'type'=>'hidden','class'=>'form-control','id'=>'close_id' ));?>
                                            <?php echo $this->Form->input('position',array('label' => false,'options'=>$positionName,'empty'=>'Select','class'=>'form-control','id'=>'edit_position','required'=>true)); ?>
                                        </div>
                                        <label class="col-sm-3 control-label">Question Paper Name</label>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('paper_name',array('label' => false,'class'=>'form-control','id'=>'edit_paper_name','PlaceHolder'=>'Question Paper Name','required'=>true)); ?>
                                        </div>
                                        
                                    </div>
                                </div> 

                                <div class="col-md-12"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Time</label>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('exam_time',array('label'=>false,'class'=>'form-control','id'=>'edit_exam_time','empty'=>'Select Exam Time','autocomplete'=>'off','required'=>true)); ?>
                                        </div>
                                        <label class="col-sm-3 control-label">Priority</label>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('priority',array('label' => false,'class'=>'form-control','id'=>'edit_priority','options'=>[''=>'Select Priority','1'=>'1','2' => '2','3'=>'3','4' => '4','5' => '5','6'=>'6','7' => '7','8'=>'8','9' => '9','10' => '10'],'required'=>true)); ?>
                                        </div>
                                    </div>
                                </div>  
                             </div> 
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Tickets/close_ticket')"  value="Submit" class="btn-web btn">
                    
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div> -->
