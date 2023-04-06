<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'M-yy'
    });
});
</script>

<script>
$(document).ready(function(){
    <?php if(isset($branchname)){ ?>
        getBranch('<?php echo $branchname;?>','<?php echo $CostCenter;?>');
    <?php }?>
});
    
function getBranch(BranchName,CostCenter){   
    $.post("<?php echo $this->webroot;?>AttendanceMisReports/getcostcenter",{BranchName:BranchName,CostCenter:CostCenter}, function(data){
        $("#CostCenter").html(data);
    });  
}

function getfnddetails(BranchName,CostCenter,Status){ 
    var StartDate="<?php echo $empmonth;?>";
    $.post("<?php echo $this->webroot;?>AttendanceMisReports/getfnddetails",{BranchName:BranchName,CostCenter:CostCenter,Status:Status,StartDate:StartDate}, function(data){
        $("#fnfdetails").html(data);
    });  
}

function fnfexport(BranchName,CostCenter,Status,StartDate){
    window.location="<?php echo $this->webroot;?>AttendanceMisReports/fnfexport?BranchName="+BranchName+"&CostCenter="+CostCenter+"&Status="+Status+"&StartDate="+StartDate;  
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
                    <span>ATTENDANCE ISSUE MIS REPORTS</span>
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
                <?php echo $this->Form->create('AttendanceMisReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">IssueType</label>
                    <div class="col-sm-2">
                        <select id="IssueType" name="IssueType" class="form-control" >
                            <option <?php if(isset($IssueType) && $IssueType =="new"){echo "selected='selected'";}?> value="new" >New</option>
                            <option <?php if(isset($IssueType) && $IssueType =="old"){echo "selected='selected'";}?> value="old" >Old</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control"  required="" >
                            <option value="">Select</option>
                        </select>
                    </div> 
                </div>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">ReportType</label>
                    <div class="col-sm-2">
                        <select id="ReportType" name="ReportType" class="form-control" >
                            <option <?php if(isset($ReportType) && $ReportType =="branch"){echo "selected='selected'";}?>  value="branch" >Branch</option>
                            <option <?php if(isset($ReportType) && $ReportType =="costcenter"){echo "selected='selected'";}?> value="costcenter" >Cost Center</option>
                            <option <?php if(isset($ReportType) && $ReportType =="data"){echo "selected='selected'";}?> value="data" >Employee</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
<!--                        <input type="text" name="StartDate" id="StartDate"   value="<?php echo isset($empmonth)?date('M-Y',strtotime($empmonth)):"";?>"  autocomplete="off"  required="" class="form-control datepik"  >-->
                    <select name="StartDate" id="StartDate" class="form-control" required="">
                            <option value="">Month</option>
                            <?php if(isset($empmonth))
                                    {?>
                                        <option value="<?php echo date('M-Y',strtotime($empmonth)); ?>"><?php echo date('M-Y',strtotime($empmonth)); ?></option>
                                    
                            
                            <?php
                                    }
                                    $curYear = date('Y');
                                    $TcurMonth = date('M');
                                    if($TcurMonth=='Jan')
                                    {?>
                                        <option value="Dec-<?php echo $curYear-1; ?>">Dec-<?php echo $curYear-1; ?></option>
                                    <?php }
                                    
                                    
                                    
                            ?>
                                         
                            <option value="Jan-<?php echo $curYear; ?>">Jan</option>
                            <option value="Feb-<?php echo $curYear; ?>">Feb</option>
                            <option value="Mar-<?php echo $curYear; ?>">Mar</option>
                            <option value="Apr-<?php echo $curYear; ?>">Apr</option>
                            <option value="May-<?php echo $curYear; ?>">May</option>
                            <option value="Jun-<?php echo $curYear; ?>">Jun</option>
                            <option value="Jul-<?php echo $curYear; ?>">Jul</option>
                            <option value="Aug-<?php echo $curYear; ?>">Aug</option>
                            <option value="Sep-<?php echo $curYear; ?>">Sep</option>
                            <option value="Oct-<?php echo $curYear; ?>">Oct</option>
                            <option value="Nov-<?php echo $curYear; ?>">Nov</option>
                            <option value="Dec-<?php echo $curYear; ?>">Dec</option>
                        </select>
                    </div>
         
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit"  value="Export" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit"  value="Search" class="btn pull-right btn-primary btn-new" >
                        
                    </div>
                    
                </div>
                
              
               
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  > 
                            <thead>
                                <tr>
                                    <th style="text-align: center;">SrNo</th>
                                    <?php foreach($headArr[$ReportType] as $hed){?>
                                        <th style="text-align: center;"><?php echo $hed;?></th>
                                    <?php }?>
                                </tr>
                            </thead>
                            
                            <tbody> 
                                <?php $n=1;foreach($data as $val){?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <?php foreach($headArr[$ReportType] as $hed){?>
                                        <td style="text-align: center;"><?php echo $val[$hed];?></td>
                                    <?php }?>
                                </tr>
                                <?php }?>
                                <?php if($ReportType !="data"){ ?>
                                <tr>
                                    <td style="text-align: center;font-weight: bold;">TOTAL</td>
                                    <?php foreach($headArr[$ReportType] as $hed){?>
                                        <td style="text-align: center;"><?php echo $total[$hed];?></td>
                                    <?php }?>
                                </tr>
                                <?php }?>
                            </tbody>  
                            
                        </table>
                        <?php }?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12" id="fnfdetails">
                        
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



