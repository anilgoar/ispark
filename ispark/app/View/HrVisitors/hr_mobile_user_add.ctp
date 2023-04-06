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
                        <input type="text" name="user_name" id="user_name" class="form-control" required="" />
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-4">
                            <input type="text" name="password" id="password" class="form-control" required="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">OP Branch</label>
                        <div class="col-sm-4">
                            <select name="OPBranch" id="OPBranch" class="form-control" required="">
                                <option value="">Select</option>
                                <?php $i = 0; foreach($branch_master as $branch) { ?>
                                <option value="<?php echo $branch; ?>"><?php echo $branch; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                
                <div class="form-group">
                    <div class="col-sm-4 " id="trainerdata" >
                <table class="table table-striped table-hover  responstable">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Branch</th>
                    </tr>
                    </thead>
                <?php $i = 0; foreach($branch_master as $branch) { ?>
                <tr>
                    <td><input type="checkbox" name="branch[]" value="<?php echo $branch; ?>" /></td>
                    <td><?php echo $branch; ?></td>
                </tr>    
                <?php } ?>
                </table>  
                    </div>
                </div>    
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-10">
                        <button name="Add" class="btn btn-primary btn-new" value="Add" >Add</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="box-content box-con form-horizontal">
               
                <div class="form-group">
                    <div class="col-sm-10 " id="trainerdata" >
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Sr.No</th>
                                    <th>User Name</th>
                                    <th style="text-align: center;">Password</th>
                                    <th style="text-align: center;">Branch</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($hr_recruit_master as $hr_recruit){ ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $i++;?></td>
                                    <td><?php echo $hr_recruit['HRLogin']['User_Name']; ?></td>
                                    <td style="text-align: center;"><?php echo $hr_recruit['HRLogin']['User_Password']; ?></td>
                                    <td style="text-align: center;"><?php echo $hr_recruit['HRLogin']['Branch'];?></td>
                                    <td style="text-align: center;">
                                        <a href="<?php $this->webroot;?>hr_mobile_user_edit?id=<?php echo base64_encode($hr_recruit['HRLogin']['HR_Id'])?>">Edit</a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>                
            </div>
        </div>
    </div>	
</div>
