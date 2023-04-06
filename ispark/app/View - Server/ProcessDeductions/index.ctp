<?php ?>

<script>
function getBranch(BranchName){
    $("#showDetails").submit();
}
function goBack(){
    window.location="<?php echo $_SERVER['HTTP_REFERER'];?>";  
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
                    <span>DEDUCTIONS FOR ( <?php echo date('M-Y', strtotime(date('Y-m')." -1 month"));?> )</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('ProcessDeductions',array('action'=>'index','class'=>'form-horizontal','id'=>'showDetails')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','onchange'=>'getBranch();','required'=>true)); ?>   
                    </div>
                    <div class="col-sm-1">
                        <input type='button' onclick="goBack()" class="btn btn-info pull-right  btn-new" value="Back">
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                
                <?php if(!empty($fieldArr)){ ?>
                <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" style="width:400px;" > 
                    <thead>
                        <tr><th colspan="4" style="text-align: left;" >Not Uploaded</th></tr>
                        <tr>                	
                        
                        <th>Cost Center</th>
                        <th style="width:100px;">Total Employees</th>
                        <th style="width:100px;text-align: center;" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fieldArr as $val){?>
                        <tr>
                            
                            <td><?php echo $val['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                            <td style="text-align: center;">Not Uploaded</td>
                        </tr>
                        <?php }?>
                    </tbody>           
                </table>
                <?php }?>
                
                <?php if(!empty($fieldArr1)){ ?>
                <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" style="width:400px;" > 
                    <thead>
                        <tr> 
                        <tr><th colspan="4" style="text-align: left;" >Uploaded</th></tr>
                        
                        <th>Cost Center</th>
                        <th style="width:100px;">Total Employees</th>
                        <th style="width:100px;text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($fieldArr1 as $val){
                        $cosc = base64_encode($val['CostCenter']);
                        $brnc = base64_encode($val['BranchName']);
                        ?>
                        <tr>
                            
                            <td><?php echo $val['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                            <td style="text-align: center;" ><a href="<?php $this->webroot;?>ProcessDeductions/process?CSN=<?php echo $cosc;?>&BRN=<?php echo $brnc;?>">View</a></td> 
                        </tr>
                        <?php }?>
                    </tbody>           
                </table>
                <?php }?>
                
                <?php if(!empty($fieldArr2)){ ?>
                <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" style="width:400px;" > 
                    <thead>
                        <tr>
                        <tr><th colspan="4" style="text-align: left;" >Processed</th></tr>                            
                      
                        <th>Cost Center</th>
                        <th style="width:100px;">Total Employees</th>
                        <th style="width:100px;text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($fieldArr2 as $val){
                        $cosc = base64_encode($val['CostCenter']);
                        ?>
                        <tr>
                           
                            <td ><?php echo $val['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                            <td style="text-align: center;">Processed</td>
                        </tr>
                        <?php }?>
                    </tbody>           
                </table>
                <?php }?>
            </div>
        </div>
    </div>	
</div>
