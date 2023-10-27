<script>
    function get_report_bill_upd()
    {
        var branch = $('#branch').val();
        var FinanceYear = $('#FinanceYear').val();
        var FinanceMonth = $('#FinanceMonth').val();
        
        $.post("get_report_bill_upd",
            {
             FinanceYear:FinanceYear,
             FinanceMonth: FinanceMonth,
             branch:branch
            },
            function(data,status){
                $('#report_det').html(data);
            }
               );

               return false;
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
})();

    
    
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
<?php echo $this->Form->create('BillGenerations',array('class'=>'form-horizontal')); ?>
<div class="row">
    <div class="col-xs-12">
        
<div class="box">
    <div class="box-header">
        <div class="box-name">
            <i class="fa fa-edit"></i>
            <span>P&L Details Update Report</span>
        </div>
				
        <div class="no-move"></div>
    </div>
<?php //print_r($branch_master); ?>

<br/>
<div class="form-horizontal">
<?php echo $this->Session->flash(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><b style="font-size:14px">Branch</b></label>	
                    <div class="col-sm-4">
                        <?php	echo $this->Form->input('branch', array('label'=>false,'class'=>'form-control',
                        'options'=>$branch_master,'id'=>'branch','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><b style="font-size:14px">Finance Year</b></label>	
                    <div class="col-sm-2">
                        <?php	echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control',
                        'options'=>$financeYearArr,'id'=>'FinanceYear','value'=>$FinanceYearLogin,'required'=>true)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><b style="font-size:14px">Finance Month</b></label>	
                    <div class="col-sm-2">
                        <?php	echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','id'=>'FinanceMonth','value'=>$mnt,'empty'=>'Select',
                        'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),'required'=>true)); ?>
                    </div>
                </div>				
                <div class="form-group">
                    <label class="col-sm-2 control-label"><b style="font-size:14px"></b></label>	
                    <div class="col-sm-3">
                        <button type="submit" name="button" value="Save" onclick="return get_report_bill_upd('show')" class="btn btn-primary">Show</button>
                        <button type="submit" name="button" value="Save" onclick=" return tableToExcel('bill_table', 'bill_detail_upd')" class="btn btn-primary">Export</button>
                        <a href="/ispark/Menuisps/sub?AX=MTM1" class="btn btn-primary btn-label-left">Back</a> 
                    </div>
                </div>
                
    </div>
</div>
        
    </div>
</div
<div class="row">
    <div class="col-xs-12">
    <div class="box">
        <div class="box-content no-padding">
            
            <div id="report_det"></div>
            
            <br/>
            <br/>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>