<?php
$Year = date('y');
    $NextYear = $Year+1;
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
                    <i class="fa fa-search"></i>
                    <span>Provision Edit Report</span>
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
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Branch','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">From Date</label>
                        <div class="col-sm-2">
					<?php	echo $this->Form->input('FromDate', array('label'=>false,'id'=>'FromDate','class'=>'form-control','placeholder'=>'From Date',
							'onClick'=>"displayDatePicker('data[FromDate]');",'required'=>true)); ?>
				</div>
                        <label class="col-sm-1 control-label">To Date</label>
                        
                            <div class="col-sm-2">
					<?php	echo $this->Form->input('ToDate', array('label'=>false,'id'=>'ToDate','class'=>'form-control','placeholder'=>'To Date',
							'onClick'=>"displayDatePicker('data[ToDate]');",'required'=>true)); ?>
				</div>
                        <div class="col-sm-2">
                            <input type="submit" name="Show" value="Show" onclick="showProvisionEditReports2(this.value)" class="btn btn-info" />
                            <input type="submit" name="Export" value="Export" onclick="showProvisionEditReports2(this.value)" class="btn btn-info" />
                        </div> 
                        
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        
                        
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
                    <i class="fa fa-search"></i>
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
                
            <div id="oo"></div>
		

		
					
		
            <div class="clearfix"></div>
            <div class="form-group">
                    
            </div>
            </div>
        </div>
    </div>
</div>

