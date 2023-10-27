<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>

<script>


function logReport(Type){ 
    $("#msgerr").remove();
    var name=$("#name").val();
    var position=$("#position").val();
    var From=$("#from_date").val();
    var To=$("#to_date").val();
    
    if(name ===""){
        $("#name").focus();
        $("#name").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select name.</span>");
        return false;
    }
    else if(position ===""){
        $("#position").focus();
        $("#position").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select position.</span>");
        return false;
    }

    else if(From ===""){
        $("#from_date").focus();
        $("#from_date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select from date.</span>");
        return false;
    }
    else if(To ===""){
        $("#to_date").focus();
        $("#to_date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select to date.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>InterviewQuestions/report",{name:name,From:From,To:To,position:position}, function(data) {
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
                    <span>Ticket Report</span>
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
                <div class="form-group">
                    <div class="col-sm-6">
                        <h3><?php echo $name; ?></h3>
                    </div>
                    <div class="col-sm-6">
                        <h3>Applied For <?php echo $position; ?></h3>
                    </div>
                    
                </div>
                
                <div class="form-group">
                  
                <table class = "table table-striped table-hover  responstable"  style="margin-top: 100px;">     
                    
                    <tbody> 
                        <tr><td colspan='2'> <h3>Score Details</h3></td>
                       </tr>
                        <?php $n=1; foreach($data as $d){?>
                         <tr>
                            <td><h5><?php echo $d['paper_name'];?> <?php //echo $d['InterviewQuizResult']['result'];?></h5></td>
                            <td><h5>Observation : <?php if($d['InterviewQuizResult']['status']== 'Should not hire'){ echo "Grade D" ;} else if($d['InterviewQuizResult']['status']== 'Risky'){ echo "Grade C";}
                            else if($d['InterviewQuizResult']['status']== 'Can be considered' || $d['InterviewQuizResult']['status']== 'Average'){ echo "Grade B";} else if($d['InterviewQuizResult']['status']== 'Ideal Candidate'){ echo "Grade A";}else { echo "Grade D" ; };
                            ?><?php //if($d['paper_type']== 'MCQ'){ echo "<br>Score " . $d['InterviewQuizResult']['result'];} ?></h5></td>
                         </tr>
                         

                      <?php }?>
                        <tr>
                        <?php foreach($data as $d){
                            if($d['paper_name'] == 'Conflict Management')
                            {
                                if($d['InterviewQuizResult']['status'] == 'Should not hire')
                                {
                                    echo "<td><h3>Total Scoring</h3></td>";
                                    echo "<td><h3>Grade D</h3></td>" ;
                                }
                               

                            }
                        }?>
                        </tr>
                      
                    </tbody>   
                </table>
                <br>
                <table class = "table table-striped table-hover responstable">
                   <thead>
                        <tr>
                            <th>Paper Name</th>
                            <th>Paper Type</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <?php  foreach($ques_ans as $total_ans){ ?>
                    <tr>
                        <td><?php echo $total_ans['paper_name']; ?></td>
                        <td><?php echo $total_ans['quest_type']; ?></td>
                        <td><?php echo $total_ans['ques_name']; ?></td>
                        <td><?php echo $total_ans['InterviewQuizAnswer']['ans']; ?></td>
                        <?php if($total_ans['InterviewQuizAnswer']['paper_type'] == 'MCQ') {?>
                            <td><?php echo $total_ans['InterviewQuizAnswer']['marks']; ?></td>
                            <?php }else{?>
                        <td><?php echo $total_ans['InterviewQuizAnswer']['ans_marks']; ?></td>
                        <?php }?>
                    </tr>
                    <?php }?>
                    
                </table>
                    
                   
                <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        
                         
  
                    
                </div>

                
            </div>
        </div>
    </div>	
</div>



