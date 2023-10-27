<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#AttenDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
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
            
            <div class="box-content box-con">
                
            <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('InterviewQuestion',array('action'=>'index','class'=>'form-horizontal','id'=>'form1','enctype'=>'multipart/form-data')); ?>
                
                
                <div class="form-group">

                    <div class="col-sm-2" style="text-align: center;margin-top: 10px;">
                        <label>Question Paper</label>
                        <a href="<?php echo $this->webroot;?>InterviewQuestions/add_question_paper"><img src="<?php echo $this->webroot;?>app/webroot/img/question.png" width="100px;"></img></a>	
                    </div>

                    <div class="col-sm-2" style="text-align: center;">
                        <label>Create Multiple Choice Question</label>
                        <a href="<?php echo $this->webroot;?>InterviewQuestions/mcq_question"><img src="<?php echo $this->webroot;?>app/webroot/img/multiple_choice.png" width="100px;"></img></a>
                    </div>

                    <div class="col-sm-2" style="text-align: center;">
                      <label>Create Subjective Type Question</label>
                      <a href="<?php echo $this->webroot;?>InterviewQuestions/subjective_question"><img src="<?php echo $this->webroot;?>app/webroot/img/subjective.png" width="100px;"></img></a>
                        
                    </div>
                    <div class="col-sm-2" style="text-align: center;">
                        <label>Edit/Delete Questions My Library</label>
                        <a href="<?php echo $this->webroot;?>InterviewQuestions/view_question"><img src="<?php echo $this->webroot;?>app/webroot/img/details.png" width="100px;"></img></a>	
                    </div>
                    <!-- <div class="col-sm-2" style="text-align: center;">
                        <label>Mark Answers</label>
                        <a href="<?php// echo $this->webroot;?>InterviewQuestions/view_subjective_answer"><img src="<?php// echo $this->webroot;?>app/webroot/img/question-mark.png" width="100px;"></img></a>	
                    </div> -->
                    <div class="col-sm-2" style="text-align: center;">
                        <label>Psychometric Test Report</label>
                        <a href="<?php echo $this->webroot;?>InterviewQuestions/report"><img src="<?php echo $this->webroot;?>app/webroot/img/report.png" width="100px;"></img></a>	
                    </div>

                    <!-- <div class="col-sm-2" style="text-align: center;margin-top: 10px;">
                        <label>Add Question Type</label>
                        <a href="<?php// echo $this->webroot;?>InterviewQuestions/add_question_type"><img src="<?php //echo $this->webroot;?>app/webroot/img/question-mark.png" width="100px;"></img></a>	
                    </div> -->

                </div>
                <div class="form-group">
                    
                    <div class="col-sm-2" style="text-align: center;">
                        <label>Add   Benchmarking  </label>
                        <a href="<?php echo $this->webroot;?>InterviewQuestions/marking_formula"><img src="<?php echo $this->webroot;?>app/webroot/img/report.png" width="100px;"></img></a>	
                    </div>
                </div>
                
              <?php echo $this->Form->end(); ?>
                
                
            </div>
        </div>
    </div>	
</div>







