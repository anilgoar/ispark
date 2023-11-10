<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
  function getWeitage(month){
    $("#weitage_table").hide();
    var user=$("#selected_user").val();

    
    $.post("<?php echo $this->webroot;?>PliSystems/get_weitage_achievment",{'EmpCode':user,'Month':month,'Approval':'1'}, function(data) {
        if(data !=""){
            $("#weitage_table").show();
            $("#weitage_table").html(data);
        }
        else{
            $("#weitage_table").hide();
        }
    });
}

function isNumberKey(e,t)
{
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
        return true;

    }
    catch (err) {
        alert(err.Description);
    }
}

function calculatePercentage(achievedValue,targetValue) 
{

  if (!isNaN(achievedValue)) {
    console.log("weitage"+targetValue);
    console.log("achivement"+achievedValue);
    const percentageAchievement = (achievedValue * targetValue) / 100;
    return `${percentageAchievement.toFixed(2)}`;
    //return `Percentage: ${percentageAchievement.toFixed(2)}%`;
  } 
}

function total_pli_score(achivement,weitage,div)
{
    const percentageText = calculatePercentage(achivement, weitage);
    $("#score"+div).val(percentageText);

    var totalScore = calculateTotalScore();
    const existingText = "Score:";
    const colorTotalWeitage = `<span style="color: green;">Total = ${totalScore}%</span>`;
    const totalText = `${existingText} ${colorTotalWeitage}`;
    $("#total_score").html(totalText);
}


function calculateTotalScore() {
    var totalScore = 0;
    
    const N = findMaxScoreElement();
    console.log(N);
    for (var i = 1; i <= N; i++) {
        var score = parseFloat($("#score" + i).val());
        if (!isNaN(score)) {
            totalScore += score;
        }
    }
    
    return totalScore;
}


function findMaxScoreElement() {
    var maxN = 0;

    // Loop through all elements with IDs starting with "score"
    $('[id^="score"]').each(function() {
        var id = $(this).attr('id');
        var num = parseInt(id.replace('score', ''), 10);
        if (!isNaN(num) && num > maxN) {
            maxN = num;
        }
    });

    return maxN;
}

function reporting_name(EmpCode)
  {
    $("#reporting_name").hide();

    $.post("<?php echo $this->webroot;?>PliSystems/get_reporting_name",{'EmpCode':EmpCode}, function(data) {
          if(data !=""){
              $("#reporting_name").show();
              $("#reporting_name").html(data);
          }
          else{
              $("#reporting_name").hide();
              
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
<?php echo $this->Form->create('PliSystems',array('action'=>'create_weitage','class'=>'form-horizontal','id'=>'weitageform')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Achievement Approval</span>
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
                    <select id="selected_user" name="selected_user" class="form-control" onchange="reporting_name(this.value);">
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
                        <select name="month" id="month" class="form-control" required="" onchange="getWeitage(this.value);">
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
                  <div class="col-sm-2" id="reporting_name"></div>
                  <div class="col-sm-1" style="margin-top:10px;">
                    <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                  </div>
       
                </div>
            </div>
        </div>
    </div>	
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12" id="weitage_table">
        
    </div>	
</div>

<?php echo $this->Form->end(); ?>


<script type="text/javascript">
   var count_elements = 0;


   function save_validate_data()
   {
      const table = document.getElementById('achivement_table');
      const rowCount = table.rows.length;
      const data = [];

      $("#msgerr").remove();

      var user=$("#selected_user").val();
      var month=$("#month").val();
      
      if(user == 'none'){
     
        $("#selected_user").focus();
        $("#selected_user").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select user.</span>");
        return false;
      }
      else if(month ===""){
        $("#month").focus();
        $("#month").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
      }
      let totalWeitage = 0;
      console.log(rowCount);
      for (let i = 1; i < rowCount; i++) { // Start from 1 to skip the header row
        var achievementField = $(`input[name="achivement${i}"]`);
        const achivement = $(`input[name="achivement${i}"]`).val();
        const score = $(`input[name="score${i}"]`).val();
        console.log(score);
        var id = achievementField.attr('id').split('_')[1];
        
        if(!achivement)
        {
          $("#achivement_" + id).focus();
          $("#achivement_" + id).after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Achivement.</span>");
          return false;

        }
        else{
          data.push({ id,score,achivement });
        }

        
        

      }
      //console.log(data);

      const jsonData = JSON.stringify(data);
      console.log(jsonData);
      $.post("create_achivement",{
              data: jsonData 
      },
      function(result)
      {
        alert(result);
        getWeitage(month);
        //location.reload();
      });

   }

    function approve_data()
    {
        const table = document.getElementById('achivement_table');
        const rowCount = table.rows.length;
        const data = [];

        for (let i = 1; i < rowCount; i++) 
        { 
            const approval_data = $(`input[name="approve_id${i}"]`).val();

            data.push({ approval_data });
        
        }

        const jsonData = JSON.stringify(data);
        console.log(jsonData);
        $.post("achivement_approval",{
                data: jsonData 
        },
        function(result)
        {
            alert(result);
            location.reload();
        });
    }
</script>


                 
