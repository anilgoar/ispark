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

<div class="box-content">
    <h4 class="page-header">Add Client</h4>
    <?php foreach ($branch_master as $post){ ?>
    <?php $data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name']; ?>
    <?php } ?>
    <?php unset($Addbranch); ?>
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('Addclient',array('class'=>'form-horizontal','action'=>'add')); ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">Branch Name</label>
            <div class="col-sm-4">
                    <?php echo $this->Form->input('branch_name',array('label' => false,'class'=>'form-control','options'=>$data,'empty'=>'Select Branch')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Client Type</label>
            <div class="col-sm-4">
                    <?php echo $this->Form->input('client_type',array('label' => false,'class'=>'form-control','options'=>array('Client'=>'Client Name','Group'=>'Group'))); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><span>Client</span> Name</label>
            <div class="col-sm-4">
                    <?php echo $this->Form->input('client_name',array('label' => false,'class'=>'form-control','placeholder'=>'Client Name')); ?>
            </div>
        </div>


            <div class="clearfix"></div>
            <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary btn-label-left">
                    <span><i class="fa fa-clock-o"></i></span>
                            Submit
                    </button>
                </div>
            </div>
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-table"></i>
                    <span>Client Name</span>
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
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                <?php $case=array('primary',''); $i=0; ?>
                        <thead>
                            <tr class="active">
                                <td>Sr. No.</td>
                                <td>Client Name</td>
                                <td>Branch Name</td>
                                <td>Action</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                                <?php foreach ($client_master as $post): ?>
                            <tr class="<?php  echo $case[$i%4]; $i++;?>">
                                <td><?php echo $i; ?></td>
                                <td><code><?php echo $post['Addclient']['client_name']; ?></td>
                                <td><code><?php echo $post['Addclient']['branch_name']; ?></td>
                                <td><code><?php echo $this->Html->link('Edit',array('controller'=>'Addclients','action'=>'edit','?'=>array('id'=>$post['Addclient']['id']),'full_base' => true)); ?></code></td>
                                 <td><code><?php echo $post['Addclient']['client_status']=='1'?'Active':DeActive; ?></td>   
                            </tr>
                                <?php endforeach; ?>
                                <?php unset($Addclient); ?>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function () 
{
    $('#table_id').dataTable();
});
</script>