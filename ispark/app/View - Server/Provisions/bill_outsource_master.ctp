<?php echo $this->Form->create('Provision',array('class'=>'form-horizontal','url'=>"bill_outsource_master_save",'enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Revenue Out-Source-Master</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                
		<div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                    <?php	
                            echo $this->Form->input('branch_name', 
                                    array('label'=>false,
                                        'class'=>'form-control',
                                        'id'=>'branch',
                                        'value' => $provision['branch_name'],
                                        'required'=>true,'readonly'=>true));
                    ?>
                    </div>

                    
                    

                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('finance_year', 
                                array('value' => $provision['finance_year'],
                                    'id'=>'year',
                                    'label' => false, 
                                    'div' => false,
                                    'class'=>'form-control','readonly'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <?php	
                                echo $this->Form->input('month', 
                                        array('label'=>false,
                                            'class'=>'form-control',
                                            'id'=>'month',
                                            'value' => $provision['month'],
                                            'required'=>true,
                                            'readonly'=>true
                                        ));
                         ?>
                    </div>
                </div>

		
            <div class="form-group">
                <label class="col-sm-2 control-label">Cost Center</label>
                <div class="col-sm-3">
                <?php	echo $this->Form->input('cost_center', array('label'=>false,
                    'id'=>'cost_center',
                    'class'=>'form-control',
                    'value' => $provision['cost_center'],
                    'readonly'=>true,
                    'required'=>true)); ?>
                </div>

                <label class="col-sm-1 control-label">Billing</label>
                <div class="col-sm-2">
                <?php	echo $this->Form->input('billing', array('label'=>false,
                    'id'=>'billing',
                    'value' => $provision['billing'],
                    'class'=>'form-control',
                    'placeholder' => 'billing',
                    'readonly'=>true,
                    'required'=>true)); ?>
                </div>

                <label class="col-sm-1 control-label">Remarks</label>
                <div class="col-sm-2">
                    <?php	echo $this->Form->textArea('remarks', array('label'=>false,
                        'class'=>'form-control',
                         'value' => $provision['remarks'],
                        'placeholder'=>'Remarks',
                        'required'=>true)); ?>
                </div>   
            </div>
		
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Branch</th>
                        <th>CostCenter</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php	
                            echo $this->Form->input('branch_name1', 
                                    array('label'=>false,
                                        'class'=>'form-control',
                                        'id'=>'branch1',
                                        'options' => $branch_master,
                                        'onchange'=>"get_costcenter_rev(this.value)",
                                        'empty' => 'Select'));
                            ?>
                        </td>
                        <td>
                            <?php	echo $this->Form->input('cost_center1', array('label'=>false,
                        'id'=>'cost_center1',
                        'class'=>'form-control',
                        'options' => '',
                        'empty' => 'Select')); ?>
                    
                        </td>
                        <td>
                            
                    
                    <?php echo $this->Form->input('billing1', array('label'=>false,
                        'id'=>'billing1',
                        'class'=>'form-control',
                        'placeholder' => 'OutSource Amount',
                        'onkeypress'=>"return isNumberKey(event)")); ?>
                    
                        </td>
                        <td><button type="button" id="add" onclick="add_billing_parts()" class="btn btn-primary" > Add</button></td>
                    </tr>
                    </thead>
                    <tbody id="data_tab">
                        <?php $i = 1;
                        foreach($table_arr as $table)
                        {
                            echo "<tr>";
                            echo "<td>".$i++."</th>";
                                echo "<td>".$table['ppt']['Branch_OutSource']."</td>";
                                echo "<td>".$table['ppt']['Cost_Center_OutSource']."</td>";
                                echo "<td>".$table['ppt']['outsource_amt']."</td>";
                                echo '<td><input type="button" value="Delete" class="btn btn-danger" onclick="delete_bill_part('."'".$table['ppt']['provision_part_id']."'".')"></td>';
                            echo "</tr>";
                            $total += $table['ppt']['outsource_amt'];
                        }
                        echo "<tr>";
                            echo '<th colspan="3">Total</th>';
                            echo '<th id="total" align="right">'.$total.'</th>';
                            echo '<th></th>';
                        echo "</tr>";
                        
                        ?>
                    </tbody>
                    
                </table>
                
		<div class="clearfix"></div>
		<div class="form-group">
                   <div class="col-sm-2">
                       <button type="submit" class="btn btn-primary btn-label-left" onclick="return validate_billing()">Save</button>
                </div> 
		</div>
            </div>
        </div>
    </div>
</div>
<?php 
echo $this->Form->input('provision_id',array('type'=>'hidden', 'id'=>'provision_id','value'=>$provision_bill['0']['pm']['id']));
echo $this->Form->end(); ?>

<script>
function get_costcenter_rev(branch)
{
    var FinanceYear = $('#year').val();
   // var branch = $('#branchOut').val();
    var month = $('#month').val();
   // var month = $('#month').val();
    
    $.post("get_cost_rev",
        {
         Branch: branch,
         FinanceYear:FinanceYear,
         FinanceMonth:month
        },
        function(data,status){
            $("#cost_center1").html(data);
        });  
}

function validate_billing()
{
    return true;
}

function delete_bill_part(id)
{
    $.post("delete_bill_part",
        {
         id: id
        },
        function(data,status){
            location.reload();
        });  
}

function add_billing_parts()
{
    var gBranch = $('#branch').val();
    var year = $('#year').val();
    var month = $('#month').val();
    var cost_center = $('#cost_center').val();
    var billing = $('#billing').val();
    
    var branch1 = $('#branch1').val();
    var cost_center1 = $('#cost_center1').val();
    var billing1 = $('#billing1').val();
    var provision_id = $('#provision_id').val();
    $.post("add_outsource_record",
        {
         gBranch: gBranch,
         FinanceYear:year,
         FinanceMonth:month,
         cost_center:cost_center,
         billing:billing,
         branch1:branch1,
         cost_center1:cost_center1,
         billing1:billing1,
         provision_id:provision_id
        },
        function(data,status){
            if(data=='Record Saved Successfully')
            {
                alert(data);
                location.reload();
            }
            else
            {
                alert(data);
            }
            
        });  
}

</script>