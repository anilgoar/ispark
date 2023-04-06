<?php

?>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

table th,td{text-align: center;font-size: 13px;}

</style>
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
                    
                    <span>Budget / Business Case Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-horizontal">
                 <?php echo $this->Form->create('Expense'); ?>   
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','value'=>$FinBranch,'required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                            <div id="monthID">
                                <?php	
                                    echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','options' => array_merge(array('All'=>'All'),$financeYearArr),'empty' => 'Select Year','value'=>$FinYear,'required'=>true));
                                ?>
                            </div>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-3">
                            <div id="monthID">
                                <?php	$month = array('All'=>'All','Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                    echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','options' => $month,'empty' => 'Select Month','value'=>$FinMonth,'required'=>true));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        
                        <div class="col-sm-3">
                            <button class="btn btn-primary btn-label-left" onClick="return budget_validate12('Show');">Show</button>
				<button class="btn btn-primary btn-label-left" onClick="return budget_validate12('Export');">Export</button>
                                <a href="/ispark/Finances" class="btn btn-primary btn-label-left">Back</a> 
			</div>
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                   
                    </div>		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <div id="data">
                    <table border="2">
                     <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Branch</th>
                            <th>EntryNo</th>
                            <th>Finance Year</th>
                            <th>Finance Month</th>
                            <th>Head</th>
                            <th>Sub Head</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Payment File</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                   <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport);
                                foreach($ExpenseReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['0']['Branch']."</td>";
                                        echo "<td>".$exp['0']['EntryNo']."</td>";
                                        echo "<td>".$exp['0']['FinanceYear']."</td>";
                                        echo "<td>".$exp['0']['FinanceMonth']."</td>";
                                        echo "<td>".$exp['0']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['0']['SubHeadingDesc']."</td>";
                                        echo "<td>".$exp['0']['Amount']."</td>";
                                        echo "<td>".$exp['0']['date']."</td>";
                                        if($exp['0']['bus_status']!='Closed')
                                        echo '<td style="background-color:red;color:#FFF;text-align:center;"><b>'.$exp['0']['bus_status']."</b></td>";
                                        else
                                         echo '<td style="background-color:green;color:#FFF;text-align:center;"><b>'.$exp['0']['bus_status']."</b></td>";
                                        echo '<td style="text-align:center;">';
                                        if(!empty($exp['0']['PaymentFile']))
                                            echo '<a href="'.$this->webroot.'expense_file'.DS.$exp['0']['PaymentFile'].'">'.$this->Html->image('download.png', array('alt' => "download",'hieght'=>'15','width'=>'15','class' => 'img-rounded')).'</a>';
                                        echo '</td>';
                                        echo '<td style="text-align:center;">';
                                        if($exp['0']['bus_status']=='Closed' && $exp['0']['Action']=='1')
                                        {
                                            echo $this->Html->link('Re-Open Business Case',array('controller'=>'ExpenseEntries','action'=>'business_case_ropen','?'=>array('Id'=>$exp['0']['Id']),'full_base' => true));
                                        }
                                        else if($exp['0']['Action']=='1')
                                        {
                                            echo '<a href="#" id="myBtn" onclick="get_pop_up('."'".$exp['0']['Branch']."','".$exp['0']['FinanceYear']."','".$exp['0']['FinanceMonth']."','".$exp['0']['HeadingDesc']."','".$exp['0']['SubHeadingDesc']."','".$exp['0']['Amount']."','".$exp['0']['Id']."'".')">Close</a>';
                                        }
                                        echo '</td>';
                                    echo "</tr>";
                                     $Total += $exp['0']['Amount'];
                                }
                                echo "<tr>";
                                    echo '<td colspan="6"></td><td><b>Total</b></td>';
                                    echo '<td><b>'.$Total.'</b></td>';
                                    echo '<td colspan="4"></td>';
                                echo "</tr>";
                        ?>
                    </tbody>
                </table>   
                </div>
                <div class="form-horizontal">
                    <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="pop_up_close()">&times;</span>
                        <?php echo $this->Form->create('Expense'); ?> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Branch</label>
                            <div class="col-sm-2" id="BranchDisp"></div>
                            <label class="col-sm-2 control-label">Year</label>
                            <div class="col-sm-2" id="YearDisp"></div>
                            <label class="col-sm-2 control-label">Month</label>
                            <div class="col-sm-2" id="MonthDisp" style="position:relative;top:-10px"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Exp. Head</label>
                            <div class="col-sm-2" id="HeadDisp"></div>
                            <label class="col-sm-2 control-label">Exp. SubHead</label>
                            <div class="col-sm-2" id="SubHeadDisp"></div>
                            <label class="col-sm-2 control-label">Amount</label>
                            <div class="col-sm-2" id="AmountDisp"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-10 control-label">Consume</label>
                            <div class="col-sm-2" id="ConsumeDisp"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Remarks</label>
                            <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('remarks', array('label'=>false,'class'=>'form-control','id'=>'remarks','placeholder' => 'Remarks','required'=>true));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" name="submit" value="CloseBusinessCase" class="btn btn-primary btn-label-left">Close Business Case</button>
                        </div>
                        </div>
                        <?php	
                            echo $this->Form->input('ExpenseId', array('label'=>false,'type'=>'hidden','id'=>'ExpenseId','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('BranchQry', array('label'=>false,'type'=>'hidden','id'=>'BranchQry','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('YearQry', array('label'=>false,'type'=>'hidden','id'=>'YearQry','class'=>'form-control','value' => '','required'=>true));
                            echo $this->Form->input('MonthQry', array('label'=>false,'type'=>'hidden','id'=>'MonthQry','class'=>'form-control','value' => '','required'=>true));
                            
                        ?>
                        <?php echo $this->Form->end(); ?> 
                    </div>
                    </div>
                </div>   
            
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <div id="grn_detail"></div>
                <div id="grn_cost_center"></div>
            
            </div>
        </div>
    </div>
</div>
 
<script>
// Get the modal
function get_pop_up(Branch,FinanceYear,FinanceMonth,Head,SubHead,Amount,id)
{
    document.getElementById('BranchDisp').innerHTML = Branch;
    document.getElementById('YearDisp').innerHTML = FinanceYear;
    document.getElementById('MonthDisp').innerHTML = FinanceMonth;
    document.getElementById('HeadDisp').innerHTML = Head;
    document.getElementById('SubHeadDisp').innerHTML = SubHead;
    document.getElementById('AmountDisp').innerHTML = Amount;
    document.getElementById('ExpenseId').value = id;
    document.getElementById('remarks').value = '';
    document.getElementById('BranchQry').value = Branch;
    document.getElementById('YearQry').value = FinanceYear;
    document.getElementById('MonthQry').value = FinanceMonth;
    
    $.post("get_budget",
            {
             Branch:Branch,
             FinanceYear: FinanceYear,
             FinanceMonth:FinanceMonth,
             Head:Head,
             SubHead:SubHead
            },
            function(data,status){
              document.getElementById('ConsumeDisp').innerHTML = data; 
            }); 
    
    var modal = document.getElementById('myModal');
    modal.style.display = "block";
    
}
function pop_up_close()
{
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
}

function budget_validate12(val)
{
var	branch_Name = document.getElementById('ExpenseBranchName').value;

var	year = document.getElementById('ExpenseFinanceYear').value;
var month = document.getElementById('ExpenseFinanceMonth').value;

	if(branch_Name =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
	
			
	if(year =='')
	{
		alert('Please Select Year');
		return false;
	}
	if(month =='')
	{

		alert('Please Select From Month');
		
	}
        
        if(val=='Export') 
        {        
            return budgetreport12(branch_Name,year,month,val);
        }
        else
        {
            var url='export_budget';
            var uqry='BranchName='+branch_Name+'&year='+year+'&month='+month+'&type='+val;
            ajax51(url,uqry,'data');
            return false;
        }
}

function budgetreport12(branchName,year,month,val)
{
	var url='export_budget/?BranchName='+branchName+'&year='+year+'&month='+month+'&type='+val;
	window.location.href = url;
        return false;
}

function get_proccesed(branchName,year,month,headid,subheadid,expenseId)
{
    var url='get_proccesed';
            var uqry='BranchName='+branchName+'&year='+year+'&month='+month+'&headid='+headid+'&subheadid='+subheadid;
            ajax51(url,uqry,'grn_detail');
            $('#grn_cost_center').html('');
            return false;
}



function get_cost_center(branch_name,expenseId)
{
    var url='get_expense_cost_center';
            var uqry='BranchName='+branch_name+'&expenseId='+expenseId;
            ajax51(url,uqry,'grn_cost_center');
            return false;
}

function get_value_by_id(div_id)
    {
        var div_value = $('#'+div_id).val();
        if(div_value=='')
        {
            return 0;
        }
        else
        {
            return div_value;
        }
    }

function set_value_by_id(div_id,value)
    {
         $('#'+div_id).val(value);
    }

function getsum()
    {
        var cost_arr = document.getElementsByClassName("cost");
        var gst_enable = parseInt(get_value_by_id("gst_enable"));
        var tot_amt = 0;
        var tot_tax = 0;
        var net_tot = 0;
        
        for(var a = 0; a<cost_arr.length;a++)
        {
            //console.log(cost_arr[a].id);
            var cost_id =  cost_arr[a].id;
            var cost_amount = parseFloat(get_value_by_id(''+cost_id));
                tot_amt +=cost_amount;
            if(gst_enable===1)
            {
                
                if(cost_amount!=='')
                {
                    
                    var gst_per = parseFloat(get_value_by_id('gst_per'+cost_id));
                    //console.log(gst_per);
                    if(gst_per===0)
                    {
                        //alert("Please Select Gst");
                        //$('#'+cost_amount).focus();
                    }
                    else
                    {
                        //console.log("print3");
                       // var tax_per = get_value_by_id('gst_per'+cost_id);
                        var tax = (cost_amount*gst_per/100).toFixed(2);
                        set_value_by_id('tax'+cost_id,tax);
                        tot_tax += parseFloat(tax);
                        
                        var total_with_tax = (parseFloat(tax)+cost_amount).toFixed(2);
                        //console.log(total_with_tax);
                        set_value_by_id('cost_total'+cost_id,total_with_tax);
                        net_tot += parseFloat(total_with_tax);
                    }
                }
            }
            else
            {
                set_value_by_id('cost_total'+cost_id,cost_amount);
                net_tot +=parseFloat(cost_amount);
                //console.log("5-"+"cost_amount"+cost_id+"="+cost_amount);
            }
        }

        set_value_by_id('amount_grn_sum',tot_amt);
        set_value_by_id('tax_grn_sum',tot_tax);
        set_value_by_id('grn_total',net_tot);
        
       //alert(cost_array);
        
    }

function getsum_check()
    {
        var cost_arr = document.getElementsByClassName("cost");
        var gst_enable = parseInt(get_value_by_id("gst_enable"));
        var tot_amt = 0;
        var tot_tax = 0;
        var net_tot = 0;
        
        for(var a = 0; a<cost_arr.length;a++)
        {
            //console.log(cost_arr[a].id);
            var cost_id =  cost_arr[a].id;
            var cost_amount = parseFloat(get_value_by_id(''+cost_id));
                tot_amt +=cost_amount;
            if(gst_enable===1)
            {
                
                if(cost_amount!=='')
                {
                    
                    var gst_per = parseFloat(get_value_by_id('gst_per'+cost_id));
                    //console.log(gst_per);
                    if(gst_per===0)
                    {
                        alert("Please Select Gst");
                        $('#gst_per'+cost_id).focus();
                        return false;
                    }
                    else
                    {
                        //console.log("print3");
                       // var tax_per = get_value_by_id('gst_per'+cost_id);
                        var tax = (cost_amount*gst_per/100).toFixed(2);
                        set_value_by_id('tax'+cost_id,tax);
                        tot_tax += parseFloat(tax);
                        
                        var total_with_tax = (parseFloat(tax)+cost_amount).toFixed(2);
                        //console.log(total_with_tax);
                        set_value_by_id('cost_total'+cost_id,total_with_tax);
                        net_tot += parseFloat(total_with_tax);
                    }
                }
            }
            else
            {
                set_value_by_id('cost_total'+cost_id,cost_amount);
                net_tot +=cost_amount;
                //console.log("5-"+"cost_total"+cost_id+"="+cost_amount);
            }
        }

        set_value_by_id('amount_grn_sum',tot_amt);
        set_value_by_id('tax_grn_sum',tot_tax);
        set_value_by_id('grn_total',net_tot);
        
       //alert(cost_array);
       return true;
        
    }

function save_grn_cost_center()
{
    var result = getsum_check();
    if(result===true)
    {
        var total_grn = parseInt($('#grn_total').val());
        var new_total = parseInt($('#total_grn_sum').val());
        if((total_grn-new_total)<=1)
        {
            //alert("record updated successfully.");
            //var form_id = document.getElementById('save_grn_cost');
            var form = $('#save_grn_cost').serialize();
            
            $.ajax({
           type: "POST",
           url: 'save_changes_to_cost_center',
           data: form, // serializes the form's elements.
           success: function(data)
           {
               alert(data); // show response from the php script.
           }
         });

        }
    }
}

function get_unproccesed(branchName,year,month,headid,subheadid,expenseId)
{
    var url='get_unproccesed';
            var uqry='BranchName='+branchName+'&year='+year+'&month='+month+'&headid='+headid+'&subheadid='+subheadid+'&expenseId='+expenseId;
            ajax51(url,uqry,'grn_detail');
            $('#grn_cost_center').html('');
            return false;
}

function get_cost_center_un(branch_name,expenseId)
{
    var url='get_budget_cost_center';
            var uqry='BranchName='+branch_name+'&expenseId='+expenseId;
            ajax51(url,uqry,'grn_cost_center');
            return false;
}

function getsum_un()
    {
        var cost_arr = document.getElementsByClassName("cost");
        var tot_amt = 0;
        for(var a = 0; a<cost_arr.length;a++)
        {
            //console.log(cost_arr[a].id);
            var cost_id =  cost_arr[a].id;
            var cost_amount = parseFloat(get_value_by_id(''+cost_id));
            tot_amt +=cost_amount;
            set_value_by_id('cost_total'+cost_id,cost_amount);
        }
        set_value_by_id('amount_grn_sum',tot_amt);
        
    }

function save_grn_cost_center_un()
{
    var total_grn = parseInt($('#amount_grn_sum').val());
    var new_total = parseInt($('#total_grn_sum').val());
    if(total_grn===new_total)
    {
        //alert("record updated successfully.");
        //var form_id = document.getElementById('save_grn_cost');
        var form = $('#save_grn_cost').serialize();

        $.ajax({
       type: "POST",
       url: 'save_changes_to_cost_center_budget',
       data: form, // serializes the form's elements.
       success: function(data)
       {
           alert(data); // show response from the php script.
       }
     });

    }
    
}
</script>
