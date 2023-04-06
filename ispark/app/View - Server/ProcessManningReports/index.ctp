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
    $.post("<?php echo $this->webroot;?>ProcessManningReports/getcostcenter",{BranchName:BranchName,CostCenter:CostCenter}, function(data){
        $("#CostCenter").html(data);
    });  
}

function getfnddetails(BranchName,CostCenter,Status){ 
    var StartDate="<?php echo $empmonth;?>";
    $.post("<?php echo $this->webroot;?>ProcessManningReports/getfnddetails",{BranchName:BranchName,CostCenter:CostCenter,Status:Status,StartDate:StartDate}, function(data){
        $("#fnfdetails").html(data);
    });  
}

function fnfexport(BranchName,CostCenter,Status,StartDate){
    window.location="<?php echo $this->webroot;?>ProcessManningReports/fnfexport?BranchName="+BranchName+"&CostCenter="+CostCenter+"&Status="+Status+"&StartDate="+StartDate;  
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
                    <span>PROCESS WISE MANNING DETAILS</span>
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
                <?php echo $this->Form->create('ProcessManningReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit"  value="Export" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit"  value="Show" class="btn pull-right btn-primary btn-new" >
                    </div>
                </div>
  
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  > 
                            <thead>
                                <tr>
                                    <th style="text-align: center;">SrNo</th>
                                    <th style="text-align: center;">CostCenter</th>
                                    <th style="text-align: center;">ManDate</th>
                                    <th style="text-align: center;">Shri</th>
                                    <th style="text-align: center;">Attri</th>
                                    <th style="text-align: center;">Actual Attr</th>
                                    <th style="text-align: center;">Target</th>
                                    <th style="text-align: center;">ActualMP</th>
                                    <th style="text-align: center;">LastPresent</th>
                                </tr>
                            </thead>
                            
                            <tbody> 
                                <?php $n=1;foreach($data as $val){?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                                    <td style="text-align: center;"><?php echo $val['ManDate'];?></td>
                                    <td style="text-align: center;"><?php echo $val['Shri'];?></td>
                                    <td style="text-align: center;"><?php echo $val['Attri'];?></td>
                                    <td style="text-align: center;"><?php echo $val['ActualAttri'];?></td>
                                    <td style="text-align: center;"><?php echo $val['Target'];?></td>
                                    <td style="text-align: center;"><?php echo $val['ActualMp'];?></td>
                                    <td style="text-align: center;"><?php echo $val['LastPresent'];?></td>
                                </tr>
                                <?php }?>
                                
                                <tr>
                                    <td></td>
                                    <td style="text-align: center;font-weight: bold;">TOTAL</td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalManDate'];?></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalShri'];?></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalAttri'];?></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalActualAttri'];?></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalTarget'];?></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalActualMp'];?></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalLastPresent'];?></td>
                                </tr>
                               
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



