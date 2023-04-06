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

.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 20px;
  height: 20px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<div class="row">
    <div class="col-xs-12" style="margin-top:-20px;">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Collection PTP</span>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('collection',array('controller'=>'CollectionReports','action'=>'get_collection_report()','class'=>'form-horizontal')); ?>
                <div class="form-group" style="margin-top:-5px;">
                    <label class="col-sm-1 control-label"><b style="font-size:14px"> Branch</label>	
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch', array('label'=>false,'id'=>'branch','options'=>$branch_master,'empty'=>'Select','onchange'=>'getprocessname(this.value)','class'=>'form-control')); ?>
                    </div>

                    <label class="col-sm-1 control-label"><b style="font-size:14px">Process</label>	
                    <div class="col-sm-3">
                        <select class="form-control" name="ProcessName" id="ProcessName"  >
                            <option value="">Select</option>
                            <option></option>
                        </select>
                    </div>

                    <div class="col-sm-3"  style="margin-top:-5px;">
                        <input type="hidden" value="0" id="pageNo" name="pageNo" />
                        <div class="btn btn-primary btn-new" style="margin-left:5px;" value = "show" onClick="get_coll_track('show');">Show</div>
                        <div class="btn btn-primary btn-new" style="margin-left:5px;" onClick="get_coll_track('export');">Export</div>
                    </div>
                    <div class="col-sm-1" style="margin-top:-2px;"><div class="loader" style="position:relative;left:-100px;display: none;" ></div></div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12" id="show_data"></div>
                </div> 
                <?php echo $this->Form->end(); ?>
            </div> 
        </div>   
    </div>
</div>

<script  src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>	
<script type="text/javascript">
$(document).ready(function () {
    $('#table_id').dataTable();
});

function getprocessname(branch){
    $.post('<?php echo $this->webroot;?>CollectionPtps/getprocessname',{branch: branch}, function(data){
        $("#ProcessName").html(data);
    });
}

function get_coll_track(fetch_type){
    $("#msgerr").remove();
    $(".loader").hide();
    
    var branch      =   $('#branch').val();
    var ProcessName =   $('#ProcessName').val();
 
    if(branch==''){
        $("#branch").focus();
        $("#branch").after("<span id='msgerr' style='color:red;font-size:12px;'><br/>Please select branch</span>");
        return false;
    }
    else{
        $(".loader").show()
        if(fetch_type=='export'){
            window.location="get_coll_track?branch="+branch+"&ProcessName="+ProcessName+"&fetch_type="+fetch_type;
            $(".loader").hide();
        }
        else{
            jQuery.ajax({
            url: 'get_coll_track',
            method: 'post',
            data: {
               branch: branch,
               ProcessName:ProcessName,
               fetch_type:fetch_type
            },
            success: function(response){
                $(".loader").hide();
                $('#show_data').html(response);
            }});
        }
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

function pop_up_close_eptp(){
    var modal = document.getElementById('myModal_eptp');
    modal.style.display = "none";
}
</script>

