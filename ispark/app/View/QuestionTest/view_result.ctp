

<script>
     function closeWindow() {
        window.location.replace("https://www.teammas.in");
    }
</script>   


<div class="box-content">
    <div class="text-center">
            <h3 class="page-header">Test Paper Result</h3>
    </div>
           
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom responstable">
        <tr>
            <th>User Name</th>
            <th><?php echo $user_det['0']['tqu']['user_name']; ?></th>
        </tr>
        <tr>
            <th>Test Name</th>
            <th><?php echo $record_para['0']['tqp']['heading_name']; ?></th>
        </tr>
        <tr>
            <th>Total Mark</th>
            <th><?php echo $test_det['0']['tqm']['para_total_mark']; ?></th>
        </tr>
        <tr>
            <th>Passing Mark</th>
            <th><?php echo $test_det['0']['tqm']['para_pass_mark']; ?></th>
        </tr>
        <tr>
            <th>Mark Obtained</th>
            <th><?php echo $test_det['0']['tqm']['para_mark_obt']; ?></th>
        </tr>
        <tr>
            <th>Question Attempt</th>
            <th><?php echo $test_det['0']['tqm']['para_attempt_quest']; ?></th>
        </tr>
        <tr>
            <th>Time Taken</th>
             <th><?php echo $test_det['0']['tqm']['para_time_taken']; ?></th>
        </tr>
        <tr>
            <th>Test Result</th>
            <th><span style="color:<?php if($test_det['0']['tqm']['para_test_result']=='Fail') { echo 'red'; } else { echo "green"; }  ?>">
                <?php echo $test_det['0']['tqm']['para_test_result']; ?>  </span>
            </th>
        </tr>
        <tr>
            <td colspan="2" align="center"><a href="#" onclick=" closeWindow();"  class="btn btn-primary btn-new pull-center">Close</a></td>
        </tr>
    </table>
</div>