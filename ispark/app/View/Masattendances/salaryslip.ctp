<script>
    
    function getsinglePdf(val,salmonth,salyear){
        
        //var url ="&ClientId=" +$("#userIds").val()+"&BillMonth="+$("#BillMonth").val();
        var url ="empCodes=" +val+"&Month="+salmonth+"&Year="+salyear;
            //alert(url);
        window.open ("<?php echo $this->webroot.'app/webroot/html2pdf/examples/exemple00.php?'; ?>"+url,'_blank');
        return false;
        
    }
    
    function getBillMonth(clientid){
        $.post("<?php echo $this->webroot;?>BillingReports/getbillmonth",{clientid:clientid},function(data){
        $("#BillMonth").html(data);
    });
    }
    
    
    
    
    function getPdf(){
        
        //var url ="&ClientId=" +$("#userIds").val()+"&BillMonth="+$("#BillMonth").val();
        var url ="empCodes=" +$("#UserIds").val();
            //alert(url);
        myWindow=window.open ("<?php echo $this->webroot.'app/webroot/html2pdf/examples/pdf.php?'; ?>"+url,'_blank');
         
        return false;
        
    }
     function closeWin()
    {
    myWindow.close();
    }
    function getBillMonth(clientid){
        $.post("<?php echo $this->webroot;?>BillingReports/getbillmonth",{clientid:clientid},function(data){
        $("#BillMonth").html(data);
    });
    }
    
</script>
<style>
    table td{margin: 5px;}
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
<?php echo $this->Form->create('upload',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Print Salary Slip</span>
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
                
		<div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('finance_year',array('label' => false,'options'=>array('2017'=>'2017','2018'=>'2018'),'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php echo $this->Form->input('month',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'month')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    </div>
<div class="form-group has-info has-feedback">
<label class="col-sm-2 control-label">Dept</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php
               //print_r($Data1);die;
              // foreach($Data as $d){
               echo $this->Form->input('Dept',array('label' => false,'options'=>$Data1,'class'=>'form-control','empty'=>'Select','id'=>'Dept'));  ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div> </div></div>
<?php echo $this->Form->end(); ?>
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                       <input type="submit" class="btn btn-info"  name='export' value="Show" >
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>
<form method="Get"  class="form-horizontal row-border" onsubmit="return getPdf()" target="posthereonly">
<div id="mm">
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active','','info'); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>EmpCode</th>
							<th>Name</th>
							<th>Father Name</th>
							<th>Doj</th>
							<th colspan="2">Action</th>
						</tr>
						<?php   foreach ($data as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>" align="center">
							<?php $id= $post['Salary']['EmpCode']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['Salary']['EmpCode']; ?></td>
							<td><?php echo $post['Salary']['EmapName']; ?></td>
							<td><?php echo $post['Salary']['FatherName']; ?></td>
							<td><?php echo $post['Salary']['DOJ']; ?></td>
                                                        <td onclick="getsinglePdf('<?php echo $id; ?>','<?php echo $month; ?>','<?php echo $finance_year; ?>')"><a href="#">PDF</a></td>
							
						</tr>
                                                <?php $array[] = $id; endforeach; ?>
						<?php  ?>
					</tbody>
				</table>
                          </div>
						  <input type="hidden" name="UserIds" id="UserIds" value="<?php echo implode(',',$array)."&Month=$month&Year=$finance_year"; ?>" />
						  <input type="submit" name="DownloadPDF" value="DownloadPDF" />
						  </form>
<?php 

echo "<script>closeWin();</script>"
?>

