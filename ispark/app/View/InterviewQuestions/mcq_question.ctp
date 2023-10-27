
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/froala_editor.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/froala_style.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/code_view.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/image_manager.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/image.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/table.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/video.css"/>

<script>
  function getQuestion(name,type){ 

    $.post("<?php echo $this->webroot;?>InterviewQuestions/getquestion",{name:name,type:type}, function(data){
        $("#tabledata").html(data);
    });  
  }
  function radio(value)
  {
    if(value=='self')
    {
      $("#answer").prop( "disabled", true);
      $('#answer').val('');
    }else{

      $("#answer").prop( "disabled",false);

    }
    
  }
  function save_quizz_data()
  {
      $("#msgerr").remove();
      var form = $("#addquizform");
      var url = form.attr('action');
      //var formData = new FormData(form[0]);
      var formData = $('#addquizform').serializeArray();
      
      var ques_paper_name=$("#ques_paper_name").val();
      var custom=$("#custom:checked").val();
      var answer=$("#answer").val();
      var choice1=$("#choice1").val();
      var choice2=$("#choice2").val();
      var choice3=$("#choice3").val();
      var choice4=$("#choice4").val();
  

      if(ques_paper_name ===""){
        $("#ques_paper_name").focus();
        $("#ques_paper_name").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select paper name.</span>");
        return false;
      }
      else if(custom == undefined)
      {
          alert('Please Select Radio.');
          return false;
      }
      else if(custom == 'answer')
      {
          if(answer ==="")
          {
            $("#answer").focus();
            $("#answer").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Answer.</span>");
            return false;
          }
      }
      
      else if(choice1 ==="")
      {
          $("#choice1").focus();
          $("#choice1").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Choice 1.</span>");
          return false;
      }
      else if(choice2 ==="")
      {
          $("#choice2").focus();
          $("#choice2").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Choice 2.</span>");
          return false;
      }
      else if(choice3 ==="")
      {
          $("#choice3").focus();
          $("#choice3").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Choice 3.</span>");
          return false;
      }
      else if(choice4 ==="")
      {
          $("#choice4").focus();
          $("#choice4").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Choice 4.</span>");
          return false;
      }

      $.post("mcq_question",{
              data: formData // serializes the form's elements.
      },
      function(result)
      {
          if(result==='1')
          {
              alert('Question Add Successfully.');
              $('#addquizform').trigger("reset");
              $('#ques_paper_name').val(ques_paper_name);
              getQuestion(ques_paper_name);
              //location.reload();
          }
          else
          {
              alert(result);
              console.log(result);
          }
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
            <div class="box-header">
                <div class="box-name">
                    <span>Multiple Choice Question</span>
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
                <?php echo $this->Form->create('InterviewQuestions',array('action'=>'mcq_question','class'=>'form-horizontal','id'=>'addquizform')); ?>

                <div class="form-group">
                        <div class="col-sm-6">
                            <div class="col-xs-12">
                                <label>Question Paper Name</label>
                                <?php //echo $this->Form->input('ques_paper_id',array('label' => false,'options'=>$paper_name,'empty'=>'Select','class'=>'form-control','id'=>'ques_paper_name','required'=>true)); ?>
                                <select id="ques_paper_name" name="ques_paper_id" autocomplete="off" class="form-control" required onchange="getQuestion(this.value,'MCQ')">
                                <option value="">Select</option>
                                  <?php foreach($paper_name as $paper)
                                  { 
                                      echo "<option value=".$paper['QuestionPaper']['id'].">".$paper['QuestionPaper']['paper_name']."</option>";
                                  }?>
                                </select>
                            </div>
                            <div class="col-xs-12">
                              <label>Question Type</label>
                              <?php echo $this->Form->input('quest_type',array('label' => false,"name"=>"quest_type",'options'=>['Democratic'=>'Democratic','Let_it_be'=>'Let it be','Situational'=>'Situational','Authoritative'=>'Authoritative','Compromising'=>'Compromising','Collaborating'=>'Collaborating','Competing'=>'Competing','Avoiding'=>'Avoiding','Accomodating'=>'Accomodating','Good'=>'Good','Average'=>'Average','Below_Average'=>'Below Average','Poor'=>'Poor'],'empty'=>'Select','class'=>'form-control','id'=>'quest_type','required'=>true)); ?>
                            </div>
                            <div class="col-xs-12" style="margin-top: 30px;">
                                <textarea id='rich_edit'  placeholder="for example : What attracts you most to our company?" name='question' class='form-control'>
                                </textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="col-xs-12">
                            <input type="radio" id="custom" name="custom" onchange="radio(this.value)" value="self">
                            <label>For Self</label>
                            <input type="radio" id="custom" name="custom" onchange="radio(this.value)" value="answer">
                            <label>Custom</label><br>

                              <label>Correct Answer</label>
                              <?php //echo $this->Form->input('answer',array('label' => false,'class'=>'form-control','id'=>'answer','required'=>true)); ?>
                              <input type="text" name="answer" id="answer" class="form-control" placeholder="Correct Answer" required>
                            </div>
                          <div id="newRow">
                            <div class="col-xs-12">
                              <label>Choice 1</label>
                              <?php //echo $this->Form->input('choice1',array('label' => false,'class'=>'form-control','id'=>'choice','placeholder'=>'Choice 1','required'=>true)); ?>
                              <input type="text" name="choice1" id="choice1" class="form-control" placeholder="Choice 1" required>
                            </div>
                            <div class="col-xs-12">
                              <label>Choice 2</label>
                              <?php //echo $this->Form->input('choice2',array('label' => false,'class'=>'form-control','id'=>'choice','placeholder'=>'Choice 2','required'=>true)); ?>
                              <input type="text" name="choice2" id="choice2" class="form-control" placeholder="Choice 2" required>
                            </div>
                            <div class="col-xs-12">
                              <label>Choice 3</label>
                              <?php //echo $this->Form->input('choice3',array('label' => false,'class'=>'form-control','id'=>'choice','placeholder'=>'Choice 3','required'=>true)); ?>
                              <input type="text" name="choice3" id="choice3" class="form-control" placeholder="Choice 3" required>
                            </div>
                            <div class="col-xs-12">
                              <label>Choice 4</label>
                              <?php //echo $this->Form->input('choice4',array('label' => false,'class'=>'form-control','id'=>'choice','placeholder'=>'Choice 4','required'=>true)); ?>
                              <input type="text" name="choice4" id="choice4" class="form-control" placeholder="Choice 4" required>
                            </div>
                           
                          </div>
                            
                            <div class="col-xs-12">
                                <a href="javascript:void(0);" id="addRow" title="Add field" class="btn pull-left btn-light"> Add Another Answer <i class="fa fa-plus"></i></a>
                            </div>

                            <div class="col-xs-12">
                                <input type="button" value="Save" class="btn btn-primary btn-new" onclick="return save_quizz_data();" style='width: 100px;'>
                                <input onclick='return window.location="<?php echo $this->webroot;?>InterviewQuestions"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                            </div>
                        </div>

                        
                    
                </div> 
                
                           
                <?php echo $this->Form->end(); ?>
                 
            </div>
            <br>
            <div class="box-content box-con" id="tabledata">
            
            </div>

            
            
        </div>
    </div>	
</div>

<?php //echo $this->Html->script('jquery.timepicker');
echo $this->Html->script('rich_textbox/js/froala_editor.min');
echo $this->Html->script('rich_textbox/js/plugins/align.min');
echo $this->Html->script('rich_textbox/js/plugins/code_beautifier.min');
echo $this->Html->script('rich_textbox/js/plugins/code_view.min');
echo $this->Html->script('rich_textbox/js/plugins/draggable.min');
echo $this->Html->script('rich_textbox/js/plugins/image.min');
echo $this->Html->script('rich_textbox/js/plugins/image_manager.min');

echo $this->Html->script('rich_textbox/js/plugins/link.min');
echo $this->Html->script('rich_textbox/js/plugins/lists.min');
echo $this->Html->script('rich_textbox/js/plugins/paragraph_format.min');
echo $this->Html->script('rich_textbox/js/plugins/paragraph_style.min');
echo $this->Html->script('rich_textbox/js/plugins/table.min');
echo $this->Html->script('rich_textbox/js/plugins/video.min');
echo $this->Html->script('rich_textbox/js/plugins/url.min');

echo $this->Html->script('rich_textbox/js/plugins/entities.min');
 ?>

<script>
    (function () {
      const editorInstance = new FroalaEditor('#rich_edit', {
        enter: FroalaEditor.ENTER_P,
        placeholderText: null,
        toolbarText: null,
        events: {
          initialized: function () {
            const editor = this
            this.el.closest('form').addEventListener('submit', function (e) {
            //   console.log(editor.$oel.val())
            //   e.preventDefault()
            })
          },
          'image.beforeUpload': function (files) {
            const editor = this
            if (files.length) {
              var reader = new FileReader()
              reader.onload = function (e) {
                var result = e.target.result
                editor.image.insert(result, null, null, editor.image.get())
              }
              reader.readAsDataURL(files[0])
            }
            return false
          }
        }
      })
    })()
  </script>
   <!-- let editor = new FroalaEditor('#rich_edit', {}, function () {

alert(editor.html.get());
console.log(editor.html.get())
}); -->

<script type="text/javascript">
   var count_elements = 0;


     // add row
   $("#addRow").click(function () {


      var child_no = document.getElementById('newRow').children.length;
      child_no+=1;
      child_no1 = child_no+100;

       var html = '';
       html += '<div id="inputFormRow">';

       html += '<div class="col-xs-12">';
       html += '<label>Choice '+child_no+'</label>';
       html += '<input type="text" class="form-control" name="choice'+child_no+'" placeholder="Choice '+child_no+'"  required>';
       html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>'; 
       html += '</div>';

       html += '</div>';

   
       $('#newRow').append(html);

        // 👉�? 4
       
   });
   
   // remove row
   $(document).on('click', '#removeRow', function () {
       $(this).closest('#inputFormRow').remove();
   });
</script>
