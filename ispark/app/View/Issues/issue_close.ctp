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
                <h4 class="page-header">Issue Close</h4>
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('Issues',array('action'=>'user_ticket_close','enctype'=>'multipart/form-data')); ?>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Branch Name</label>
                    <div class="col-sm-4">
                        <input type="text" name="BranchName" id="BranchName" class="form-control" required="" readonly="" value="<?php echo $this->Session->read('branch_name');?>" >
                    </div>

                    <label class="col-sm-2 control-label">Ticket Details</label>
                    <div class="col-sm-4">
                        <select name="TicketDetails" id="TicketDetails" class="form-control" required="" >
                            <option value="">Select Ticket</option>
                            <?php foreach($TicketArr as $val){?>
                            <option value="<?php echo $val['t1']['TicketNo'];?>"><?php echo $val['t1']['TicketNo'];?> => <?php echo $val['t1']['ticket_desc'];?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Ticket Status</label>
                    <div class="col-sm-4">
                        <select name="TicketStatus" id="TicketStatus" class="form-control" required="" >
                            <option value="">Select Status</option>
                            <option value="1">Closed</option>
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
                        <input type="submit" value="submit" class="btn btn-info pull-right"  /> 
                    </div>
                </div>
                <br/><br/><br/><br/><br/><br/>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
	
 