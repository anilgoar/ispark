
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/froala_editor.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/froala_style.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/code_view.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/image_manager.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/image.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/table.css"/>
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>rich_textbox/css/plugins/video.css"/>

<script>

function Questionaction(Id){
   
   if(confirm("Are you sure you want to delete this question?")){
       //window.location="<?php// echo $this->webroot;?>InterviewQuestions/delete_question?Id="+Id;
        $.post("delete_marking",
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
            <div class="box-header">
                <div class="box-name">
                    <span>Marking Formula</span>
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
                      <div class="col-sm-12">
                          <table class = 'table table-striped table-hover  responstable' style='margin-top:-10px;' >
                          
                            <tr>
                              <thead>
                                <th>Democratic</th>
                                <th>Let it be</th>
                                <th>Situational</th>
                                <th>Authoritative</th>
                                <th>Compromising</th>
                                <th>Collaborating</th>
                                <th>Competing</th>
                                <th>Avoiding</th>
                                <th>Accomodating</th>
                                <th>Good</th>
                                <th>Average</th>
                                <th>Below Average</th>
                                <th>Poor</th>
                                <th>Result</th>
                                <th>Action</th>
                              </thead>
                            </tr>
                            <?php foreach($data as $dt){ ?>
                              
                              <tr id="<?php echo $dt['InterviewMarking']['id'];?>">
                                <td><?php echo $dt['InterviewMarking']['Democratic']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Let_it_be']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Situational']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Authoritative']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Compromising']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Collaborating']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Competing']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Avoiding']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Accomodating']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Good']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Average']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Below_Average']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['Poor']; ?></td>
                                <td><?php echo $dt['InterviewMarking']['result']; ?></td>
                                <td><i title="Delete" onclick="Questionaction('<?php echo $dt['InterviewMarking']['id'];?>');" style="font-size:20px;cursor: pointer;" class="material-icons">delete_forever</i></td>
                              </tr>
                            <?php }?>
                              <form action="marking_formula" method="post">
                              <tr>
                                  <td><input type="text" name="democratic" required style="width: 60px;"></td>
                                  <td><input type="text" name="let_it_be" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="situational" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="authoritative" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Compromising" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Collaborating" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Competing" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Avoiding" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Accomodating" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Good" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Average" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Below_Average" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="Poor" id="" required style="width: 60px;"></td>
                                  <td><input type="text" name="result" id="" required style="width: 60px;"></td>
                                  <td><input onclick='return window.location="<?php echo $this->webroot;?>InterviewQuestions"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                                  <input type="submit" class="btn btn-primary btn-new" value="Save"></td>
                              </tr>
                              
                              </form>
                          </table>
                          
                      </div>    
                </div> 

                 
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
