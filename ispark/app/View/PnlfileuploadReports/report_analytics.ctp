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
                <span>P&L Analytics</span>
            </div>
            
            <div class="no-move"></div>
        </div>
        <div class="box-content">
            
                <?php echo $this->Session->flash(); ?>
            

                <?php echo $this->Form->create('collection',array('controller'=>'PnlfileuploadReports','class'=>'form-horizontal')); ?>

                <div class="form-group">
                    <label class="col-sm-1 control-label"><b style="font-size:14px"> Category</b></label>	
                    <div class="col-sm-2">
                            <?php echo $this->Form->input('category', array('label'=>false,'id'=>'category','options'=>array('Branch'=>'Branch','Group'=>'Group'),'empty'=>'Select','value'=>'','onchange'=>'get_disp_cat(this.value)','class'=>'form-control')); ?>
                    </div>
                    <div id="group_type_disp" style="display:none">
                        <label class="col-sm-3 control-label"><input type="radio" id="group_type1" name="group_type" value="T" onclick="get_cost_center('T')" class="">&nbsp; <span style="font-size:14px">Telecom</span> 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="group_type2" name="group_type" value="N" onclick="get_cost_center('N')" class="">&nbsp; <span style="font-size:14px">Non - Telecom</span></label>
                        
                        <div id="group_disp" style="display:none">
                            <label class="col-sm-1 control-label"><b style="font-size:14px"> Group</b></label>	
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('group', array('label'=>false,'id'=>'group','options'=>$group_master,'empty'=>'Select','value'=>'','class'=>'form-control')); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div id="branch_disp" style="display:none">
                    <label class="col-sm-1 control-label"><b style="font-size:14px">Branch</b></label>	
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch', array('label'=>false,'id'=>'branch','options'=>$branch_master,'empty'=>'Select','value'=>'','class'=>'form-control')); ?>
                    </div>
                    </div>
                    
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label"><b style="font-size:14px">From </b></label>	
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('from', array('label'=>false,'id'=>'from','options'=>$month_master,'empty'=>'Select','value'=>"",'class'=>'form-control')); ?>
                    </div>
                    <label class="col-sm-1 control-label"><b style="font-size:14px">To </b></label>	
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('to', array('label'=>false,'id'=>'to','options'=>$month_master,'empty'=>'Select','value'=>"",'class'=>'form-control')); ?>
                    </div>
                    <label class="col-sm-1 control-label"><b style="font-size:14px">Type </b></label>	
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('type', array('label'=>false,'id'=>'type','options'=>array('summary'=>'summary','detail'=>'detail'),'empty'=>'Select','value'=>"",'class'=>'form-control')); ?>
                    </div>
                    <div class="col-sm-2">
                        
                    <a href="#" onclick="get_report_analytics('export')" class="btn btn-primary btn-new">Export</a>
                    <input onclick='return window.location="<?php echo $this->webroot;?>MenuIsparks/profitloss"' type="button" value="Back" class="btn btn-primary btn-new" style="margin-left: 5px;" />
                    
                    </div>
                </div>
                <div class="form-group">
                
                </div>
                <div class="clearfix"></div>
                <?php echo $this->Form->end(); ?>
            
        </div>
    </div>
</div> 
</div>   



<script type="text/javascript">

function get_cost_center(cost_center_type)
{
    $('#group_disp').show();
    $.post("get_cost_center",{cost_center_type:cost_center_type},function(data){
        $('#group').html(data); });

}

function get_report_analytics(fetch_type)
{
    var category = $('#category').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var group = $('#group').val();
    var branch = $('#branch').val();
    var type = $('#type').val();
    
    var group_type = false;
    try{
        group_type = document.querySelector('input[name = "group_type"]:checked').value;
    }
    catch(err)
    {
        group_type = false;
    }
    
    if(category=='')
    {
        alert("Please Select category");
        return false;
    }
    else if(from=='')
    {
        alert("Please Select From");
        return false;
    }
    else if(to=='')
    {
        alert("Please Select To");
        return false;
    }
    else if(category=='Branch' &&  branch=='')
    {
        alert("Please Select Branch");
        return false;
    }
    else if(category=='Group' && group_type===false)
    {
        alert("Please Select Telecom/Non Telecom");
        return false;
    }
    else if(category=='Group' && group=='')
    {
        alert("Please Select Group");
        return false;
    }
    else if(type=='')
    {
        alert("Please Select Type");
        return false;
    }
    
    
    if(fetch_type=='export')
    {
        window.location="get_analytics?category="+category+"&from="+from+"&to="+to+"&group="+group+"&branch="+branch+'&type='+type;
    }
    else
    {
        
    }
    
    

    
}
</script>

<script>
function get_disp_cat(cat)
{
    if(cat=='Branch')
    {
        $('#branch_disp').show();
        $('#group_type_disp').hide();
    }
    else if(cat=='Group')
    {
        $('#group_type_disp').show();
        $('#branch_disp').hide();
    }
    else
    {
        $('#branch_disp').hide();
        $('#group_type_disp').hide();
    }
    
}

</script>