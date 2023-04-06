<?php ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right">
            <a href="#"><i class="fa fa-google-plus"></i></a>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-content">
                <h4 class="page-header">Update Ticket Status</h4>
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('Issues',array('action'=>'update_it_ticket_status','enctype'=>'multipart/form-data')); ?>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Ticket No</label>
                    <div class="col-sm-4">
                        <input type="text" name="TicketNo" id="TicketNo" class="form-control" required="" readonly="" value="<?php echo isset($_REQUEST['TN'])&& $_REQUEST['TN'] !=''?$_REQUEST['TN']:'';?>" >
                    </div>
                </div>
                
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Ticket Status</label>
                    <div class="col-sm-4">
                        <select name="TicketStatus" id="TicketStatus" class="form-control" required="" >
                            <option value="">Select Status</option>
                            <option value="1">Complete</option>
                            <option value="0">Pending</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Ticket Remarks</label>
                    <div class="col-sm-4">
                        <textarea name="TicketRemarks" id="TicketRemarks" class="form-control" required="" ></textarea>
                    </div> 
                </div>
                
                <div class="form-group has-success has-feedback" >
                    <div class="col-sm-12">
                        <a href="<?php echo $this->webroot?>Issues/view_user_issue" class="btn btn-info pull-right" style="margin-left: 5px;" >Back</a>
                        <input type="submit" value="submit" class="btn btn-info pull-right" style="margin-left: 5px;"  />
                    </div>
                    
                </div>
                <br/><br/><br/><br/><br/><br/>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
	
 