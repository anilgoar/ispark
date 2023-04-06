<?php ?>
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

<style>
.form-group .form-control, .form-group .input-group {
    margin-bottom: -20px;
}
.form-horizontal .control-label {
    padding-top: 0px;
}
.control-label{
    font-size: 11px;
}

table th{text-align: center;}
#brnch-align {position: relative;top: -20px;} 
</style>


<div class="row">
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <div class="box-name">
                <span>Collection Tracking Report</span>
            </div>
            
            <div class="no-move"></div>
        </div>
        <div class="box-content">
            
                <?php echo $this->Session->flash(); ?>
            

                <?php echo $this->Form->create('collection',array('controller'=>'CollectionReports','action'=>'get_collection_report()','class'=>'form-horizontal')); ?>

                <div class="form-group">
                    <label class="col-sm-1 control-label"><b style="font-size:14px"> Branch</b></label>	
                    <div class="col-sm-3">
                            <?php	echo $this->Form->input('branch', array('label'=>false,'id'=>'branch','options'=>$branch_master,'empty'=>'Select','value'=>$branchS,'class'=>'form-control')); ?>
                    </div>

                    <label class="col-sm-1 control-label"><b style="font-size:14px">Category </b></label>	
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('category', array('label'=>false,'id'=>'category','options'=>array('All'=>'All','Agreement Pending'=>'Agreement Pending','PO Pending'=>'PO Pending','Bill Ready'=>'Bill Ready','GRN Pending'=>'GRN Pending','Receiving Pending'=>'Receiving Pending','PTP Date Pending'=>'PTP Date Pending'),'empty'=>'Select','value'=>$categoryS,'class'=>'form-control')); ?>
                    </div>
                    <label class="col-sm-1 control-label"><b style="font-size:14px">Type </b></label>	
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('report_type', array('label'=>false,'id'=>'report_type','options'=>array('Details'=>'Details','Summary'=>'Summary','NewSummary'=>'New Summary','EPTP'=>'EPTP'),'empty'=>'Select','value'=>$report_typeS,'class'=>'form-control')); ?>
                    </div>
                    
                    
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <input type="hidden" value="0" id="pageNo" name="pageNo" />
                        <div class="btn btn-info btn-label-left" value = "show" onClick="get_coll_track('show');">Show</div>
                        <div class="btn btn-info btn-label-left" onClick="get_coll_track('export');">Export</div>
                        <div class="btn btn-info btn-label-left" onClick="">Back</div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php echo $this->Form->end(); ?>
            
        </div>
    </div>
</div> 
</div>   
<div class="row">
    <div class="col-xs-12">
        <div class="box form-horizontal">
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Collection Tracking'],
          ['Agreement Pending',     <?php if(!empty($data_branch2['AP'])) {echo $data_branch2['AP'];} else { echo '0';} ?>],
          ['PO Pending',     <?php if(!empty($data_branch2['POP'])) {echo $data_branch2['POP'];} else { echo '0';} ?>],
          ['GRN Pending',  <?php if(!empty($data_branch2['GP'])) {echo $data_branch2['GP'];} else { echo '0';} ?>],
          ['PTP Pending', <?php if(!empty($data_branch2['PTP'])) {echo $data_branch2['PTP'];} else { echo '0';} ?>],
          ['Receiving Pending',<?php if(!empty($data_branch2['RP'])) {echo $data_branch2['RP'];} else { echo '0';} ?>],
          ['Bill Ready',<?php if(!empty($data_branch2['BP'])) {echo $data_branch2['BP'];} else { echo '0';} ?>]
        ]);

        var options = {
            width: 400,
            height: 240,
            pieSliceText: 'value',
          colors: ['#878FD7', '#D98890', '#A8C99D', '#9A91C3', '#33C6CF','#F2A471']
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        
        chart.draw(data, options);
      }
    </script>
            <div class="form-group">
                <div class="col-sm-6">
                    <div id="piechart_3d" style="width: 900px; height: 200px;"></div>
                </div>
                
            </div>
            <div id = "show_record" style="overflow:auto"><?php echo $htm; ?></div>
            
                    <div id="myModal" class="modal" style="z-index:9999; padding-top: 120px;">
                    <div class="modal-content" style="left:285px !important;width:75%" >
                        <span class="close" onclick="pop_up_close()">&times;</span>
                        <?php echo $this->Form->create('CollectionReports'); ?> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Add Action Date</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Cost Center</label>
                            <div class="col-sm-2" id="CostDisp"></div>
                            
                            <label class="col-sm-2 control-label">Month</label>
                            <div class="col-sm-2" id="MonthDisp"></div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Action Date</label>
                            <div class="col-sm-2">
                                <?php	
                                    echo $this->Form->input('action_date', array('label'=>false,'class'=>'form-control','id'=>'action_date','onclick'=>"displayDatePicker('data[CollectionReports][action_date]');",'placeholder' => 'Action Date','required'=>true));
                                ?>
                            </div>
                            <label class="col-sm-2 control-label">Remarks</label>
                            <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->textArea('remarks', array('label'=>false,'class'=>'form-control','id'=>'remarks','placeholder' => 'Remarks','required'=>true));
                                ?>
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <label class="col-sm-2 control-label"></label>
                            
                            <div class="col-sm-2">
                                <button type="submit" name="submit" value="CloseBusinessCase" class="btn btn-primary btn-label-left">Save</button>
                        </div>
                        </div>
                        <?php	
                            echo $this->Form->input('cost_center', array('label'=>false,'type'=>'hidden','id'=>'cost_center','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('month', array('label'=>false,'type'=>'hidden','id'=>'month','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('category', array('label'=>false,'type'=>'hidden','id'=>'category2','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('branchS', array('label'=>false,'type'=>'hidden','id'=>'branchS','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('categoryS', array('label'=>false,'type'=>'hidden','id'=>'categoryS','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('report_typeS', array('label'=>false,'type'=>'hidden','id'=>'report_typeS','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('entry_type', array('label'=>false,'type'=>'hidden','id'=>'entry_type','class'=>'form-control','value' => 'action_date','required'=>true));
                        ?>
                        <?php echo $this->Form->end(); ?> 
                    </div>
                    </div>
            
                    <div id="myModal_eptp" class="modal" style="z-index:9999; padding-top: 120px;">
                    <div class="modal-content" style="left:285px !important;width:75%" >
                        <span class="close" onclick="pop_up_close_eptp()">&times;</span>
                        <?php echo $this->Form->create('CollectionReports'); ?> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Add EPTP Date</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Cost Center</label>
                            <div class="col-sm-2" id="CostDisp_eptp"></div>
                            
                            <label class="col-sm-2 control-label">Month</label>
                            <div class="col-sm-2" id="MonthDisp_eptp"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Process</label>
                            <div class="col-sm-2" id="ProcessDisp_eptp"></div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">EPTP Date</label>
                            <div class="col-sm-2">
                                <?php	
                                    echo $this->Form->input('eptp_date', array('label'=>false,'class'=>'form-control','id'=>'eptp_date','onclick'=>"displayDatePicker('data[CollectionReports][eptp_date]');",'placeholder' => 'EPTP Date','required'=>true));
                                ?>
                            </div>
                            <label class="col-sm-2 control-label">Remarks</label>
                            <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->textArea('remarks', array('label'=>false,'class'=>'form-control','id'=>'eptp_remarks','placeholder' => 'Remarks','required'=>true));
                                ?>
                            </div>
                            
                        </div>
                        <div class="form-group" style="margin-left:75px;">
                            <div class="col-sm-12 pull-right" id='hisdt_date'  ></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-2">
                                <button type="submit" name="submit" value="Save" class="btn btn-primary btn-label-left pull-right">Save</button>
                            </div>
                        </div>
                        <?php	
                            echo $this->Form->input('branchS', array('label'=>false,'type'=>'hidden','id'=>'branchS_eptp','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('cost_center', array('label'=>false,'type'=>'hidden','id'=>'cost_center_eptp','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('month', array('label'=>false,'type'=>'hidden','id'=>'month_eptp','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('report_typeS', array('label'=>false,'type'=>'hidden','id'=>'report_typeS_eptp','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('entry_type', array('label'=>false,'type'=>'hidden','id'=>'entry_type','class'=>'form-control','value' => 'eptp_date','required'=>true));
                            echo $this->Form->input('inv', array('label'=>false,'type'=>'hidden','id'=>'inv','class'=>'form-control','value' => '','required'=>true));
                        ?>
                        <?php echo $this->Form->end(); ?> 
                    </div>
                    </div>
            
        </div>
    </div>
</div>

<script  src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>	
<script type="text/javascript">
$(document).ready(function () {
    $('#table_id').dataTable();
});


function get_coll_track(fetch_type)
{
    var branch = $('#branch').val();
    var category = $('#category').val();
    var report_type = $('#report_type').val();
    
    if(branch=='')
    {
        alert("Please Select Branch");
        return false;
    }
    else if(category=='')
    {
        alert("Please Select Category");
        return false;
    }
    else if(report_type=='')
    {
        alert("Please Select Report Type");
        return false;
    }
    
    if(fetch_type=='export')
    {
        window.location="get_coll_track?branch="+branch+"&category="+category+"&report_type="+report_type+"&fetch_type="+fetch_type;
    }
    else
    {
        jQuery.ajax({
              url: 'get_coll_track',
              method: 'post',
              data: {
                 branch: branch,
                 category:category,
                 report_type:report_type,
                 fetch_type:fetch_type
              },
              success: function(response){
                 $('#show_record').html(response);
              }});
    }
    
    

    
}
</script>

<script>
// Get the modal
function get_add_action_date(cost_center,month,category)
{
    document.getElementById('CostDisp').innerHTML = cost_center;
    document.getElementById('MonthDisp').innerHTML = month;
    
    document.getElementById('cost_center').value = cost_center;
    document.getElementById('month').value = month;
    document.getElementById('category2').value = category;
    
    var branch = $('#branch').val();
    var categoryS = $('#category').val();
    var report_type = $('#report_type').val();
    $('#branchS').val(branch);
    $('#categoryS').val(categoryS);
    $('#report_typeS').val(report_type);
    $('#action_date').val('');
    $('#remarks').val('');
    var modal = document.getElementById('myModal');
    modal.style.display = "block";
    
}
function pop_up_close()
{
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
}



function get_add_eptp_date(cost_center,month,inv,rem,act_dat,hisre,process_name)
{
    document.getElementById('CostDisp_eptp').innerHTML = cost_center;
    document.getElementById('MonthDisp_eptp').innerHTML = month;
    document.getElementById('ProcessDisp_eptp').innerHTML = process_name;
    
    document.getElementById('cost_center_eptp').value = cost_center;
    document.getElementById('month_eptp').value = month;
    document.getElementById('inv').value = inv;
   
    
    var branch = $('#branch').val();
    var report_type = $('#report_type').val();
    
    
    $('#branchS_eptp').val(branch);
    $('#report_typeS_eptp').val(report_type);
    $('#eptp_date').val(act_dat);
    $('#eptp_remarks').val(rem);

    $('#hisdt_date').html(hisre);
    

    var modal = document.getElementById('myModal_eptp');
    modal.style.display = "block";
    
}

function pop_up_close_eptp()
{
    var modal = document.getElementById('myModal_eptp');
    modal.style.display = "none";
}


</script>

