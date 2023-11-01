<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
  function getWeitageTotal(val)
  {
    const table = document.getElementById('newRow');
    const rowCount = table.rows.length;
      let totalWeitage = 0;
      for (let i = 1; i < rowCount; i++) 
      { 
        const weitage = $(`select[name="weitage${i}"]`).val();
        const weitageValue = parseFloat(weitage);
        totalWeitage += weitageValue;
      }

      const existingText = "Percentage(%)";
      const colorTotalWeitage = `<span style="color: green;">Total = ${totalWeitage}%</span>`;
      $("#tot_weitage").html(`${existingText} ${colorTotalWeitage}`);
      //$("#tot_weitage").text(existingText + '  Total ' + colorTotalWeitage + '%');

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
<?php echo $this->Form->create('PliSystems',array('action'=>'create_weitage','class'=>'form-horizontal','id'=>'weitageform')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Weitage</span>
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
                  <div class="col-sm-3">
                    <label>User</label>
                    <select id="selected_user" name="selected_user" class="form-control">
                        <option value='none'>Select</option>
                    <?php 
                        foreach($users as $key => $user){
                            echo "<option value='".$user['masjclrentry']['EmpCode']."'>".$user['masjclrentry']['EmpName']."</option>";
                        }
                    ?>

                    </select>
                  </div>

                        <div class="col-sm-3">
                              <label>Month</label>
                              <select name="month" id="month" class="form-control" required="">
                                  <option value="">Month</option>
                                  <?php
                                          $TcurMonth = date('M');
                                          if($TcurMonth=='Jan')
                                          {?>
                                              <option value="Dec-<?php echo $curYear-1; ?>">Dec-<?php echo $curYear-1;?></option>
                                          <?php }
                                  ?>
                                  <option value="Jan-<?php echo $curYear; ?>">Jan</option>
                                  <option value="Feb-<?php echo $curYear; ?>">Feb</option>
                                  <option value="Mar-<?php echo $curYear; ?>">Mar</option>
                                  <option value="Apr-<?php echo $curYear; ?>">Apr</option>
                                  <option value="May-<?php echo $curYear; ?>">May</option>
                                  <option value="Jun-<?php echo $curYear; ?>">Jun</option>
                                  <option value="Jul-<?php echo $curYear; ?>">Jul</option>
                                  <option value="Aug-<?php echo $curYear; ?>">Aug</option>
                                  <option value="Sep-<?php echo $curYear; ?>">Sep</option>
                                  <option value="Oct-<?php echo $curYear; ?>">Oct</option>
                                  <option value="Nov-<?php echo $curYear; ?>">Nov</option>
                                  <option value="Dec-<?php echo $curYear; ?>">Dec</option>
                              </select>
                        </div>
                              
                        <div class="col-sm-6">      
                          <label>Is Account Approval Require :</label><br>
                          <label>Yes</label>
                          <input type="radio" id="account_approval" name="account_approval"  value="Yes" required>
                          <label>No</label>
                          <input type="radio" id="account_approval" name="account_approval"  value="No" required>
                        </div>
                                
                </div>
            </div>
        </div>
    </div>	
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
		        <h4 class="page-header" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">Weitage Entry</h4>
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="newRow">
                    <div id="errorDiv" style="color: red;"></div>
                      <tr>
                          <th>Sr. No.</th>
                          <th>Type</th>
                          <th>Particular</th>
                          <th id="tot_weitage">Percentage(%)</th>
                          <th>Action</th>
                      </tr>
                      <tr>
                        <th>1</th>
                        <th>
                            <label>Basic</label>
                            <input type="radio" id="type1" name="type1"  value="Basic" required>
                            <label>Growth</label>
                            <input type="radio" id="type1" name="type1"  value="Growth" required>
                        </th>
                        <th>
                        <input type="text" name="particular1" class="form-control" id="particular1" placeholder="Particular 1" required>
                        </th>
                        <th>
                          <?php //echo $this->Form->input('percentage1',array('label' => false,'empty'=>'Select','options'=>$per_options,'class'=>'form-control','id'=>'percentage','required'=>true)); ?>
                          <select name="weitage1" class="form-control" id="weitage1" required="required" onchange="getWeitageTotal(this.value);">
                            <option value="">Select</option>
                            <?php foreach ($per_options as $key => $value) {?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php }?>

                          </select>
                        </th>
                        
                        <th>
                          <!-- <button onclick="return add_cost_value_grn()"> ADD</button> -->
                          <a href="javascript:void(0);" id="addRow" title="Add field" class="btn pull-left btn-light"> Add <i class="fa fa-plus"></i></a>
                        </th>
                      </tr>
                      
                    </table>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-2">
                            <input type="button" value="Save" name="Save" onclick="save_validate_data()"  class="btn btn-primary pull-right"  />
                        </div>
                        
                    </div>
                    
              </div>
        </div>
    </div>	
</div>

<?php echo $this->Form->end(); ?>


<script type="text/javascript">
   var count_elements = 0;


     // add row
   $("#addRow").click(function () {


      //var child_no = document.getElementById('newRow').children.length;
      var child_no = document.getElementById('newRow').querySelectorAll('tr').length - 1;
      //console.log(child_no);
      child_no+=1;
      child_no1 = child_no+100;

      var html = '';
      html += '<tr id="inputFormRow">';
      html += '<th>'+child_no+'</th>';
      html += '<th><label>Basic</label><input type="radio" id="type'+child_no+'" name="type'+child_no+'"  value="Basic" required><label>Growth</label><input type="radio" id="type'+child_no+'" name="type'+child_no+'"  value="Growth" required></th>';
      html += '<th><input type="text" name="particular'+child_no+'" id="particular'+child_no+'" placeholder="Particular '+child_no+'" class="form-control" required></th>';
      html += '<th><select name="weitage'+child_no+'" class="form-control" id="weitage'+child_no+'" required="required" onchange="getWeitageTotal(this.value);">';
      html += '<option value="">Select</option>';

      <?php
      foreach ($per_options as $key => $value) {
          echo "html += '<option value=\"$key\">$value</option>';";
      }?>

      html += '</select></th>';

      html += '<th><i title="Delete" id="removeRow" type="button"  style="font-size:22px;cursor: pointer;color:red;" class="material-icons">delete_forever</i></th>';
      html += '</tr>';

      

   
       $('#newRow').append(html);

       
   });
   
   // remove row
   $(document).on('click', '#removeRow', function () {
       $(this).closest('#inputFormRow').remove();
   });


   function save_validate_data()
   {
      const table = document.getElementById('newRow');
      const rowCount = table.rows.length;
      const data = [];

      $("#msgerr").remove();

      var user=$("#selected_user").val();
      var month=$("#month").val();
      var account_approval=$("#account_approval:checked").val();
      
      if(user == 'none'){
     
        $("#selected_user").focus();
        $("#selected_user").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select user.</span>");
        return false;
      }
      else if(month ===""){
        $("#month").focus();
        $("#month").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
      }else if(!account_approval)
      {
        $("#account_approval").focus();
        $("#account_approval").after("<span id='msgerr' style='color:red;font-size:11px;'>Approval is rquire or not.</span>");
        return false;
      }
      let totalWeitage = 0;

      for (let i = 1; i < rowCount; i++) 
      { 
        const type = $(`input[name="type${i}"]:checked`).val();
        const particular = $(`input[name="particular${i}"]`).val();
        const weitage = $(`select[name="weitage${i}"]`).val();
        const weitageValue = parseFloat(weitage);

        totalWeitage += weitageValue;
        if (!type){
       
          $("#type" + i).focus();
          $("#type" + i).after("<span id='msgerr' style='color:red;font-size:11px;'>Please select type.</span>");

          return false;

        }else if(!particular){

          $(`input[name="particular${i}"]`).focus();
          $(`input[name="particular${i}"]`).after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Particular.</span>");
          return false;

        }else if(weitage === '') {

          $(`select[name="weitage${i}"]`).focus();
          $(`select[name="weitage${i}"]`).after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Weitage.</span>");
          return false;
        }
        
        else{

          data.push({ user,month, account_approval,type, particular , weitage});
        }

      }
      //console.log(totalWeitage);

      if(totalWeitage !== 100) {
        $("#msgerr").remove();
        $("#weitage" + (rowCount - 1)).focus();
        $("#weitage" + (rowCount - 1)).after("<span id='msgerr' style='color:red;font-size:11px;'>Total Weitage must be equal to 100.</span>");
        return false;
      }


      const jsonData = JSON.stringify(data);
      console.log(jsonData);
      $.post("create_weitage",{
              data: jsonData 
      },
      function(result)
      {
        alert(result);
        location.reload();
      });

   }
</script>


                 
