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
                <span>EPTP Tracking Report</span>
            </div>
            
            <div class="no-move"></div>
        </div>
        <div class="box-content">
            
                <?php echo $this->Session->flash(); ?>
            

                <?php echo $this->Form->create('collection',array('controller'=>'CollectionReports','action'=>'report_eptp_tracking()','class'=>'form-horizontal')); ?>

                <div class="form-group">
                    <label class="col-sm-1 control-label"><b style="font-size:14px"> Branch</b></label>	
                    <div class="col-sm-3">
                            <?php	echo $this->Form->input('branch', array('label'=>false,'id'=>'branch','options'=>$branch_master,'empty'=>'Select','value'=>$branchS,'class'=>'form-control')); ?>
                    </div>

                    <label class="col-sm-1 control-label"><b style="font-size:14px">Type </b></label>	
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('report_type', array('label'=>false,'id'=>'report_type','options'=>array('Details'=>'Details'),'empty'=>'Select','value'=>$report_typeS,'class'=>'form-control')); ?>
                    </div>
                    
                    
                </div>
                <div id="form-group">
                    <div class="col-sm-3">
                        <input type="hidden" value="0" id="pageNo" name="pageNo" />
                        <div class="btn btn-info btn-label-left" value = "show" onClick="get_eptp_track('show');">Show</div>
                        <div class="btn btn-info btn-label-left" onClick="get_eptp_track('export');">Export</div>
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
            <div id = "show_record" style="overflow:auto"><?php echo $htm; ?></div>
            
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
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <label class="col-sm-2 control-label"></label>
                            
                            <div class="col-sm-2">
                                <button type="submit" name="submit" value="Save" class="btn btn-primary btn-label-left">Save</button>
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


function get_eptp_track(fetch_type)
{
    var branch = $('#branch').val();
    var report_type = $('#report_type').val();
    
    if(branch=='')
    {
        alert("Please Select Branch");
        return false;
    }
    else if(report_type=='')
    {
        alert("Please Select Report Type");
        return false;
    }
    
    if(fetch_type=='export')
    {
        window.location="get_eptp_track?branch="+branch+"&report_type="+report_type+"&fetch_type="+fetch_type;
    }
    else
    {
        jQuery.ajax({
              url: 'get_eptp_track',
              method: 'post',
              data: {
                 branch: branch,
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




function get_add_eptp_date(cost_center,month,inv)
{
    document.getElementById('CostDisp_eptp').innerHTML = cost_center;
    document.getElementById('MonthDisp_eptp').innerHTML = month;
    
    document.getElementById('cost_center_eptp').value = cost_center;
    document.getElementById('month_eptp').value = month;
    document.getElementById('inv').value = inv;
   
    
    var branch = $('#branch').val();
    var report_type = $('#report_type').val();
    
    
    $('#branchS_eptp').val(branch);
    $('#report_typeS_eptp').val(report_type);
    $('#eptp_date').val('');
    $('#eptp_remarks').val('');
    var modal = document.getElementById('myModal_eptp');
    modal.style.display = "block";
    
}

function pop_up_close_eptp()
{
    var modal = document.getElementById('myModal_eptp');
    modal.style.display = "none";
}


</script>