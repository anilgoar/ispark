<?php 
$Read   =   explode(",", $data['Read']);
$Write  =   explode(",", $data['Write']);
$Speak  =   explode(",", $data['Speak']);

echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#Next_Interview_Date").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function ValidateForm(Type){ 
    $("#msgerr").remove();
    var Department      =   $("#Department").val();
    var Desgination     =   $("#Desgination").val();
    var Round           =   $("#Round").val();
    var Question        =   $("#Question").val();
    
    if(Department ===""){
        $("#Department").focus();
        $("#Department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select department.</span>");
        return false;
    }
    else if(Desgination ===""){
        $("#Desgination").focus();
        $("#Desgination").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select desgination.</span>");
        return false;
    }
    else if(Round ===""){
        $("#Round").focus();
        $("#Round").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select round.</span>");
        return false;
    }
    else if(Question ===""){
        $("#Question").focus();
        $("#Question").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select question.</span>");
        return false;
    }
    else{
        return true;        
    }
}

function getdept(Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdept",{'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
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
                    <span>Interview Question</span>
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
                <?php echo $this->Form->create('HrVisitors',array('action'=>'interviewquestion','class'=>'form-horizontal','onSubmit'=>'return ValidateForm()')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Department</label>
                    <div class="col-sm-2">
                        <select name="Department" id="Department" class="form-control" onchange="getdept(this.value,'Desgination')" required=""  >
                            <option value="" >Select</option>
                            <?php foreach($dep as $val){?>
                            <option <?php echo $data['Dept']==$val?"selected='selected'":'';?> value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Designation</label>
                    <div class="col-sm-2">
                        <select name="Desgination" id="Desgination" class="form-control" onchange="question(this.value)" required="" >
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Interview Round</label>
                    <div class="col-sm-2">
                        <select name="Round" id="Round" class="form-control" required=""  >
                            <option value="">Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    
                    <label class="col-sm-1 control-label">Question</label>
                    <div class="col-sm-10">
                        <input type="text" name="Question" id="Question"  autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Answer</label>
                    <div class="col-sm-10">
                        <input type="text" name="Answer" id="Answer"   autocomplete="off" class="form-control">
                    </div>
                </div>

                    
                <div class="form-group">
                    <div class="col-sm-11">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTI2"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        
                        <input type="submit" name="Submit" value="Save" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12 " id="trainerdata" style="overflow-y:scroll;height:400px; " >
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Round</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($data as $rowArr){$row=$rowArr['InterviewQuestionmaster'];?>
                                <tr>
                                    <td><?php echo $i++;?></td>
                                    <td><?php echo $row['Department']?></td>
                                    <td><?php echo $row['Designation']?></td>
                                    <td><?php echo $row['Round']?></td>
                                    <td><?php echo $row['Question']?></td>
                                    <td><?php echo $row['Answer']?></td>
                                    <td> <a href="<?php $this->webroot;?>deletequestion?id=<?php echo base64_encode($row['Id'])?>" onclick="return confirm('Are you sure you want to delete this record?');"><span class='icon' ><i class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span></a></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>	
</div>

