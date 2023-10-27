<?php ?>
<script>
function validateDepartment(){
    $("#msgerr").remove();
    var Department=$("#paper_type").val();
    
    if(Department ===""){
        $("#paper_type").focus();
        $("#paper_type").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter paper type.</span>");
        return false;
    }
    else{
        return true;
    }
}
    
function addNew(){
    window.location="<?php echo $this->webroot;?>InterviewQuestions/add_question_type";
}

function Questionaction(Id){
   
   if(confirm("Are you sure you want to delete this paper type?")){
       //window.location="<?php// echo $this->webroot;?>InterviewQuestions/delete_question?Id="+Id;
        $.post("delete_question_type",
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
            <div class="box-header"  >
                <div class="box-name">
                    <span>Paper Type</span>
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
                <?php echo $this->Form->create('InterviewQuestions',array('action'=>'add_question_type','class'=>'form-horizontal','onSubmit'=>'return validateDepartment()')); ?>
                <div class="form-group"> 
                    <label class="col-sm-1 control-label">Paper Type</label>
                    <div class="col-sm-3">
                        <input type="text" id="paper_type" name="paper_type" value="<?php echo isset($row['paper_type'])?$row['paper_type']:'';?>" autocomplete="off" class="form-control" >
                    </div>
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php if(isset($row)){?>
                        <input type="hidden" name="paper_type_id" value="<?php echo isset($row['id'])?$row['id']:'';?>" >
                        <input type="button" onclick="addNew();"  value="Add New" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input type="submit" name="submit"  value="Update" class="btn pull-right btn-primary btn-new"  >
                        <?php }else{?>
                        <input type="submit"  name="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                        <?php }?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="width:50px;">SNo</th>
                                    <th>Paper Type</th>
                                    <th style="width:50px;">Status</th>
                                    <th style="width:40px;" >Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($data as $val){?>
                                <tr id="<?php echo $val['InterviewPaperType']['id'];?>">
                                    <td><?php echo $n++;?></td>
                                    <td><?php echo $val['InterviewPaperType']['paper_type'];?></td>
                                    <td><?php if($val['InterviewPaperType']['status'] =="1"){echo "Active";}else{echo "Deactive";}?></td>
                                    <td>
                                        <a href="#" onclick="Questionaction('<?php echo $val['InterviewPaperType']['id'];?>');"><span class='icon' ><i title="Delete" class="material-icons" style="font-size:20px;" >delete_forever</i></span></a>
                                        
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                        <?php } ?>
                        
                    </div>
                </div>
               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



