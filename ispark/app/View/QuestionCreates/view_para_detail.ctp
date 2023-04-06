<script>
    
function checkNumber(val,evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        else if(val.length>1 &&  (charCode> 47 && charCode<58) || charCode == 8 || charCode == 110 )
        {
            if(val.indexOf(".") >= 0 && val.indexOf(".") <= 3 || charCode == 8 ){
                 
            }
        }
	return true;
}
   
function validate_form()
{
    var head_name=$('#head_name').val();
    var para_graph=$('#para_id').val();
    var total_time=$('#para_time').val();
    var passing=$('#passing_mark').val();
    
    var question=$('#quest').val();
    var ans_type=$('#ans_type').val();
    var options=$('#option1').val();
    var ans=$('#ans').val();
    var mark=$('#marks').val();
    
    var ans_type = false;
    try{
        ans_type = document.querySelector('input[name = "ans_type"]:checked').value;
    }
    catch(err)
    {
        ans_type = false;
    }
    
    
    if(head_name=='')
    {
        alert('Please Fill Heading Name');
        return false;
    }
    else if(para_graph=='')
    {
        alert('Please Fill Paragraph');
        return false;
    }
    else if(total_time=='')
    {
        alert('Please Fill Total Time');
        return false;
    }
    else if(total_time=='00:00')
    {
        alert('Please Fill Right Time');
        return false;
    }
    else if(passing=='')
    {
        alert('Please Fill Passing Percent');
        return false;
    }
    else if(passing==0)
    {
        alert('Passing % should be Greater Than 0');
        return false;
    }
    else if(passing>100)
    {
        alert('Passing % should not be Greater Than 100%');
        return false;
    }
    else if(question=='')
    {
        alert('Please Fill Question');
        return false;
    }
    else if(ans_type==false)
    {
        alert('Please Choose Select or Multiple Choice Question');
        return false;
    }
    else if(options=='')
    {
        alert('Please Fill Field Options');
        return false;
    }
    else if(ans=='')
    {
        alert('Please Fill All Answers');
        return false;
    }
    else if(mark=='')
    {
        alert('Please Fill Marks');
        return false;
    }
    else
    {
        return true;
    }
    
}
   
    
function add_quest()
{
    if(validate_form())
    {
        var head_name=$('#head_name').val();
        var para_graph=$('#para_id').val();
        var total_time=$('#para_time').val();
        var passing=$('#passing_mark').val();
    
        var question=$('#quest').val();
        var ans_type = document.querySelector('input[name = "ans_type"]:checked').value;
        var options=$('#option1').val();
        var ans=$('#ans').val();
        var mark=$('#marks').val();
        var unique_id=$('#unique_id').val();
        
        $.post("QuestionCreates/add_question",
        {
            head_name:head_name,
            para_graph:para_graph,
            total_time:total_time,
            passing:passing,
            question:question,
            ans_type:ans_type,
            options:options,
            ans:ans,
            mark:mark,
            unique_id:unique_id
        },
        function(data)
        {
            var json = jQuery.parseJSON(data);
            if(json['status']=='Success')
            {
                var table = document.getElementById("tbl1");
                var no_of_row = table.length;
                var row = table.insertRow(no_of_row);
                row.innerHTML = json['field'];
                row.id = json['id'];
                $('#total').val(json['total']);
                
                
    
                question=$('#quest').val('');
                options=$('#option1').val('');
                ans=$('#ans').val('');
                mark=$('#marks').val('');
                
            }
            else
            {
                alert(json['msg']);
            }
        });
    }
    
    
       
}

function delete_quest(row_id)
{
    $.post("QuestionCreates/delete_question",
        {
            row_id:row_id
        },
        function(data)
        {
            var json = jQuery.parseJSON(data);
            if(json['status']=='Success')
            {
                var row = document.getElementById(row_id);
                row.innerHTML = ''; 
                $('#total').val(json['total']);
            }
            else
            {
                alert(json['msg']);
            }
        });
}




</script>

<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		
	</div>
</div>
<?php echo $this->Form->create('QuestionCreates',array('class'=>'form-horizontal','url'=>'save_question')); ?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Paragraph View</span>
		</div>
            
            <div class="no-move"></div>
            </div>
            <div class="box-content">
		<h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Heading Name</label>
                    <div class="col-sm-4">
                        <input type="text" id="head_name" name="head_name" class="form-control" placeholder="Write Name Here" value="<?php echo $record_para['0']['tqp']['heading_name']; ?>" required="" />
                    </div>
                    <label class="col-sm-1 control-label">Time</label>
                    <div class="col-sm-2">
                        <input type="text" id="para_time" name="para_time" class="timepicker" placeholder="0" value="<?php echo $record_para['0']['tqp']['para_time']; ?>" required="" />
                    </div>
                    <label class="col-sm-1 control-label">Pass %</label>
                    <div class="col-sm-1">
                        <input type="text" id="passing_mark" name="passing_mark" class="form-control" placeholder="0" onkeypress="return checkNumber(this.value,event)" value="<?php echo $record_para['0']['tqp']['passing_mark']; ?>"  required="" />
                    </div>
                </div>
                <div class="form-group">    
                    <label class="col-sm-2 control-label">ParaGraph</label>
                    <div class="col-sm-8">
                        <textarea id="para_id" name="para_id" class="form-control" placeholder="write your paragraph here..." rows="4"><?php echo $record_para['0']['tqp']['paragraph']; ?></textarea>
                    </div>
                </div>
                <h4 class="page-header">Question List</h4>
                <table id="tbl1" class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th style="width:300px;">Question</th>
                        <th>Options Separated by #</th>
                        <th style="width:110px;">Type</th>
                        <th style="width:30px;">Marks</th>
                        <th>Answers (Multi-Choice Separated by #)</th>
<!--                        <th style="width:50px;">Action</th>-->
                    </tr>
                    </thead>
                    <tbody>
<!--                        <tr>
                            <td><textarea type="text" id="quest" name="question" rows="3" class="form-control" placeholder="Question" ></textarea>
                            </td>
                            <td>
                                <textarea type="text" id="option1" name="option1" rows="3" class="form-control" placeholder="write multi choice options here e.g. a#b#c" ></textarea>
                            </td>
                            <td>
                                <input type="radio" id="ans_type1" name="ans_type" value="single choice" />&nbsp;Single Choice &nbsp;
                                <input type="radio" id="ans_type2" name="ans_type" value="multiple choice" />&nbsp; Multiple Choice &nbsp;
                            </td>
                            <td>
                                <input type="text" id="marks" name="marks" class="form-control" onkeypress="return checkNumber(this.value,event)" placeholder="0"  />&nbsp;&nbsp;
                            </td>
                            
                            <td>
                                <textarea type="text" id="ans" name="ans" rows="3" class="form-control" placeholder="write multi choice answers here e.g. a#c" ></textarea>
                            </td>
                            <td>
                                <input type="button" id="add" name="add" value="ADD" onclick="add_quest()" class="btn btn-primary btn-new pull-center" style="margin-left: 5px;" /> 
                            </td>
                        </tr>-->
                        
                        <?php  $total = 0; $srno=1;
                                foreach($record_all as $record)
                                {
                                    echo '<tr id="'.$record['tqt']['quest_id'].'">';
                                    echo '<td>'.$srno++.'</td>';
                                    echo '<td>'.$record['tqt']['quest'].'</td>';
                                    echo '<td>'.$record['tqt']['opt1'].'</td>';
                                    echo '<td>'.$record['tqt']['ans_type'].'</td>';
                                    echo '<td>'.$record['tqt']['marks'].'</td>';
                                    echo '<td>'.$record['tqt']['ans1'].'</td>';
                                    //echo '<td>'.'<a href="#" onclick="delete_quest('."'{$record['tqt']['quest_id']}'".');" class="btn btn-primary btn-new pull-center">Delete</a>'.'</td>';
                                    echo '</tr>';
                                    $total += $record['tqt']['marks'];
                                }
                        ?>
                    </tbody>
                </table>
                
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Total </label>
                    <div class="col-sm-2">
                        <input type="text" id="total" name="total" class="form-control" placeholder="Total" value="<?php echo $total;?>" required="" />
                    </div>
                    
       
                    <div class="col-sm-1">
                        <a href="view_para" class="btn btn-primary btn-new pull-center" >Back</a>
                    </div>
                </div>
                    
                
                
                
            </div>
	</div>
        
    </div>
</div>


<input type="hidden" id="unique_id" name="unique_id" value="<?php echo $quest_unique_id; ?>" />
<?php echo $this->Form->end(); ?>	

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot.'app/webroot/css/'; ?>dist/css/chung-timepicker.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="<?php echo $this->webroot.'app/webroot/css/'; ?>dist/js/chung-timepicker.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
$('#para_time').chungTimePicker({
        callback: function(e) {
                //alert('Callback');
        }
});
</script>
