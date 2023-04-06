<script>
function downloadReport(){
    window.location="<?php echo $this->webroot;?>BmoldAttendanceApprovals/report";  
}
</script>

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
            <div class="box-header"  >
                <div class="box-name">
                    <span>OLD ATTENDANCE APPROVAL</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('BmoldAttendanceApprovals',array('action'=>'index','class'=>'form-horizontal')); ?>
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="text-align: center;width:80px;">Emp Code</th>
                            <th style="text-align: center;width:80px;">Bio Code</th>
                            <th>Emp Name</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;width:80px;">Attend Date</th>
                            <th>Reason</th>
                            <th style="text-align: center;width:100px;">Current Status</th>
                            <th style="text-align: center;width:100px;" >Expected Status</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($OdArr as $val){?>
                    <tr>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['OldAttendanceIssue']['Id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['BioCode'];?></td>
                        <td><?php echo $val['OldAttendanceIssue']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo date('d M y',strtotime($val['OldAttendanceIssue']['AttandDate'])) ;?></td>
                        <td><?php echo $val['OldAttendanceIssue']['Reason'];?></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['CurrentStatus'];?></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['ExpectedStatus'];?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="downloadReport();" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:10px;" >
                        <?php 
                        echo $this->Form->submit('Discard', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
                        echo $this->Form->submit('Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));
                        
                        ?>
                    </div>
                </div>
                
                <?php }else{?>
                <div class="form-group">
                    <div class="col-sm-10">
                       <span>Record Not Found.</span>
                       <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
                <?php }?>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



