<script>
    $(document).ready(function()
    {
        $("#InitialInvoiceBranchName").on('change',function()
        {
            
            var branch = $('#InitialInvoiceBranchName').val();
            $.post("InitialInvoices/get_costcenter",
            {
                branch : branch
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                
                $("#InitialInvoiceCostCenter").empty();
                $("#InitialInvoiceCostCenter").html(text);
            });  
     });
     $("#InitialInvoiceInvoiceDate").blur(function()
        {
            var str = $("#InitialInvoiceInvoiceDate").val().split("-").reverse().join("-");
            var date1 = new Date(str);
            var date2 = new Date("2017-07-01");
            if(date1>=date2)
            {
                $("#InitialInvoiceApplyKrishiTax").prop("checked",false);
                $("#InitialInvoiceApplyKrishiTax").attr("disabled","disabled");
                $("#InitialInvoiceApplyGst").attr("disabled",false);
            }
            else
            {
                $("#InitialInvoiceApplyKrishiTax").prop("checked",false);
                $("#InitialInvoiceApplyKrishiTax").attr("disabled",false);
                $("#InitialInvoiceApplyGst").prop("checked",false);
                $("#InitialInvoiceApplyGst").attr("disabled",true);
                $("#GSTTYPEID").hide();
            }
        });
    });
    
    function get_gst_pop_up()
    {
        var cost = $('#InitialInvoiceCostCenter').val();
        $.post("InitialInvoices/get_service_no",
            {
                cost_center : cost
            },
            function(data,status){
                 var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                
                $("#InitialInvoiceServNo").empty();
                $("#InitialInvoiceServNo").html(text);
            });
        
            $.post("InitialInvoices/get_gst_type",
            {
                cost_center : cost
            },
            function(data,status){
                if(data=='1')
                {
                    return true;
                }
                else
                {
                    $("#GSTTYPEID").show();
                    //$("#InitialInvoiceServNo").attr("disabled",false);
                    //window.open(""+'<?php //echo $this->Html->url("get_pop_up"."', '_blank', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,resizable=1,width=500,height=500'");?>');
                    
                }
            });
          
        return false;
    }
    
    function serve_enable()
    {
        $("#InitialInvoiceServNo").attr("disabled",false);
    }
    function serve_disable()
    {
        $("#InitialInvoiceServNo").attr("disabled",true);
        $("#InitialInvoiceServNo").val("");
    }
</script>

<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'add','onsubmit'=>"return get_revenue_validate()")); ?>
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
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-search"></i>
                    <span>Proforma Entry</span>
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
		<h4 class="page-header">Proforma Invoice</h4>
	        <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php $data=array(); foreach ($branch_master as $post): 
                         $data[$post['Addbranch']['branch_name']]= $post['Addbranch']['branch_name']; 
                         endforeach; ?><?php unset($Addbranch); 

                         echo $this->Form->input('branch_name', array('options' => $data,'empty' => 'Select 
Branch','label' => false, 'div' => false,'class'=>'form-control')); ?>
                    </div>

                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-3">
                        <div id="mm">
                        <?php	echo $this->Form->input('cost_center', array('label'=>false,'class'=>'form-control','options' 
=> '','empty' => 'Cost Center','required'=>true)); ?>
                        </div>
                    </div>
		</div>
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Finance Year</label>
                    <div class="col-sm-3">
                        
			<?php	echo $this->Form->input('finance_year', array('label'=>false,'class'=>'form-control','options' 
=> $finance_yearNew,'empty' => 'Select Year','required'=>true)); ?>
                    </div>
                    <label class="col-sm-2 control-label">Month</label>
                    <div class="col-sm-3">
                    <?php 
                        $data=array(
			'Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                        'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                    ?>
                    <?php   echo $this->Form->input('month', array('label'=>false,'class'=>'form-control','options' => 
$data,'empty' => 'Month','required'=>true,'onChange'=>'getDescription(this);get_provision(this.value)')); ?>
                    </div>
		</div>
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">invoice Date</label>
                    <div class="col-sm-3">
                    <?php	echo $this->Form->input('invoiceDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
                        'onClick'=>"displayDatePicker('data[InitialInvoice][invoiceDate]');",'onBlur'=>'','required'=>true,'readonly'=>true)); ?>
                    </div>
                    <label class="col-sm-2 control-label">Invoice Description</label>
                    <div class="col-sm-3">
                    <?php	echo $this->Form->input('invoiceDescription', array('label'=>false,'class'=>'form-control','placeholder' => 'Invoice Description','required'=>true)); ?>
                    </div>
                    
                </div>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">invoice Type</label>
                    <div class="col-sm-3">
                    <?php	echo $this->Form->input('invoiceType', array('label'=>false,'class'=>'form-control','empty'=>'Invoice Type',
                        'options'=>array("Revenue"=>"Revenue","Non Revenue"=>"Non Revenue"),'required'=>true)); ?>
                    </div>
                    
                    
                </div>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Apply Tax Calculation</label>
                    <div class="col-sm-3">
                    <div class="checkbox-inline" ><label>
                    <?php	echo $this->Form->checkbox('app_tax_cal', array('label'=>false,'checked'=>true,readonly=>true)); ?><i class="fa fa-square-o"></i></label></div>(check for Yes)
                    </div>
                    
                </div>
                <div class="form-group has-success has-feedback">
                    
                    <label class="col-sm-2 control-label">Apply GST</label>
                    <div class="col-sm-3">
                        <div class="checkbox-inline" ><label>
                    <?php echo $this->Form->checkbox('apply_gst', array('label'=>false,'checked'=>true,'readonly'=>true)); ?><i class="fa fa-square-o"></i></label></div>(Check for GST)
                    </div>
                </div>
                <div class="form-group has-success has-feedback" id="GSTTYPEID" style="display:none">
                    <label class="col-sm-2 control-label">GST TYPE</label>
                    <div class="col-sm-3">
                        <input type="radio" name="GSTType" onclick="serve_enable()" value="Integrated">Integrate
                       <input type="radio" name="GSTType" onclick="serve_disable()" value="Intrastate">IntraState
                    </div>  
                    <label class="col-sm-2 control-label">GST No</label>
                    <div class="col-sm-3">
                       <?php	echo $this->Form->input('serv_no', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'GST No','required'=>true,'required'=>true,'disabled'=>true)); ?> 
                    </div>  
                </div>
                <div class="form-group" id="month_selection">
                    
                </div>
                <div class="form-group has-success has-feedback">
                    <div class="col-sm-2">
                        <button type="submit" onclick="return get_revenue_validate()" class="btn btn-success btn-label-left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Go</b></button>
                    </div>
                </div>
            </div>
	</div>
    </div>
</div>
	
<?php echo $this->Form->end(); ?>
<script>
function get_provision(month)
{
    var year = $('#InitialInvoiceFinanceYear').val();
    var branch = $('#InitialInvoiceBranchName').val();
    var cost_center = $('#InitialInvoiceCostCenter').val();
    $.post("InitialInvoices/get_provision_months",
    {
     year: year,
     month:month,
     branch:branch,
     cost_center:cost_center
    },
    function(data,status){
        $("#month_selection").html(data);

    });  

}
    
    function get_display(dispId)
    {
        
        
        if($('#' + dispId).is(":checked"))
        {
            $('#'+dispId+'Disp').show();
        }
        else
        {
            $('#'+dispId+'Disp').hide();
        }
        get_revenue_total();
    }
    
    function get_revenue_total()
    {
        
       var total = 0;
       var idvalue =0;
       var month_select = $('#month_check').val();
       
       var str_month_arr = month_select.split(",");
       
       for(var i=0; i<str_month_arr.length; i++)
       {
          
            var mnt = str_month_arr[i];
            
              if($('#' + mnt).is(":checked"))
              {
                  idvalue =  $('#input'+mnt).val();  
                  total += parseInt(idvalue);
              }
       }
       
       
       
       $('#Total').html(total);
    }
    
    
    
    function get_revenue_validate()
    {
        var flag = false;
        var month_select = $('#month_check').val();
        var str_month_arr = month_select.split(",");
        for(var i=0; i<str_month_arr.length; i++)
        {
            var mnt = str_month_arr[i];
            
            if($('#' + mnt).is(":checked"))
            {
                flag=true;
                break;
            }
       }
       return flag;
    }
    
</script>