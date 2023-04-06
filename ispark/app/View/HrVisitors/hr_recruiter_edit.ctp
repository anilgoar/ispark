<?php ?>

<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>Edit HR Recruiter</span>
		</div>
            </div>
            <div class="box-content box-con form-horizontal">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create("HrRecruit",array('method'=>'Post')); ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Recruiter Name</label>
                    <div class="col-sm-4">
                        <select name="mas_name" id="mas_name" class="form-control" required="">
                            <?php foreach($hr_recruit_master as $hr_mas) { ?>
                            <option value="<?php echo $hr_mas['Id']; ?>"><?php echo $hr_mas['mas_name'].' - '.$hr_mas['mas_code']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Branch</label>
                    <div class="col-sm-4">
                        <select name="branch_name" id="branch_name" class="form-control" required="">
                            <option value="<?php echo $hr_mas['branch'];?>"><?php echo $hr_mas['branch'];?></option>
                            <?php foreach($branch_master as $branch) { ?>
                            <option value="<?php echo $branch; ?>"><?php echo $branch; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-4">
                        <select name="active_status" id="active_status" class="form-control" required="">
                            <option value="1">Active</option>
                            <option value="0">De-Active</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-10">
                        <button name="Update" class="btn btn-primary btn-new" value="Update" >Update</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            
        </div>
    </div>	
</div>
