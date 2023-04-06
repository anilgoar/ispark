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
            <div class="box-header"  >
                <div class="box-name">
                    <span>EMPLOYEE LIVE STATUS REPORT</span>
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
                <?php echo $this->Form->create('EmployeeLiveStatusReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    <div class="col-sm-2">
                        <input type="submit" name="Submit" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="submit" name="Submit" value="View" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                
                <div class="form-group">  
                     <div class="col-sm-12">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: left;" >SrNo</th>
                                    <th>Branch</th>
                                    <th>Cost Center</th>
                                    <th>Field</th>
                                    <th>InHouse</th>
                                    <th>OnSite</th>
                                    <th>Total Active</th>
                                </tr>
                            </thead>
                            <tbody>         
                            <?php
                            $Field=0;
                            $InHouse=0;
                            $OnSite=0;
                            $TotalActive=0;
                            
                            $i=1; 
                            foreach ($data as $val){
                                $Field=$Field+$val[0]['EmpField'];
                                $InHouse=$InHouse+$val[0]['InHouse'];
                                $OnSite=$OnSite+$val[0]['OnSite'];
                                $TotalActive=$TotalActive+$val[0]['TotActive'];
                            ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $val['Masjclrentry']['BranchName'];?></td>
                                <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                                <td><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=Field"><?php echo $val[0]['EmpField'];?></a></td>
                                <td><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=InHouse"><?php echo $val[0]['InHouse'];?></a></td>
                                <td><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=OnSite"><?php echo $val[0]['OnSite'];?></a></td>
                                <td><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=ALL"><?php echo $val[0]['TotActive'];?></a></td>
                            </tr>
                            <?php }?>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td></td>
                                <td></td>
                                <td><?php echo $Field;?></td>
                                <td><?php echo $InHouse;?></td>
                                <td><?php echo $OnSite;?></td>
                                <td><?php echo $TotalActive;?></td>
                            </tr>
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



