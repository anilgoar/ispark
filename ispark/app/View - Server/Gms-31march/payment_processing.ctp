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
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>Add GRN Payment Processing</span>
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
			<div class="box-content no-padding">
				<table class="table  table-bordered table-hover table-heading no-border-bottom responstable" id="table_id">
				<?php $case=array('',''); $i=0; ?>
					<thead>
						<tr class="active">
							<td>Sr. No.</td>
                                                        
							<td>Branch </td>
							<td>Head</td>
                                                        <td>SubHead</td>
                                                        <td>Due Amount</td>
                                                        <td>Due Date</td>
                                                        <td>Grn File</td>
                                                        <td>Payment Mode</td>
                                                        <td>Payment Date</td>
                                                        <td>Bank Name</td>
                                                        <td>Transaction ID (Chq. No./Transaction ID/Imprest)</td>
                                                        <td>Action</td>
						</tr>
                                        </thead>
                                        <tbody>
						<?php foreach ($data as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
                                                        
							<td><?php echo $post['bm']['branch_name']; ?></td>
							<td><?php echo $post['head']['HeadingDesc']; ?></td>
                                                        <td><?php echo $post['subhead']['SubHeadingDesc']; ?></td>
                                                        <td><?php echo round($post['0']['Total'],2); ?></td>
                                                        <td><?php echo $post['eem']['due_date']; ?></td>
                                                        <td> &nbsp;<?php if(!empty($post['eem']['grn_file'])) { $File = explode(',',$post['eem']['grn_file']); $files_no=1;  
                                                        foreach($File as $file)
                                                        {
                             ?>   
                                                            <a href="<?php echo $this->webroot.'app/webroot/GRN/'.$file; ?>" target="_blank"> <?php echo 'File'.$files_no++; ?></a>
                                                        <?php } }  ?>
                                                            
                                                            
                                                        </td>
                                                        <td><select name="PaymentMode<?php echo $i; ?>" id="PaymentMode<?php echo $i; ?>" onchange="disable_bank('<?php echo $i; ?>',this.value)" required="">
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Transfer">Transfer</option>
                                                                <option value="Imprest">Imprest</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="payment_date<?php echo $i; ?>" id="payment_date<?php echo $i; ?>" value="" readonly="" required="" onclick="displayDatePicker('payment_date<?php echo $i; ?>')" /></td>
                                                        <td><input type="text" name="bank_name<?php echo $i; ?>" id="bank_name<?php echo $i; ?>" value="" /></td>
                                                        <td><input type="text" name="transid<?php echo $i; ?>" id="transid<?php echo $i; ?>" value="" /></td>
                                                        <td>
                                                            <input type="hidden" name="grn_no<?php echo $i; ?>" id="grn_no<?php echo $i; ?>" value="<?php echo $post['eem']['GrnNo']; ?>" />
                                                            <input type="hidden" name="grn_id<?php echo $i; ?>" id="grn_id<?php echo $i; ?>" value="<?php echo $post['eem']['Id']; ?>" />
                                                            <button id="Save" name="Save" value="<?php echo $i; ?>" onclick="save_record(this.value)" class="btn btn-primary" >Save</button></td>
						</tr>
						<?php endforeach; ?>
                                                <?php foreach ($data1 as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
							<td><?php echo $post['bm']['branch_name']; ?></td>
							<td><?php echo $post['head']['HeadingDesc']; ?></td>
                                                        <td><?php echo $post['subhead']['SubHeadingDesc']; ?></td>
                                                        <td><?php echo round($post['0']['Total'],2); ?></td>
                                                        <td><?php echo $post['eem']['due_date']; ?></td>
                                                        <td> &nbsp;<?php if(!empty($post['eem']['grn_file'])) { $File = explode(',',$post['eem']['grn_file']);   $files_no=1;  
                                                        foreach($File as $file)
                                                        {
                             ?>   
                                                            <a href="<?php echo $this->webroot.'app/webroot/GRN/'.$file; ?>" target="_blank"> <?php echo 'File'.$files_no++; ?></a>
                                                        <?php } }  ?>
                                                            
                                                            
                                                        </td>
                                                        <td><select name="PaymentMode<?php echo $i; ?>" id="PaymentMode<?php echo $i; ?>" onchange="disable_bank('<?php echo $i; ?>',this.value)" required="">
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Transfer">Transfer</option>
                                                                <option value="Imprest">Imprest</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="payment_date<?php echo $i; ?>" id="payment_date<?php echo $i; ?>" value="" readonly="" required="" onclick="displayDatePicker('payment_date<?php echo $i; ?>')" /></td>
                                                        <td><input type="text" name="bank_name<?php echo $i; ?>" id="bank_name<?php echo $i; ?>" value="" /></td>
                                                        <td><input type="text" name="transid<?php echo $i; ?>" id="transid<?php echo $i; ?>" value="" /></td>
                                                        <td>
                                                            <input type="hidden" name="grn_id<?php echo $i; ?>" id="grn_id<?php echo $i; ?>" value="<?php echo $post['eem']['Id']; ?>" />
                                                            <button id="Save" name="Save" value="<?php echo $i; ?>" onclick="save_record1(this.value)" class="btn btn-primary" >Save</button></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($data); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    
    
    function disable_bank(id,value)
    {
        if(value=='Imprest')
        {
            $('#bank_name'+id).val('');
            $('#bank_name'+id).prop('readonly',true);
        }
        else
        {
            $('#bank_name'+id).val('');
            $('#bank_name'+id).prop('readonly',false);
        }
        
    }
    
function save_record(id)
{
    
    var PaymentMode=$("#PaymentMode"+id).val();
    var payment_date=$("#payment_date"+id).val();
    var bank_name=$("#bank_name"+id).val();
    var transid=$("#transid"+id).val();
    var grn_no=$("#grn_no"+id).val();
    var grn_id=$("#grn_id"+id).val();
    
    
    if(PaymentMode==''){ return;}

    if(payment_date==''){ return;}
        
    if(PaymentMode!='Imprest' && bank_name==''){ return;}
        
    if(transid==''){ return;}
    
    if(grn_no==''){ return;}
    
    if(grn_id==''){ return;}
    
    
    
    $.post("save_payment_processing",
            {
             GrnId: grn_id,
             GrnNo: grn_no,
             PaymentMode: PaymentMode,
             PaymentDate: payment_date,
             BankName:bank_name,
             TransactionId:transid
            },
            function(data,status)
            {
                if(data==1)
                {
                    alert("Record has been saved");
                    location.reload();
                }
                else
                {
                    alert("Record has been Not Saved");
                }
            });
}
    
function save_record1(id)
{
    
    var PaymentMode=$("#PaymentMode"+id).val();
    var payment_date=$("#payment_date"+id).val();
    var bank_name=$("#bank_name"+id).val();
    var transid=$("#transid"+id).val();
    var grn_id=$("#grn_id"+id).val();
    
    
    if(PaymentMode==''){ return;}

    if(payment_date==''){ return;}
        
    if(PaymentMode!='Imprest' && bank_name==''){ return;}
        
    if(transid==''){ return;}
    
    if(grn_id==''){ return;}
    
    
    
    $.post("save_payment_processing1",
            {
             GrnId: grn_id,
             PaymentMode: PaymentMode,
             PaymentDate: payment_date,
             BankName:bank_name,
             TransactionId:transid
            },
            function(data,status)
            {
                if(data==1)
                {
                    alert("Record has been saved");
                    location.reload();
                }
                else
                {
                    alert("Record has been Not Saved");
                }
            });
}    
</script>