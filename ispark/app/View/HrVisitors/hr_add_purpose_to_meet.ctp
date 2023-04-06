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
                    <span>Add Purpose to meet</span>
		</div>
            </div>
            <div class="box-content box-con form-horizontal">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create("HrRecruit",array('method'=>'Post')); ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Purpose of Meet</label>
                    <div class="col-sm-4">
                        <input type="text" name="meet_purpose" id="meet_purpose" class="form-control" required="" />
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
                    <div class="col-sm-4 " id="trainerdata" >
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Sr.No</th>
                                    <th>Purpose of Meet</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($hr_purpose_master as $purpose){ ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $i++;?></td>
                                    <td><?php echo $purpose['HRMeetPurpose']['meet_purpose']; ?></td>
                                    <td style="text-align: center;">
                                        <a href="<?php $this->webroot;?>hr_delete_purpose_to_meet?id=<?php echo base64_encode($purpose['HRMeetPurpose']['Id'])?>">Delete</a>
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
