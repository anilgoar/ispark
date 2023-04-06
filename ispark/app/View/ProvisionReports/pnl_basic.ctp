<?php

    $months = array("Jan"=>"Jan","Feb"=>"Feb","Mar"=>"Mar",
        "Apr"=>"Apr","May"=>"May","Jun"=>"Jun","Jul"=>"Jul",
        "Aug"=>"Aug","Sep"=>"Sep","Oct"=>"Oct","Nov"=>"Nov","Dec"=>"Dec");
?>
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

                    <span>BUDGET REPORT</span>
		</div>
		
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-group has-success has-feedback">
                    <div id="form-group">
                        <label class="col-sm-1 control-label">Branch </label>
                        <div class="col-sm-3">
                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select','required'=>true));
                        ?>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
			<?php	echo $this->Form->input('finance_year', array('label'=>false,'id'=>'finance_year','class'=>'form-control','options' 
=> $finance_yearNew,'empty' => 'Select','required'=>true)); ?>
                    </div>
                    </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-1">
                            <div id="monthID">
                                <?php	
                                    echo $this->Form->input('month', array('label'=>false,'class'=>'form-control','options' => $months,'empty' => 'Select','required'=>true));
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" name="Show" value="Show" onclick="show_pnl_basic(this.value)" class="btn btn-primary" />
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" name="Export" value="Export" onclick="show_pnl_basic(this.value)" class="btn btn-primary" />
                        </div>
                        <div class="col-sm-1">
                           <a href="/ispark/FinanceReports" class="btn btn-primary btn-label-left">Back</a> 
                        </div>
                    </div>
                    
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
		
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
            <div id="details_id"></div>
		

		
					
		
            <div class="clearfix"></div>
            <div class="form-group">
                    
            </div>
            </div>
        </div>
    </div>
</div>

<script>
    function show_pnl_basic(value)
{
    var branch = document.getElementById("branch_name").value;
    var month = document.getElementById("month").value;
    var finance_year = document.getElementById("finance_year").value;
    
    var url = 'branch_name='+branch+"&finance_year="+finance_year+"&month="+month+"&view="+value;
    //alert(url);
    var xmlHttpReq = false;
    if (window.XMLHttpRequest)
    {
        xmlHttpReq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttpReq.onreadystatechange = function()
    {
        if (xmlHttpReq.readyState == 4)
        {     //alert(xmlHttpReq.responseText);
            document.getElementById("details_id").readOnly= true;
            document.getElementById('details_id').innerHTML = xmlHttpReq.responseText;
	}
    }
    
    if(value=='Export') window.location.href ='export_pnl_basic?'+url;
    else
    xmlHttpReq.open('POST','export_pnl_basic?'+url,true);
    
    xmlHttpReq.send(null);
}
</script>