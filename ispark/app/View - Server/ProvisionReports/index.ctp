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
                    <span>Provision Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-group has-success has-feedback">
                    <div id="form-group">
                        <label class="col-sm-2 control-label">Branch Master</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Type</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('type', array('label'=>false,'class'=>'form-control','options' => array('Month'=>'Month Wise','Branch'=>'Branch Wise'),'empty' => 'Select Report','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-2">
                            <div id="monthID">
                                <?php	
                                    echo $this->Form->input('month', array('label'=>false,'class'=>'form-control','options' => $months,'empty' => 'Select','required'=>true));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Finance Year</label>
                    <div class="col-sm-3">
			<?php	echo $this->Form->input('finance_year', array('label'=>false,'id'=>'finance_year','class'=>'form-control','options' 
=> $finance_yearNew,'empty' => 'Select Year','required'=>true)); ?>
                    </div>
                    </div>
                    <div id="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                            <input type="submit" name="Show" value="Show" onclick="showProvisionReports2(this.value)" class="btn btn-info" />
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" name="Export" value="Export" onclick="showProvisionReports2(this.value)" class="btn btn-info" />
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

