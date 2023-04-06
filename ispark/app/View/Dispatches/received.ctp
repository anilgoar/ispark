<script>
    function show_tag(value)
    {       
        //alert($('#'+value).css('display'));
        if($('#'+value).css('display')=='none')
        {
            $('#'+value).show();
        }
        else
        {
           $('#'+value).hide();
        }
    }
    
    $(document).ready(function(){
     
     $("#BranchSendFrom").on('change',function(){

        var BranchSendFrom = $("#BranchSendFrom").val();
        $.post("get_dispatch1",
            {
                BranchSendFrom: BranchSendFrom
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                
                $("#EnvelopeName").empty();
                $("#EnvelopeName").html(text);
            });
            
         $.post("get_imprest",
            {
                BranchId: BranchSendFrom
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                
                $("#ImprestManager").empty();
                $("#ImprestManager").html(text);
            });   
            
     });

     $('#view1').on('click',function()
     {
        var BranchSendFrom = $('#BranchSendFrom1').val();
        var FromDate = $('#FromDate1').val();
        var ToDate = $('#ToDate1').val();
        
        if(BranchSendFrom=='')
        {
            alert('Please Select Branch');
        }
        else if(FromDate=='')
        {
            alert('Please Select From Date');
        }
        else if(ToDate=='')
        {
            alert('Please Select To Date');
        }
        
        $.post("get_received",
            {
                BranchSendFrom: BranchSendFrom,
                FromDate:FromDate,
                ToDate:ToDate
            },
            function(data,status){
                $("#view_received").empty();
                $("#view_received").html(data);
                
            });  
     });

     $('#view2').on('click',function()
     {
        var BranchSendFrom = $('#BranchSendFrom2').val();
        var FromDate = $('#FromDate2').val();
        var ToDate = $('#ToDate2').val();
        
        if(BranchSendFrom=='')
        {
            alert('Please Select Branch');
        }
        else if(FromDate=='')
        {
            alert('Please Select From Date');
        }
        else if(ToDate=='')
        {
            alert('Please Select To Date');
        }
        
        $.post("get_dispatch2",
            {
                BranchSendFrom: BranchSendFrom,
                FromDate:FromDate,
                ToDate:ToDate
            },
            function(data,status){
                $("#view_received2").empty();
                $("#view_received2").html(data);
                
            });  
     });
});
    
    function get_rcv_grn(DisId)
    {
        if(DisId!='')
        {
            $.post("get_rcv_grn",
            {
                DisId: DisId
            },
            function(data,status){
                $("#grn_received").empty();
                $("#grn_received").html(data);
                
            });  
        }
    }
   function checkAllBox()
{
    if($("#checkAll").prop('checked'))
    $('input:checkbox').add().prop('checked','checked');
    else
     $('input:checkbox').add().prop('checked',false);   
} 
</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
        <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>


<div class="box-content" style="background-color:#ffffff">
    <?php echo '<font color="green">'.$this->Session->flash().'</font>'; ?>
    <h4 class="page-header textClass" onclick="show_tag('receive')">Receiving Pending Envelope(-/+)</h4>
    <?php echo $this->Form->create('Dispatches'); ?>
    <div class="form-horizontal" id="receive" style="display:none">
    <div class="form-group">
        <label class="col-sm-3 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('BranchSendFrom',array('label' => false,'options'=>$branch,'class'=>'form-control','empty'=>'Select','id'=>'BranchSendFrom','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Courier No/Envelope No</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('EnvelopeName',array('label' => false,'options'=>'','class'=>'form-control','empty'=>'Select','id'=>'EnvelopeName','required'=>true,'onchange'=>'get_rcv_grn(this.value)')); ?>
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>  
            </div>    
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Handover To</label>
        <div class="col-sm-3">
        <div class="input-group">
           <?php echo $this->Form->input('ImprestManager',array('label' => false,'options'=>'',
               'class'=>'form-control','empty'=>'Select','id'=>'ImprestManager','required'=>true)); ?>
            <span class="input-group-addon"><i class="fa fa-user"></i></span>  
        </div>   
        </div>
    </div>
        
        <div id="grn_received"></div>  
    <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-1">
            <button type='submit' class="btn btn-info" value="Save">Save</button>
        </div>
        
    </div>
    </div>
    <?php echo $this->Form->end(); ?>
    
    <h4 class="page-header textClass"  onclick="show_tag('Received')">Received Envelope(-/+)</h4>
    <div class="form-horizontal" id="Received"  style="display:none">
        <div class="form-group">
            <label class="col-sm-3 control-label">Branch</label>
            <div class="col-sm-3">
                <div class="input-group">
                    <?php echo $this->Form->input('BranchSendFrom1',array('label' => false,'options'=>$branch,'class'=>'form-control','empty'=>'Select','id'=>'BranchSendFrom1')); ?>
                    <span class="input-group-addon"><i class="fa fa-group"></i></span>
                </div>    
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">From Date</label>
            <div class="col-sm-3">
                <div class="input-group">
                    <?php echo $this->Form->input('FromDate1',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'Select','onclick'=>"displayDatePicker('data[FromDate1]',false,'my')",'id'=>'FromDate1')); ?>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>    
            </div>
        </div>
        <div class="form-group">
           <label class="col-sm-3 control-label">To Date</label>
            <div class="col-sm-3">
                <div class="input-group">
                    <?php echo $this->Form->input('ToDate1',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'Select','onclick'=>"displayDatePicker('data[ToDate1]',false,'my')",'id'=>'ToDate1')); ?>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>    
            </div>
        </div>
        <div class="form-group">
           <label class="col-sm-3 control-label"></label>
            <div class="col-sm-1">
                <div class="btn btn-info" id="view1">View</div>
            </div>
            <div class="col-sm-1">
                <div class="btn btn-info" id="back1">Back</div>
            </div>
        </div>
        <div id="view_received"></div>
    </div>
    
    <h4 class="page-header textClass" onclick="show_tag('dispatch')">Dispatch Envelope(-/+)</h4>
    <div class="form-horizontal" id="dispatch"  style="display:none">
        <div class="form-group">
            <label class="col-sm-3 control-label">Branch</label>
            <div class="col-sm-3">
                <div class="input-group">
                    <?php echo $this->Form->input('BranchSendFrom2',array('label' => false,'options'=>$branch,'class'=>'form-control','empty'=>'Select','id'=>'BranchSendFrom2')); ?>
                    <span class="input-group-addon"><i class="fa fa-group"></i></span>
                </div>    
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">From Date</label>
            <div class="col-sm-3">
                <div class="input-group">
                    <?php echo $this->Form->input('FromDate2',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'From Date','onclick'=>"displayDatePicker('data[FromDate2]',false,'my')",'id'=>'FromDate2')); ?>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>    
            </div>
        </div>
        <div class="form-group">
           <label class="col-sm-3 control-label">To Date</label>
            <div class="col-sm-3">
                <div class="input-group">
                    <?php echo $this->Form->input('ToDate2',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'To Date','onclick'=>"displayDatePicker('data[ToDate2]',false,'my')",'id'=>'ToDate2')); ?>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>    
            </div>
        </div>
        <div class="form-group">
           <label class="col-sm-3 control-label"></label>
            <div class="col-sm-1">
                <div class="btn btn-info" id="view2">View</div>
            </div>
            <div class="col-sm-1">
                <div class="btn btn-info" id="back2">Back</div>
            </div>
        </div>
        <div id="view_received2" align="center"></div>
    </div>
    <div class="clearfix"></div>
    
    
</div>