<?php echo $this->Form->create('Provision',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
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
                    <span>Provision</span>
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
                
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Upload Provision</label>
                    <div class="col-sm-6">
                    <?php	
                    echo $this->Form->input('file', array('label'=>false,'type' => 'file','required'=>true));
                    ?>
                    </div>
                </div>
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                        <button type="Upload" class="btn btn-primary btn-label-left">
                            Upload
			</button>
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($wrongData)) { ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-search"></i>
                    <span>Data Not Matched</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <tr>
                    <th>Cost Center</th>
                    <th>Finance Year</th>
                    <th>Month Amount</th>
                    <th>Amount</th>
                    <th>Reasion</th>
                </tr>
                <?php 
                foreach($wrongData as $wd):
                    echo "<tr>";
                            echo "<td>".$wd['TMPProvision']['cost_center']."</td>";
                            echo "<td>".$wd['TMPProvision']['finance_year']."</td>";
                            echo "<td>".$wd['TMPProvision']['month']."</td>";
                            echo "<td>".$wd['TMPProvision']['provision']."</td>";
                            echo "<td>".$wd['TMPProvision']['Reasion']."</td>";
                    echo "</tr>";
                endforeach;
                ?>
                </table>
		<div class="clearfix"></div>
		
            </div>
        </div>
    </div>
</div>
<?php } echo $this->Form->end(); ?>