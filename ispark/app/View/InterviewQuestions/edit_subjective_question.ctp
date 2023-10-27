
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/froala_editor.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/froala_style.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/code_view.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/image_manager.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/image.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/table.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/video.css"/>


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
                    <span>Subjective Question</span>
		        </div>
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
		    <div class="no-move"></div>
        </div>
           
            <div class="box-content box-con">
                
                <?php echo $this->Form->create('InterviewQuestions',array('action'=>'edit_subjective_question','class'=>'form-horizontal','id'=>'form1')); ?>
                <span><?php echo $this->Session->flash(); ?></span>

                <div class="form-group">
                    <div class="col-sm-3">
                    </div>

                    <div class="col-sm-6">
                        <div class="col-xs-12">
                            <label>Question Paper Name</label>
                            <?php echo $this->Form->input('ques_paper_id',array('label' => false,'options'=>$paper_name,'empty'=>'Select','class'=>'form-control','id'=>'ques_paper_name','value'=>$edit_sub['InterviewQuiz']['ques_paper_id'],'required'=>true)); ?>
                            <input type="hidden" name="update_id" value='<?php echo $edit_sub['InterviewQuiz']['id']; ?>'>
                        </div>
                        <div class="col-xs-12" style="margin-top: 30px;">
                            <textarea id='rich_edit' placeholder="for example : What attracts you most to our company?" name='question' class='form-control'>
                            <?php echo $edit_sub['InterviewQuiz']['question']; ?>
                            </textarea>
                        </div>
                        <div class="col-xs-12" style="margin-top: 10px;">
                            <input type="submit"  value="Update" class="btn pull-right btn-primary btn-new" style='width: 100px;'>
                            <input onclick='return window.location="<?php echo $this->webroot;?>InterviewQuestions/view_question"' type="button" value="Back" class="btn btn-primary btn-new pull-left" style="margin-left: 5px;" />
                        </div>
                    </div>

                    <div class="col-sm-3">
                    </div>
                </div> 
                
                           
                <?php echo $this->Form->end(); ?>
                 
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
       html += '<input type="text" class="form-control" name="data[InterviewQuestions][choice'+child_no+']" placeholder="Choice '+child_no+'"  required>';
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
