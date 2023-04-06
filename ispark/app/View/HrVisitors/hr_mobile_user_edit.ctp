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
                    <span>Add HR App User</span>
		</div>
            </div>
            <div class="box-content box-con form-horizontal">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create("HrRecruit",array('method'=>'Post')); ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">User Name</label>
                    <div class="col-sm-4">
                        <input type="text" name="user_name" id="user_name" value="<?php echo $hr_recruit_master['HRLogin']['User_Name']; ?>" class="form-control" required="" readonly="" />
                    </div>
                    </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-4">
                        <input type="text" name="password" id="password" value="<?php echo $hr_recruit_master['HRLogin']['User_Password']; ?>" class="form-control" required="" />
                    </div>
                </div>
                <div class="form-group">
                        <label class="col-sm-3 control-label">OP Branch</label>
                        <div class="col-sm-4">
                            <select name="OPBranch" id="OPBranch" class="form-control" required="">
                                <option value="">Select</option>
                                <?php $i = 0; foreach($branch_master as $branch) { ?>
                                <option value="<?php echo $branch; ?>" <?php if(strtolower($hr_recruit_master['HRLogin']['OPBranch'])==strtolower($branch)) { echo 'selected';} ?>><?php echo $branch; ?></option>
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
                <table class="table">
                    <tr>
                        <th>S.No.</th>
                        <th>Branch</th>
                    </tr>
                
                <?php $i = 0; foreach($branch_master as $branch) { ?>
                <tr>
                    <td><input type="checkbox" name="branch[]" value="<?php echo $branch; ?>" <?php if(in_array($branch,explode(",",$hr_recruit_master['HRLogin']['Branch']))) { echo 'checked'; } ?> /></td>
                    <td><?php echo $branch; ?></td>
                </tr>    
                <?php } ?>
                </table>            
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
