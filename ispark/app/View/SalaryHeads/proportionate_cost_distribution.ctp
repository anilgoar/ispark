<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
	</div>
</div>

<div class="box-content">
				<h4 class="page-header">Proportionate Cost Distribution</h4>
				
<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('SalaryHeads',array('class'=>'form-horizontal')); ?>
    <div class="form-group">
        <label class="col-sm-2 control-label">From Branch</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('Branch',array('label' => false,'options'=>$branch,'id'=>'Branch','class'=>'form-control','empty'=>'Select','onchange'=>"get_cost_center1()",'required'=>true)); ?>
        </div>
        <label class="col-sm-2 control-label">From Cost Center</label>
        <div class="col-sm-2">
                <?php if(empty($cost_center)) { $cost_center='';} echo $this->Form->input('CostCenter',array('label' => false,'id'=>'CostCenter','class'=>'form-control','options'=>$cost_center,'required'=>true)); ?>
        </div>
    </div>
    <div class="form-group">                            
        
        
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('Year',array('label' => false,'id'=>'Year','class'=>'form-control','options'=>array('2017-18'=>'2017-18','2018-19'=>'2018-19','2019-20'=>'2019-20'),'required'=>true)); ?>
        </div>
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-2">
            <?php echo $this->Form->input('Month',array('label' => false,'id'=>'Month','class'=>'form-control','options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),'required'=>true)); ?>
        </div>
        <label class="col-sm-1 control-label">Amount</label>
        <div class="col-sm-1">
            <?php echo $this->Form->input('Amount',array('label' => false,'class'=>'form-control','id'=>'amount','value'=>$amount,'onKeypress'=>"return isNumberKey(event)",'required'=>true)); ?>
        </div>
    </div>
    <div class="form-group">

    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">To Branch</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('Branch2',array('label' => false,'options'=>$branch,'id'=>'Branch2','class'=>'form-control','empty'=>'Select','onchange'=>"get_cost_center2()")); ?>
        </div>
        <label class="col-sm-2 control-label">TO Cost Center</label>
        <div class="col-sm-2">
            <?php echo $this->Form->input('CostCenter2',array('label' => false,'id'=>'CostCenter2','class'=>'form-control','options'=>'')); ?>
        </div>
        <label class="col-sm-1 control-label">Amount</label>
        <div class="col-sm-1">
            <?php echo $this->Form->input('Amount2',array('label' => false,'class'=>'form-control','id'=>'Amount2','value'=>'','onKeypress'=>"return isNumberKey(event)")); ?>
        </div>
        <div class="col-sm-2">
            <div class="btn btn-primary" onclick="add_record()">Add</div>
        </div>
    </div> 
    <div class="form-group" id="recordDisp">
        <?php
        
                echo '<table border="2">';

                echo "<tr>";
                    echo "<th>Branch</th>";
                    echo "<th>CostCenter</th>";
                    echo "<th>Amount</th>";
                echo "</tr>";


                foreach($data as $d)
                {
                    echo "<tr>";
                        echo "<td>".$d['bm']['branch_name'].'</td>';
                        echo "<td>".$d['ccctp']['ToCostCenter'].'</td>';
                        echo "<td>".$d['ccctp']['ToAmount'].'</td>';
                    echo "</tr>";
                    $Total +=$d['ccctp']['ToAmount'];
                }
                echo "<tr>";
                        echo '<th colspan="2">Total</th>';
                        echo "<td>".$Total.'</td>';
                    echo "</tr>";
                echo "</table>";
        ?>
    </div>
                                
    <div class="clearfix"></div>
    <div class="form-group">
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary btn-label-left">Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
			</div>

<script>
    function get_cost_center1()
    {
        var branch = $('#Branch').val();
        get_cost_center(branch,'CostCenter');
    }
    function get_cost_center2()
    {
        var branch = $('#Branch2').val();
        get_cost_center(branch,'CostCenter2');
    }
    
    function get_cost_center(Branch,CostId)
    {
        $.post("get_cost_center",
            {
             Branch:Branch
            },
            function(data,status){
                var text='<option value="">Select</option>';
                   var json = jQuery.parseJSON(data);
                   for(var i in json)
                   {
                       text += '<option value="'+i+'">'+json[i]+'</option>';
                   }
                $('#'+CostId).html(text);
            });
    }
    
    function add_record()
    {
       var FromBranch       = $('#Branch').val();
       var FromCostCenter   = $('#CostCenter').val();
       var FromAmount       = $('#amount').val();
       var Year             = $('#Year').val();
       var Month             = $('#Month').val();
       
       var ToBranch = $('#Branch2').val();
       var ToCostCenter = $('#CostCenter2').val();
       var ToAmount = $('#Amount2').val();
       
       if(FromBranch=='')
       {
           alert("Please Select From Branch");
           return false;
       }
       if(FromAmount=='')
       {
           alert("Please Fill Amount No");
           return false;
       }
       if(Year=='')
       {
           alert("Please Select Year");
           return false;
       }
       if(Month=='')
       {
           alert("Please Select Month");
           return false;
       }
       if(ToBranch=='')
       {
           alert("Please Select To Branch");
           return false;
       }
       if(ToCostCenter=='')
       {
           alert("Please Select To CostCenter");
           return false;
       }
       if(ToAmount=='')
       {
           alert("Please Fill To Amount");
           return false;
       }
       
       
       $.post("save_from",
            {
             Branch:FromBranch,
             CostCenter:FromCostCenter,
             Amount:FromAmount,
             FinanceMonth:Month,
             FinanceYear:Year
            },
            function(data,status){
                //alert(data);
                  if(data=='1')
                  {
                      $('#Branch2').val("");
                      $('#CostCenter2').html("");
                      $('#Amount2').val("");
                    $.post("save_to",
                    {
                        TransferId:data,
                        Branch:ToBranch,
                        CostCenter:ToCostCenter,
                        Amount:ToAmount,
                        FinanceMonth:Month,
                        FinanceYear:Year
                    },
                    function(data,status){
                        if(data=='0')
                        {
                            alert("Record Not Saved");
                        }
                        else if(data=='2')
                        {
                            alert("Entry Total Amount is More Than Amount");
                        }
                        else
                        {
                            $('#recordDisp').html(data);
                            alert("Record Saved");
                        }
                        
                    });  
                  } 
                  else if(data=='2')
                  {
                      alert("Salary Not Uploaded");
                  }
                  else if(data=='3')
                  {
                      alert("Amount is More Than Salary Amount");
                  }
                  else if(data=='4')
                  {
                      alert("Record Allready Exist. Please Edit");
                  }
                  else
                  {
                      alert("Record Not Saved");
                  }
            });
            
          
    }
</script>    