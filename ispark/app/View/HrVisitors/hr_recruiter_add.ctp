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
                    <span>Add HR Recruiter</span>
		</div>
            </div>
            <div class="box-content box-con form-horizontal">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create("HrRecruit",array('method'=>'Post')); ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Recruiter Name</label>
                    <div class="col-sm-4">
                        <select name="mas_name" id="mas_name" class="form-control" required="">
                            <option value="">Select</option>
                            <?php foreach($user_mas as $umId=>$um) { ?>
                            <option value="<?php echo $umId; ?>"><?php echo $um; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Branch</label>
                    <div class="col-sm-4">
                        <select name="branch_name" id="branch_name" class="form-control" required="">
                            <option value="">Select</option>
                            <?php foreach($branch_master as $branch) { ?>
                            <option value="<?php echo $branch; ?>"><?php echo $branch; ?></option>
                            <?php } ?>
                        </select>
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
                                    <th>Mas Code</th>
                                    <th style="text-align: center;">Name</th>
                                    <th style="text-align: center;">Branch</th>
                                    <th style="text-align: center;">Mobile No</th>
                                    <th style="text-align: center;">Official Email Id</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($hr_recruit_master as $hr_recruit){ ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $i++;?></td>
                                    <td><?php echo $hr_recruit['HRVisitorRecruiter']['mas_code']; ?></td>
                                    
                                    <td style="text-align: center;"><?php echo $hr_recruit['HRVisitorRecruiter']['mas_name']; ?></td>
                                    <td style="text-align: center;"><?php echo $hr_recruit['HRVisitorRecruiter']['branch']; ?></td>
                                    <td style="text-align: center;"><?php echo $hr_recruit['HRVisitorRecruiter']['mobile_no']?></td>
                                    <td style="text-align: center;"><?php echo $hr_recruit['HRVisitorRecruiter']['office_mail_id']?></td>
                                    <td style="text-align: center;">
                                        <a href="<?php $this->webroot;?>hr_recruiter_edit?id=<?php echo base64_encode($hr_recruit['HRVisitorRecruiter']['Id'])?>">Edit</a>
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
