<?php if(empty($FinanceYear)) {$FinanceYear = $FinanceYearLogin;}  ?>
<?php echo $this->Form->create('GrnReport',array('enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Business Case Upload Branch Wise</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="form-horizontal">
                <div class="col-sm-9">    
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                
                    <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label> 
                    <div class="col-sm-4">
                       <?php	
                    echo $this->Form->input('BranchId', array('label'=>false,'id'=>'BranchId','empty'=>"Select",'class'=>'form-control','options'=>$branch_master,'value'=>$BranchId,'onchange'=>"get_dash()",'required'=>true));
                    ?> 
                    </div>
                    
                    <label class="col-sm-1 control-label">Year</label> 
                    <div class="col-sm-2">
                       <?php	
                    echo $this->Form->input('FinanceYear', array('label'=>false,'id'=>'FinanceYear','empty'=>"Select",'class'=>'form-control','options'=>$financeYearArr,'value'=>$FinanceYear,'onchange'=>"get_dash()",'required'=>true));
                    ?> 
                    </div>
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select name="Month" id="Month" class="form-control" onchange="get_dash()" required="">
                            <option value="">Select</option>
                            <option value="Jan" <?php if($Month=='Jan') { echo "Selected";} ?>>Jan</option>
                            <option value="Feb" <?php if($Month=='Feb') { echo "Selected";} ?>>Feb</option>
                            <option value="Mar" <?php if($Month=='Mar') { echo "Selected";} ?>>Mar</option>
                            <option value="Apr" <?php if($Month=='Apr') { echo "Selected";} ?>>Apr</option>
                            <option value="May" <?php if($Month=='May') { echo "Selected";} ?>>May</option>
                            <option value="Jun" <?php if($Month=='Jun') { echo "Selected";} ?>>Jun</option>
                            <option value="Jul" <?php if($Month=='Jul') { echo "Selected";} ?>>Jul</option>
                            <option value="Aug" <?php if($Month=='Aug') { echo "Selected";} ?>>Aug</option>
                            <option value="Sep" <?php if($Month=='Sep') { echo "Selected";} ?>>Sep</option>
                            <option value="Oct" <?php if($Month=='Oct') { echo "Selected";} ?>>Oct</option>
                            <option value="Nov" <?php if($Month=='Nov') { echo "Selected";} ?>>Nov</option>
                            <option value="Dec" <?php if($Month=='Dec') { echo "Selected";} ?>>Dec</option>
                        </select>
                    </div>
                    </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Upload CSV File Only</label>
                    <div class="col-sm-3">
                    <?php	
                    echo $this->Form->input('file', array('label'=>false,'type' => 'file','required'=>true));
                    ?>
                    </div>
                    <div class="col-sm-2">
                        <input type="Submit" name="Submit" value="Upload" class="btn btn-primary btn-label-left" />
                        <a href="/ispark/Menuisps/sub?AX=NTk=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
                    </div>
		</div>
                
                <div class="form-group">
                    <?php echo $html; ?>
                </div>
                
		<div class="clearfix"></div>
            </div>
            </div>        
            <div class="col-sm-3">    
            <div class="box-content" id="business_upload_check">
                <table border="2">
                    <tr>
                        <th>Branch</th>
                        <th>Status</th>
                    </tr>
                    <?php 
                            foreach($SalaryCheck as $k=>$v)
                            {
                                echo "<tr>";
                                    echo "<td>".$k."</td>";
                                    echo "<td>";
                                    if($v=='Yes')  
                                    {
                                      echo '<font color="green">Yes</font>';
                                    }
                                    else
                                    {
                                        echo '<font color="red">No</font>';
                                    }
                                    echo "</td>";
                                echo "</tr>";
                            }
                    ?>
                </table>
            </div>
            </div>    
                </div>    
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<script>
    function get_dash()
    {
        var FinanceYear = $('#FinanceYear').val();
        var Month = $('#Month').val();
        var BranchId = $('#BranchId').val();
        if(FinanceYear!='' && Month!='')
        {
            $.post("get_dash_business",
            {
             FinanceYear:FinanceYear,
             Month:Month,
             BranchId:BranchId
            },
            function(data,status){
                   $('#business_upload_check').html(data);
            }); 
        }
    }
</script>