<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>

<script>

function getPosition(name){ 

    $.post("<?php echo $this->webroot;?>InterviewQuestions/getposition_marking",{name:name}, function(data){
        $("#position").html(data);
    });  
}
function getPapername(name){ 


$.post("<?php echo $this->webroot;?>InterviewQuestions/getpaper_name",{name:name}, function(data){
    $("#paper").html(data);
});  
}

function Setstatus(){

    $("#msgerr").remove(); 
    var right_ans=$("#right").name(); 
    var wrong_ans=$("#wrong").name(); 
    alert(right_ans);
    alert("hello");
    return false;
   
    $.post("<?php echo $this->webroot;?>InterviewQuestion/mark_subjective_answer",{'interview_id':interview_id,'status':status,'questionid':questionid}, function(data) {
        $("#EmpNameCode" ).html(data);
    });

}

function logReport(Type){ 
    $("#msgerr").remove();
    var paper_name=$("#paper_name").val();
    var position=$("#position").val();
    var paper=$("#paper").val();
    
    if(paper_name ===""){
        $("#paper_name").focus();
        $("#paper_name").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select name.</span>");
        return false;
    }

    else if(position ===""){
        $("#position").focus();
        $("#position").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select position.</span>");
        return false;
    }
    else if(paper ===""){
        $("#paper").focus();
        $("#paper").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select paper.</span>");
        return false;
    }

    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>InterviewQuestions/view_subjective_answer",{paper_name:paper_name,position:position,paper:paper}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divAttendance").html(data);
                }
                else{
                    $("#divAttendance").html('<div class="col-sm-12" style="color:red;font-weight:bold;">Record not found.</div>');
                } 
            });
        }
        else if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>Tickets/export_report?BranchName="+BranchName+"&From="+From+"&To="+To+"&CostCenter="+CostCenter+"&status"+status+"&trigger_type="+trigger_type+"&EmpCode="+$.trim(EmpCode);  
           
        }
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
                    <span>Mark Answers</span>
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
                <?php echo $this->Form->create('Tickets',array('action'=>'report','class'=>'form-horizontal')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Name</label>
                    <div class="col-sm-2">
                        <?php //echo $this->Form->input('paper_name',array('label' => false,'options'=>$paper_name,'empty'=>'Select','class'=>'form-control','id'=>'paper_name','onchange'=>'getPosition(this.value)','required'=>true)); ?>
                        <select name="paper_name" id="paper_name" class="form-control" onchange='getPosition(this.value),getPapername(this.value)' required>
                        <option value="">Select</option>
                        <?php foreach($paper_name as $paper){ ?>
                            <option value="<?php echo $paper['interview_quiz_answer']['interview_id']; ?>"><?php echo $paper['interview_quiz_answer']['name']; ?></option>
                        <?php }?>
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">Position</label>
                    <div class="col-sm-2">
                       <?php //echo $this->Form->input('position',array('label' => false,'options'=>$positionName,'empty'=>'Select','class'=>'form-control','id'=>'position','required'=>true)); ?>
                        <select id="position" name="position" autocomplete="off" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">Paper Name</label>
                    <div class="col-sm-2">
                        <select id="paper" name="paper" autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <!-- <input type="button" onclick="logReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" > -->
                         
                        <input type="button" onclick="logReport('show');" value="View" class="btn pull-right btn-primary btn-new">
                        
                    </div>

                </div>
                <?php echo $this->Form->end(); ?>
                
                <div class="form-group" id="divAttendance"></div>
                
                
            </div>
        </div>
    </div>	
</div>



