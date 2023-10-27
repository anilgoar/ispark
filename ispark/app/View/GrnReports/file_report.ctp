<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("<?php echo $this->webroot;?>/ExpenseEntries/get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text='<option value="">Select</option><option value="All">All</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#subhead").empty();
                $("#subhead").html(text);
                
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
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Filing Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-group has-success has-feedback">
                 <?php echo $this->Form->create('GrnReports',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','options' => (array('All'=>'All') +$financeYearArr),'empty' => 'Select Year','value'=>$FinanceYearLogin,'required'=>true));
                                ?>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-3">
                                <?php	$month = array('All'=>'All','Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                    echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','options' => $month,'empty' => 'Select Month','required'=>true));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Head</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('head', array('label'=>false,'class'=>'form-control','options' => $head,'empty' => 'Select Head','id'=>'head','onChange'=>"getSubHeading()",'required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Sub Head</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('subhead', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Select Sub Head','id'=>'subhead'));
                                ?>
                        </div>
                        <label class="col-sm-1 control-label">Expense Mode</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('expenseEntryType', array('label'=>false,'class'=>'form-control','options' => array('All'=>'All','Imprest'=>'Imprest','Vendor'=>'Non Imprest'),'empty' => 'Select','required'=>true));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label"></label>
                        <label class="col-sm-3 control-label">Grn No</label>
                        <div class="col-sm-3">
                            <?php	
                                    echo $this->Form->input('GrnNo', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'GrnNo'));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label"></label>
                        <label class="col-sm-3 control-label">Unique No</label>
                        <div class="col-sm-3">
                            <?php	
                                    echo $this->Form->input('UniqueId', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'UniqueId'));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                           <button class="btn btn-primary btn-label-left" onClick="return imrest_validate12('show');">Show</button>
                        </div>
                        <div class="col-sm-1">
                           <button class="btn btn-primary btn-label-left" onClick="return imrest_validate12('Export');">Export</button>
                        </div>
                        <div class="col-sm-1">
                           <a href="/ispark/Menuisps/sub?AX=MTMx&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OUSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
                        </div>
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                    </div>
		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content" id="data">
                <table border="2" class="table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>GRN</th>
                            <th>Unique No for Filling</th>
                            <th>Branch</th>
                            <th>Exp. Type</th>
                            <th>Year Month</th>
                            <th>Exp. Head</th>
                            <th>Exp. SubHead</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
                                foreach($ExpenseReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['em']['GrnNo']."</td>";
                                        echo "<td>".$exp['em']['UniqueId']."</td>";
                                        echo "<td>".$exp['bm']['Branch']."</td>";
                                        echo "<td>".$exp['em']['ExpenseEntryType']."</td>";
                                        echo "<td>".$exp['em']['FinanceYear'].'  '.$exp['em']['FinanceMonth']."</td>";
                                        echo "<td>".$exp['hm']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['shm']['SubHeadingDesc']."</td>";
                                        echo "<td>".$exp['em']['Amount']."</td>";
                                        
                                    echo "</tr>";
                                }
                              
                        ?>
                    </tbody>
                </table>    
            
		

		
					
		
            <div class="clearfix"></div>
            <div class="form-group">
                    
            </div>
            </div>
        </div>
    </div>
</div>

