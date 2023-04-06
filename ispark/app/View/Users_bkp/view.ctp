<?php ?>
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

<!-- File: /app/View/UserType/index.ctp -->


<h1>Welcome <?php echo ucwords(str_replace('.', ' ', $username)); ?></h1>

<?php if($this->Session->read('role')=='admin') { ?>
<h4 class="bg-active"><b style="color:#FF0000;font-size:14px"><?php echo 'Last Invoice No. '.$LastBill['InitialInvoice']['bill_no'].' ( '.$LastBill['InitialInvoice']['InvoiceDescription'].' )'.' to '.$LastBill['InitialInvoice']['client'].' for Month '.$LastBill['InitialInvoice']['month'].' Generated Successfully </b>'; ?> </h4>
<?php } ?>

<?php 
if(!empty($provision) || !empty($provision2) || !empty($provision3))
{
echo $this->Form->create('User',array('url'=>'add_date'));
?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-search"></i>
                    <span> Pending</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
            <?php if(!empty($provision3)) {  ?>
            <table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                <tr>
                    <th colspan = "6"> Bill To Be Initiated </th>
                </tr>
                <tr>
                    <th> Sr. No </th>
                    <th> Branch Name </th>
                    <th> Cost Center </th>
                    <th> Finance Year </th>
                    <th> Month </th>
                    <th> Amount </th>
                </tr>
                <?php $i = 1; $IniTotal = 0;
                    foreach($provision3 as $pr):
                        echo "<tr>";
                        echo "<td>".$i++."</td>";
                        echo "<td>".$pr['Provision']['branch_name']."</td>";
                        echo "<td>".$pr['Provision']['cost_center']."</td>";
                        echo "<td>".$pr['Provision']['finance_year']."</td>";
                        echo "<td>".$pr['Provision']['month']."</td>";
                        echo "<td>".$pr['Provision']['provision_balance']."</td>";
                        echo "</tr>";
                        $IniTotal +=$pr['Provision']['provision_balance'];
                    endforeach;
                    echo "<tr><th colspan='5'>Total</th>";
                    echo "<th>".$IniTotal."</th>";
                    echo "</tr>";
                ?>
            </table>
            <?php } if(!empty($provision)) {  ?>
                <table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                <tr>
                    <th colspan = "9"> PO Pending List</th>
                </tr>
                    <tr>
                    <th>Branch Name</th>
                    <th>Cost Center</th>
                    <th>Finance Year</th>
                    <th>Month</th>
                    <th>Invoice No</th>
                    <th>Invoice Description</th>
                    <th>Amount</th>
                    <th>PO Action Date</th>
                    <th>PO Remarks</th>
                </tr>
                <?php  $PoTotal = 0; //print_r($provision);
                foreach($provision as $wd):
                    echo "<tr>";
                            echo "<td>".$wd['tb']['branch_name']."</td>";
                            echo "<td>".$wd['tb']['cost_center']."</td>";
                            echo "<td>".$wd['tb']['finance_year']."</td>";
                            echo "<td>".$wd['tb']['month']."</td>";
                            echo "<td>".$wd['tb']['bill_no']."</td>";
                            echo "<td>".$wd['tb']['invoiceDescription']."</td>";
                            echo "<td>".$wd['tb']['grnd']."</td>";
                              $id = $wd['tb']['id'];
                              $str = "data[User][".$id."][po_date]";
                            echo "<td>".$this->Form->input($id.'.po_date',array("label"=>false,"class"=>"form-conctrol","placeholder"=>"Date",'id'=>$id,'onBlur'=>"getRemark(this.id)",'onClick'=>"displayDatePicker('$str');"))."</td>";
                            echo "<td>".$this->Form->input($id.'.po_remarks',array("label"=>false,"class"=>"form-conctrol","placeholder"=>"remarks"))."</td>";
                    echo "</tr>";
                    $PoTotal +=$wd['tb']['grnd'];
                endforeach;
                echo "<tr><th colspan='6'>Total</th>";
                echo "<th>".$PoTotal."</th><th colspan='2'></th>";
                echo "</tr>";
                ?>
                </table>
<?php } if(!empty($provision2)) {  ?>
                <table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                <tr>
                    <th colspan = "9"> GRN Pending List</th>
                </tr>
                <tr>
                    <th>Branch Name</th>
                    <th>Cost Center</th>
                    <th>Finance Year</th>
                    <th>Month</th>
                    <th>Invoice No</th>
                    <th>Invoice Description</th>
                    <th>Amount</th>
                    <th>GRN Action Date</th>
                    <th>GRN Remarks</th>
                </tr>
                <?php $GrnTotal = 0; 
                foreach($provision2 as $wd):
                    echo "<tr>";
                            echo "<td>".$wd['tb']['branch_name']."</td>";
                            echo "<td>".$wd['tb']['cost_center']."</td>";
                            echo "<td>".$wd['tb']['finance_year']."</td>";
                            echo "<td>".$wd['tb']['month']."</td>";
                            echo "<td>".$wd['tb']['bill_no']."</td>";
                            echo "<td>".$wd['tb']['invoiceDescription']."</td>";
                            echo "<td>".$wd['tb']['grnd']."</td>";
                              $id = $wd['tb']['id'];
                              $str = "data[User][".$id."][grn_date]";
                            echo "<td>".$this->Form->input($id.'.grn_date',array("label"=>false,"class"=>"form-conctrol","placeholder"=>"Date",'id'=>$id,'onBlur'=>"getRemark2(this.id)",'onClick'=>"displayDatePicker('$str');"))."</td>";
                            echo "<td>".$this->Form->input($id.'.grn_remarks',array("label"=>false,"class"=>"form-conctrol","placeholder"=>"remarks"))."</td>";
                    echo "</tr>";
                   $GrnTotal += $wd['tb']['grnd'];
                endforeach; 
                echo "<tr><th colspan='6'>Total</th>";
                echo "<th>".$GrnTotal."</th><th colspan='2'></th>";
                echo "</tr>";
                ?>
                </table>
                
                <?php } if(!empty($provision) || !empty($provision2)) { ?>
		<div class="clearfix"></div>
		<button type="submit" class="btn btn-primary">Add </button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
