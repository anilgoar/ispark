
        <style>
            table
            {
                 overflow-y:auto;  
            }
        td { 
        text-align: center;

        }
        th { 
        font-size: 12px; 
        text-align: center;

        }
        </style>
        <?php
        ?>
        <script>
        function costcenter(branch)
        {
            $.post("get_cost_center",{branch},function(data){
              $('#mm').html(data);
              //alert(data);
          });
        }
        function show_cost_center_disp(reportType)
        {
            if(reportType=='CostCenter')
            {
                $('#cost_centerDisp').show();
            }
            else
            {
                $('#cost_centerDisp').hide();
            }
        }
        </script>
        
        <div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-center">
                    
		</div>
	</div>
</div>
<?php $data[] = array('Branch'=>'Branch','CostCenter'=>'Cost Center'); ?>


<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                   <h3>Business Dashboard Report</h3> 
                </div>
            </div>
            
<div class="box-content">
    
<?php 
$year = date("Y"); 
$year1 = $year-2000;
$month = date("m"); 
$month2 = date("M");
if(in_array($month,array('01','02','03','1','2','3',1,2,3)))
{
    $year2 = (2000+$year1-1).'-'.$year1;
}
else
{
    $year2 = (2000+$year1).'-'.($year1+1);
}

//echo $year2; exit;

?>
<?php echo $this->Form->create('Dashs',array('controller'=>'Dashs','action'=>'view','class'=>'form-horizontal')); ?>

<div class="form-group">
    <label class="col-sm-2 control-label"><b style="font-size:14px"> Branch</b></label>
    <div class="col-sm-3">
        <?php echo $this->Form->input('branch_name', array('label'=>false,'options'=>$branch_master,'id'=>'branch_name','class'=>'form-control','onchange'=>"costcenter(this.value);",'required'=>true)); ?>
    </div>
<!--    <div id="cost_centerDisp" style="display:none">
        <label class="col-sm-2 control-label"><b style="font-size:14px"> Cost Center</b></label>
    <div class="col-sm-3">
    <div id="mm" id>
       <?php //echo $this->Form->input('Dashs.cost_centerId',array('label'=>false,'options'=>'','empty'=>'No Records Found','required'=>true,'class'=>'form-control','id'=>'cost_center','readonly'=>true)); ?> 
    </div>
        </div>
    </div>-->
    
</div>
    
<div class="form-group">
    <label class="col-sm-2 control-label"><b style="font-size:14px"> Finance Year</b></label>
    <div class="col-sm-3">
        <?php	
            echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','options' => $financeYearArr,'value' => $year2,'required'=>true));
        ?>
    </div>
    		
</div>
<div class="form-group">
    <label class="col-sm-2 control-label"><b style="font-size:14px"> Finance Month</b></label>
    <div class="col-sm-3">
        <?php	$month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','options' => $month,'empty' => 'Select Month','value'=>$month2,'required'=>true));
        ?>
    </div>		
</div>
    
    
		<div class="clearfix"></div>
		<div class="form-group">
			<div class="col-sm-2">
                            <button type="button" class="btn btn-info btn-label-left" value = "show" onClick="get_Show11();">Show</button>
			</div>
			<div class="col-sm-2">
                            <button type="button" class="btn btn-info btn-label-left" onClick="report_validate11();">Export</button>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
          </div>
    </div>
</div>      
        
        
        
        
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="box-name">
                            
                            <span>View Dashboard Report</span>
                        </div>
                    <div class="box-icons">
                        <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="expand-link">
                        <i class="fa fa-expand"></i>
                        </a>
                        <a class="close-link">
                        <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <div class="no-move"></div>
                    </div>
                    <div class="box-content">
                        <div id="nn" style="overflow: auto">
                                </div>
                    </div>
                </div>
            </div>
        </div>


