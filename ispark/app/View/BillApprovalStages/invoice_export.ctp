
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
                    <span>Invoice Export</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
            <div class = "form-horizontal">
		<div class="form-group">
                
                    <label class="col-sm-1 control-label">Company</label>
                    <div class="col-sm-3">
                    <?php	
                        echo $this->Form->input('company_name', array('label'=>false,'class'=>'form-control','options' => array('All'=>'All','Mas Callnet India Pvt Ltd'=>'Mas Callnet India Pvt Ltd','IDC'=>'IDC'),'empty' => 'Select Company','required'=>true));
                    ?>
                    </div>
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                    <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                    ?>
                    </div>        

                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('finance_year', array('label' => false,'options'=>$finance_yearNew,'empty' => 'Finance Year','class'=>'form-control','required'=>true)); ?>
                    </div>
                </div>
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-1">
                        <button type="button" value="show" onclick="getExport(this.value)" class="btn btn-primary btn-label-left">Show</button>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" value="export" onclick="getExport(this.value)" class="btn btn-primary btn-label-left" >Export</button>
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
                    <i class="fa fa-search"></i>
                    <span>Invoice Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
            <div id="data"></div>
            </div>
        </div>
    </div>
</div>