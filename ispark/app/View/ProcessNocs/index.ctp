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
        getBranch('<?php echo $branchname;?>');
    <?php }?>
});
    
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>ProcessNocs/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
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
                    <span>LEFT EMPLOYEE DETAILS</span>
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
                <?php echo $this->Form->create('ProcessNocs',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
<!--                        <input type="text" name="StartDate" id="StartDate"  value="<?php //echo isset($empmonth)?date('M-Y',strtotime($empmonth)):"";?>"  autocomplete="off"  required="" class="form-control datepik"  >-->
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
                    
                </div>
                
                <div class="form-group"> 
                    <div class="col-sm-9">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit"  value="Search" class="btn pull-right btn-primary btn-new">
                    </div>
                </div> 
               
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:30px;">SNo</th>
                                    <th style="text-align: center;width:80px;">EmpCode</th>
                                    <th>EmpName</th>
                                    <th style="text-align: center;width:100px;">Branch</th>
                                    <th style="text-align: center;width:150px;">CostCenter</th>
                                    <th style="text-align: center;width:100px;">DOB</th>
                                    <th style="text-align: center;width:100px;">DOJ</th>
                                    <th style="text-align: center;width:100px;">LeftDate</th>
                                    <th>LeftRemark</th>
                                    <th style="text-align: center;width:120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php
                                $n=1; foreach ($data as $val){
                                    $EJEID = base64_encode($val['Masjclrentry']['id']);
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                                    <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                                    <td style="text-align: center;"><?php echo $val['Masjclrentry']['BranchName'];?></td>
                                    <td style="text-align: center;"><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                                    <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['DOB']));?></td>
                                    <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['DOJ']));?></td>
                                    <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['ResignationDate']));?></td>

                                    <td><?php echo $val['Masjclrentry']['LeftReason'];?></td>
                                    <td style="text-align: center;">
                                        <a href="<?php $this->webroot;?>ProcessNocs/viewdetails?EJEID=<?php echo $EJEID;?>">NOC By Cheque</a>
										<a href="<?php $this->webroot;?>ProcessNocs/onlinedetails?EJEID=<?php echo $EJEID;?>">NOC By Transfer</a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                        <?php }?>
                    </div>
                </div>
                
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



