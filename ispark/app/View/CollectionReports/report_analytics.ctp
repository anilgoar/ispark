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
            

                <?php echo $this->Form->create('collection',array('controller'=>'CollectionReports','class'=>'form-horizontal')); ?>

                <div class="form-group">
                    <label class="col-sm-1 control-label"><b style="font-size:14px"> Category</b></label>	
                    <div class="col-sm-2">
                            <?php echo $this->Form->input('category', array('label'=>false,'id'=>'category','options'=>array('Branch'=>'Branch','Group'=>'Group'),'empty'=>'Select','value'=>'','onchange'=>'get_disp_cat(this.value)','class'=>'form-control')); ?>
                    </div>

                    <label class="col-sm-1 control-label"><b style="font-size:14px">From </b></label>	
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('from', array('label'=>false,'id'=>'from','options'=>$month_master,'empty'=>'Select','value'=>"",'class'=>'form-control')); ?>
                    </div>
                    <label class="col-sm-1 control-label"><b style="font-size:14px">To </b></label>	
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('to', array('label'=>false,'id'=>'to','options'=>$month_master,'empty'=>'Select','value'=>"",'class'=>'form-control')); ?>
                    </div>
                </div>
                
                <div id="form-group">
                    <div id="group_disp" style="display:none">
                        <label class="col-sm-1 control-label"><b style="font-size:14px"> Group</b></label>	
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('group', array('label'=>false,'id'=>'group','options'=>$group_master,'empty'=>'Select','value'=>'','class'=>'form-control')); ?>
                        </div>
                    </div>
                    <div id="branch_disp" style="display:none">
                    <label class="col-sm-1 control-label"><b style="font-size:14px">Branch</b></label>	
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch', array('label'=>false,'id'=>'branch','options'=>$branch_master,'empty'=>'Select','value'=>'','class'=>'form-control')); ?>
                    </div>
                    </div>
                </div>
                <div id="form-group">
                <div class="col-sm-2">
                    <a href="#" class="btn btn-primary">Export</a>
                    <a href="#" onclick="get_back()" class="btn btn-primary">Back</a>
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
            
                    <div id="myModal" class="modal" style="z-index:9999; padding-top: 120px;">
                    <div class="modal-content" style="left:285px !important;width:75%" >
                        <span class="close" onclick="pop_up_close()">&times;</span>
                        <?php echo $this->Form->create('CollectionReports'); ?> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
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


function get_report_analytics(fetch_type)
{
    var category = $('#category').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var group = $('#group').val();
    var branch = $('#branch').val();
    
    if(category=='')
    {
        alert("Please Select category");
        return false;
    }
    else if(from=='')
    {
        alert("Please Select Category");
        return false;
    }
    else if(to=='')
    {
        alert("Please Select Report Type");
        return false;
    }
    
    if(fetch_type=='export')
    {
        window.location="get_analytics?category="+category+"&from="+from+"&to="+to+"&group="+group+"&branch="+branch;
    }
    else
    {
        
    }
    
    

    
}
</script>

<script>
// Get the modal
function get_disp_cat(cat)
{
    if(cat=='Branch')
    {
        $('#branch_disp').show();
        $('#group_disp').hide();
    }
    else if(cat=='Group')
    {
        $('#group_disp').show();
        $('#branch_disp').hide();
    }
    else
    {
        $('#branch_disp').hide();
        $('#group_disp').hide();
    }
    
}
function pop_up_close()
{
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
}
</script>