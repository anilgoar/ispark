<script>
$(document).ready(function(){
    //alertify.success('Success notification message.'); 
        $("input[name=select]").on('click',function(){
        var val = $("input[name=select]:checked").val();
        if(val==1)
        {
            $('#Existing').hide();
            $('#Dispatch').hide();
            $('#New').show();
        }
        else if(val==2)
        {
            $('#New').hide();
            $('#Dispatch').hide();
            $('#Existing').show();
        }
        else if(val==3)
        {
            $('#New').hide();
            $('#Existing').hide();
            $('#Dispatch').show();
        }
     });
     
     $("#ExistingBranchSendFrom").on('change',function(){
        
        var branchId = $("#ExistingBranchSendFrom").val();
        
        $.post("Dispatches/get_dispatch",
            {
                BranchSendFrom: branchId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#ExistingEnvelopeName").empty();
                $("#ExistingEnvelopeName").html(text);
            });
        
        
        
        
        
     });
     
     $("#DispatchBranchSendFrom").on('change',function(){
        
        var branchId = $("#DispatchBranchSendFrom").val();
        
        $.post("Dispatches/get_dispatch",
            {
                BranchSendFrom: branchId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#DispatchEnvelopeName").empty();
                $("#DispatchEnvelopeName").html(text);
            });
     });
     
     $("#view").on('click',function()
     {
        var EnvelopeName = $("#DispatchEnvelopeName").val();
        $.post("Dispatches/get_grn",
            {
                dispatchId: EnvelopeName
            },
            function(data,status){
                $("#showGrn").empty();
                $("#showGrn").html(data);
            });
     });
});


</script>
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

<div class="box-content">
				<h4 class="page-header">GRN Dispatch Option</h4>
                                <h4 class="page-header" style="color:green"><?php echo $this->Session->flash(); ?></h4>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-1 control-label">Options</label>
                                    </div>
                                <div class="form-group">
                                    <div class="col-sm-1">
                                        <input type="radio" name="select" value="1" class="form-control">
                                    </div>
                                    <label class="col-sm-0 control-label">New</label>
                                </div>
                                    <div id="New" style="display: none;background-color: #436E90; color: #FFFFFF">
                                        <h4 class="page-header textClass">New Envelop Creation</h4>
                                    <?php echo $this->Form->create('Dispatch',array('style'=>'')); ?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Branch</label>
                                            <div class="col-sm-3">
                                                <?php    echo $this->Form->input('New.BranchSendFrom',array('label'=>false,'options'=>$branch,'empty'=>'Select','class'=>'form-control')); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Send To</label>
                                            <div class="col-sm-3">
                                            <?php    echo $this->Form->input('New.BranchSendTo',array('label'=>false,'options'=>$branch1,'empty'=>'Select','class'=>'form-control')); ?>
                                            </div>
                                            <label class="col-sm-2 control-label">Envelope Name:</label>
                                            <div class="col-sm-3">
                                            <?php    echo $this->Form->input('New.EnvelopeName',array('label'=>false,'value'=>'','placeholder'=>'Envelope Name','class'=>'form-control')); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <button name="submit" class="btn btn-info">Save</button>
                                            </div>
                                        </div>
                                   <?php echo $this->Form->end(); ?>
                                        <h6 class="page-header textClass"></h6>
                                  </div>  
                                <div class="form-group">
                                        <div class="col-sm-1">
                                        <input type="radio" name="select" value="2" class="form-control">
                                        </div>
                                    <label class="col-sm-0 control-label">Existing</label>
                                </div>
                                  
                                    <div id="Existing" style="display: none;background-color: #436E90; color: #FFFFFF">
                                        <h4 class="page-header textClass">Please Select Existing One</h4>
                                <?php echo $this->Form->create('Dispatch',array('style'=>'')); ?>    
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Branch</label>
                                            <div class="col-sm-3">
                                            <?php    echo $this->Form->input('Existing.BranchSendFrom',array('label'=>false,'options'=>$branch,'empty'=>'Select','class'=>'form-control')); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Envelope</label>
                                            <div class="col-sm-3">
                                            <?php    echo $this->Form->input('Existing.EnvelopeName',array('label'=>false,'options'=>'','empty'=>'Select','class'=>'form-control')); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <button name="submit" class="btn btn-info">Proceed</button>
                                            </div>
                                        </div>
                                   <?php echo $this->Form->end(); ?>
                                        <h6 class="page-header textClass"></h6>
                                    </div>      
                                <div class="form-group">
                                        <div class="col-sm-1">
                                        <input type="radio" name="select" value="3" class="form-control">
                                        </div>
                                    <label class="col-sm-0 control-label">Dispatch</label>
                                </div>
                                    <div id="Dispatch" style="display: none;background-color: #436E90; color: #FFFFFF">
                                        <h4 class="page-header textClass">Dispatch Details</h4>
                                    <?php echo $this->Form->create('Dispatch',array('style'=>'')); ?>    
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Branch</label>
                                            <div class="col-sm-3">
                                            <?php    echo $this->Form->input('Dispatch.BranchSendFrom',array('label'=>false,'options'=>$branch,'empty'=>'Select','class'=>'form-control','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Envelope Name</label>
                                            <div class="col-sm-3">
                                            <?php    echo $this->Form->input('Dispatch.EnvelopeName',array('label'=>false,'options'=>'','empty'=>'Select','class'=>'form-control','required'=>true)); ?>
                                            </div>
                                            <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-2">
                                                <div class="btn btn-info" id="view">View</div>
                                            </div>
                                        </div>
                                        <div id='showGrn' align="center"></div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Courier Company Name</label>
                                            <div class="col-sm-3">
                                            <?php    echo $this->Form->input('Dispatch.CourierCompanyName',array('label'=>false,'value'=>'','placeholder'=>'Company Name','class'=>'form-control','required'=>true)); ?>
                                            </div>
                                            <label class="col-sm-2 control-label">Receipt No</label>
                                                <div class="col-sm-3">
                                                    <?php    echo $this->Form->input('Dispatch.ReceiptNo',array('label'=>false,'value'=>'','placeholder'=>'Receipt No.','class'=>'form-control','required'=>true)); ?>
                                                </div>
                                            <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-2">
                                                <button name="submit" class="btn btn-info">Dispatch</button>
                                            </div>
                                        </div>
                                   <?php echo $this->Form->end(); ?>
                                        <h6 class="page-header textClass"></h6>
                                        </div>
                                </div>
                                          <div class="clearfix"></div>
					<div class="form-group">
                                            <div class="col-sm-2">
                                            </div>
					</div>
</div>
