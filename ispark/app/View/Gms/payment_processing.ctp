<?php  
echo $this->Html->css('newDatePicker/jquery-ui');
echo $this->Html->css('newDatePicker/style');
echo $this->Html->script('newDatePicker/jquery-1.12.4');
echo $this->Html->script('newDatePicker/jquery-ui');
?>

  
  
  <script>
  $( function() {
    $( ".datepicker" ).datepicker();
  } );
  </script>
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
            <form method="get">
            <label class="col-sm-1 control-label">Year</label>
            <div class="col-sm-3">
            <?php	
                echo $this->Form->input('FinanceYear', array('label'=>false,'id'=>'FinanceYear','class'=>'form-control','options' => array_merge(array('All'=>'All'),$financeYearArr),'empty' => 'Select Year','value'=>$FinanceYear,'required'=>true));
            ?>
            </div>
            <input type="submit" class="btn btn-primary" value="Search">    
            <input type="button" onclick="tableToExcel('testTable', 'W3C Example Table')" class="btn btn-success" value="Export to Excel">
            </form>
            <?php if($page!=1) { ?>
            <input type="button" onclick="gotonext('<?php echo ($page-1); ?>')" class="btn btn-primary" value="Previous">
            <?php } ?>
            <input type="button" onclick="gotonext('<?php echo ($page+1); ?>')" class="btn btn-primary" value="Next">
            <a href="/ispark/Menuisps/sub?AX=NjA=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 

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
                        <?php echo $this->Form->create('Gms',array('action'=>'save_payment_processing1')); ?>
			<div class="box-content no-padding">
				<table class="table  table-bordered table-hover table-heading no-border-bottom responstable" id="testTable">
				<?php $case=array('',''); $i=0; ?>
					<thead>
						<tr class="active">
							<td>Sr. No.</td>
							<td>Branch </td>
                                                        <td>Grn No. </td>
							<td>Head</td>
                                                        <td>SubHead</td>
                                                        <td>Due Amount</td>
                                                        <td>Due Date</td>
                                                        <td>Grn File</td>
                                                        <td>Payment Mode</td>
                                                        <td>Payment Date</td>
                                                        <td>Bank Name</td>
                                                        <td>Transaction ID (Chq. No./Transaction ID/Imprest)</td>
						</tr>
                                        </thead>
                                        <tbody>
						<?php foreach ($data as $post){ ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
                                                        
							<td><?php echo $post['bm']['branch_name']; ?></td>
                                                        <td><?php echo $post['eem']['GrnNo']; ?></td>
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
                                                        <td>
                                                            <select name="PaymentModeA<?php echo $post['eem']['Id']; ?>" id="PaymentMode<?php echo $post['eem']['Id']; ?>" onchange="disable_bank('<?php echo $post['eem']['Id']; ?>',this.value)" >
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Transfer">Transfer</option>
                                                                <option value="Imprest">Imprest</option>
                                                            </select>
                                                            
                                                        </td>
                                                        <td>
                                                            <input type="text" name="payment_dateA<?php echo $post['eem']['Id']; ?>" id="payment_date<?php echo $post['eem']['Id']; ?>" value="" readonly=""  class="datepicker" style="width:80px"  />
                                                        </td>
                                                        <td>
                                                            <select name="bank_nameA<?php echo $post['eem']['Id']; ?>" id="bank_name<?php echo $post['eem']['Id']; ?>"  style="width:100px">
                                                                <option value="">Select</option>
                                                                <?php
                                                                        foreach($bank_master as $bank)
                                                                        {
                                                                ?>
                                                                <option value="<?php echo $bank;?>"><?php echo $bank;?></option>
                                                                <?php   }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="transidA<?php echo $post['eem']['Id']; ?>" id="transid<?php echo $post['eem']['Id']; ?>" value="" style="width:100px" />
                                                            <input type="hidden" name="grn_noA<?php echo $post['eem']['Id']; ?>" id="grn_no<?php echo $post['eem']['Id']; ?>" value="<?php echo $post['eem']['GrnNo']; ?>" />
                                                        </td>
						</tr>
                                                <?php  $GrnArrA[] = $post['eem']['Id']; } ?>
                                                <?php foreach ($data1 as $post){ ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
							<td><?php echo $post['bm']['branch_name']; ?></td>
                                                        <td><?php echo $post['eem']['GrnNo']; ?></td>
							<td><?php echo $post['head']['HeadingDesc']; ?></td>
                                                        <td><?php echo $post['subhead']['SubHeadingDesc']; ?></td>
                                                        <td><?php echo round($post['0']['Total'],2); ?></td>
                                                        <td><?php echo $post['eem']['due_date']; ?></td>
                                                        <td> &nbsp;
                                                    <?php if(!empty($post['eem']['grn_file'])) 
                                                        { $File = explode(',',$post['eem']['grn_file']);   $files_no=1;  
                                                        foreach($File as $file)
                                                        {
                             ?>   
                                                            <a href="<?php echo $this->webroot.'app/webroot/GRN/'.$file; ?>" target="_blank"> <?php echo 'File'.$files_no++; ?></a>
                                                  <?php } }  ?>
                                                            
                                                            
                                                        </td>
                                                        <td><select name="PaymentModeB<?php echo $post['eem']['Id']; ?>" id="PaymentMode<?php echo $post['eem']['Id']; ?>" onchange="disable_bank('<?php echo $post['eem']['Id']; ?>',this.value)" >
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Transfer">Transfer</option>
                                                                <option value="Imprest">Imprest</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="payment_dateB<?php echo $post['eem']['Id']; ?>" id="payment_date<?php echo $post['eem']['Id']; ?>" value="" readonly=""  class="datepicker" style="width:80px"  /></td>
                                                        <td>
                                                            <select name="bank_nameB<?php echo $post['eem']['Id']; ?>" id="bank_name<?php echo $post['eem']['Id']; ?>"  style="width:100px">
                                                                <option value="">Select</option>
                                                                <?php
                                                                        foreach($bank_master as $bank)
                                                                        {
                                                                ?>
                                                                <option value="<?php echo $bank;?>"><?php echo $bank;?></option>
                                                                <?php   }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="transidB<?php echo $post['eem']['Id']; ?>" id="transid<?php echo $post['eem']['Id']; ?>" value="" style="width:100px" /></td>
						</tr>
                                                 <?php  $GrnArrB[] = $post['eem']['Id']; } ?>
                                                <?php if(!empty($GrnArrA) || !empty($GrnArrB)) { ?>
                                                <tr><th colspan="12"><input type="submit" name="submit" id="submit" value="Save" class="btn btn-primary" /></th></tr>
                                                <?php } ?>
						<?php unset($data); ?>
					</tbody>
				</table>
			</div>
                        <?php  
                        echo $this->Form->textArea('GrnA',array('flag'=>false,'type'=>'hidden','id'=>'GrnA',"style"=>"display:none;",'value'=>implode(',',$GrnArrA)));
                        echo $this->Form->textArea('GrnB',array('flag'=>false,'type'=>'hidden','id'=>'GrnB',"style"=>"display:none;",'value'=>implode(',',$GrnArrB)));
                        echo $this->Form->end();
                        ?>
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

function gotonext(page)
{
    var FinanceYear=$("#FinanceYear").val();
    window.location.href="payment_processing?page="+page+'&FinanceYear='+FinanceYear;
}

var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()

</script>