<?php //print_r($master_report); exit; ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
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
    <div class="col-xs-12">
	<div class="box">
            <div class="box-header">
                <div class="box-name"><span>Master Branch Report</span></div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
		<div class="no-move"></div>
            </div>
	<div class="box-content no-padding">

    <table border='1'  id="table_id">
    <thead>
        <tr class="active">
            <td align="center"><b>Sr. No.</b></td>
            <td align="center"><b>Branch</b></td>
            <td align="center"><b>Company</b></td>
            <td align="center"><b>To be billed</b></td>
            <td align="center"><b>In Processing</b></td>
            <td align="center"><b>Ready for payment or billed</b></td>
            <td align="center"><b>Pyt. for month</b></td>
            <td align="center"><b>Post month</b></td>
            <td align="center"><b>W1</b></td>
            <td align="center"><b>W2</b></td>
            <td align="center"><b>W3</b></td>
            <td align="center"><b>W4</b></td>
            <td align="center"><b>W5</b></td>
            <td align="center"><b>W/2 1</b></td>
            <td align="center"><b>W/2 2</b></td>
            <td align="center"><b>Total</b></td>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; foreach ($master_report as $com=>$comp): 
                        foreach($comp as $branch=>$mr):
            ?>
            <tr class="">
                <td align="center"><?php echo $i; ?></td>
                <td align="center"><?php echo $branch; ?></td>
                <td align="center" class="MasterProcess <?php echo $branch.'##'.$com; ?>"><a href="#"><?php echo $com; ?></a></td>
                <td align="center"><?php echo !empty($mr['Tobebilled'])?round($mr['Tobebilled']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['InProcess'])?round($mr['InProcess']/100000,2):0; ?></td>
                <td align="center" class="MasterDay <?php echo $branch.'##'.$com; ?>"><a href="#"><?php echo !empty($mr['PytReady'])?round($mr['PytReady']/100000,2):0; ?></a></td>
                <td align="center"><?php echo !empty($mr['PayForMonth'])?round($mr['PayForMonth']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['PostMonth'])?round($mr['PostMonth']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['w1'])?round($mr['w1']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['w2'])?round($mr['w2']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['w3'])?round($mr['w3']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['w4'])?round($mr['w4']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['w5'])?round($mr['w5']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['month2']['w1'])?round($mr['month2']['w1']/100000,2):0; ?></td>
                <td align="center"><?php echo !empty($mr['month2']['w2'])?round($mr['month2']['w2']/100000,2):0; ?></td>
                <?php
                    $Total = $mr['Tobebilled']+$mr['InProcess']+$mr['PytReady']+$mr['PayForMonth']
                    +$mr['PostMonth']+$mr['w1']+$mr['w2']+$mr['w3']+$mr['w4']+$mr['w5']+$mr['month2']['w1']+$mr['month2']['w2'];
                    
                    $GTotal += $Total; 
                    $GTTobebilled += $mr['Tobebilled'];
                    $GTInProcess += $mr['InProcess'];
                    $GTPytReady += $mr['PytReady'];
                    $GTPayForMonth += $mr['PayForMonth'];
                    $GTPostMonth += $mr['PostMonth'];
                    $GTw1 += $mr['w1'];
                    $GTw2 += $mr['w2'];
                    $GTw3 += $mr['w1'];
                    $GTw4 += $mr['w4'];
                    $GTw5 += $mr['w5'];
                    $GTMw1 += $mr['month2']['w1'];
                    $GTMw2 += $mr['month2']['w2'];
                ?>
                <td align="center"><?php echo round($Total/100000,2); ?></td>
            </tr>
            </tbody>
        <?php $i++; endforeach;
                    endforeach;  unset($master_report); ?>
            <tr>
                <td colspan="3"><b>Total</b></td>
                <td align="center"><?php echo round($GTTobebilled/100000,2); ?></td>
                <td align="center"><?php echo round($GTInProcess/100000,2); ?></td>
                <td align="center"><?php echo round($GTPytReady/100000,2); ?></td>
                <td align="center"><?php echo round($GTPayForMonth/100000,2); ?></td>
                <td align="center"><?php echo round($GTPostMonth/100000,2); ?></td>
                <td align="center"><?php echo round($GTw1/100000,2); ?></td>
                <td align="center"><?php echo round($GTw2/100000,2); ?></td>
                <td align="center"><?php echo round($GTw3/100000,2); ?></td>
                <td align="center"><?php echo round($GTw4/100000,2); ?></td>
                <td align="center"><?php echo round($GTw5/100000,2); ?></td>
                <td align="center"><?php echo round($GTMw1/100000,2); ?></td>
                <td align="center"><?php echo round($GTMw2/100000,2); ?></td>
                <td align="center"><b><?php echo round($GTotal/100000,2); ?></b></td>
            </tr>
    
    </table>
<div id="master_report_day"></div>
<div id="master_report_day_brief"></div>
<div id="master_report_event_brief"></div>
</div>
</div>
</div>
</div>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        
        //$('#table_id').dataTable();
        
        $('.MasterDay').click(function()
        {
           var postdata = this.className.split('MasterDay').join('').trim();
           var postdataArray=postdata.split("##");
           var branch = postdataArray[0];
           var company = postdataArray[1];
           
           $.ajax({type:"Post",datatype:'json',cache:false,url: "MasterBranchReports/get_master_day_wise",data:{branch:branch,company:company}, success: function(data){
           $('#master_report_day').html(data);
           $('#master_report_day_brief').html('');
           $('#master_report_event_brief').html('');
            }});
           //$('#master_day_wise').dataTable();
        });
        
        $('.MasterProcess').click(function()
        {
           var postdata = this.className.split('MasterProcess').join('').trim();
           var postdataArray=postdata.split("##");
           var branch = postdataArray[0];
           var company = postdataArray[1];
           
           $.ajax({type:"Post",datatype:'json',cache:false,url: "MasterBranchReports/get_master_process_wise",data:{branch:branch,company:company}, success: function(data){
           $('#master_report_day').html(data);
           $('#master_report_day_brief').html('');
           $('#master_report_event_brief').html('');
            }});
           //$('#master_day_wise').dataTable();
        });
        
    });

$(document).on('click', '.MasterProcessBrief', function(){
   var postdata = this.className.split('MasterProcessBrief').join('').trim();
           var postdataArray=postdata.split("##");
           var cost_center = postdataArray[0];
           var month = postdataArray[1];
           
           $.ajax({type:"Post",datatype:'json',cache:false,url: "MasterBranchReports/get_master_process_brief_wise",data:{cost_center:cost_center,month:month}, success: function(data){
           $('#master_report_day_brief').html(data);
            }});
           //$('#master_day_wise_brief').dataTable(); 
});

$(document).on('click', '.MasterEventsBrief', function(){
   var postdata = this.className.split('MasterEventsBrief').join('').trim();
           var postdataArray=postdata.split("##");
           var id = postdataArray[0];
           
           
           $.ajax({type:"Post",datatype:'json',cache:false,url: "MasterBranchReports/get_master_event_brief_wise",data:{id:id}, success: function(data){
           $('#master_report_event_brief').html(data);
            }});
           //$('#master_event_wise_brief').dataTable(); 
});


</script>