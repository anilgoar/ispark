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
                    <span>EMPLOYEE STATUS REPORT</span>
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
                <?php echo $this->Form->create('EmployeeStatusReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="submit" name="Submit" value="View" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                
                <div class="form-group">  
                     <div class="col-sm-7">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:30px;" >SrNo</th>
                                    <th style="text-align: center;">Branch</th>
                                    <th style="text-align: center;">Cost Center</th>
                                    <th style="text-align: center;width:30px;">Field</th>
                                    <th style="text-align: center;width:30px;">InHouse</th>
                                    <th style="text-align: center;width:30px;">OnSite</th>
                                    <th style="text-align: center;width:100px;">Total Active</th>
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
                                <td style="text-align: center;" ><?php echo $i++;?></td>
                                <td style="text-align: center;"><?php echo $val['Masjclrentry']['BranchName'];?></td>
                                <td style="text-align: center;"><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                                <td style="text-align: center;"><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=Field"><?php echo $val[0]['EmpField'];?></a></td>
                                <td style="text-align: center;"><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=InHouse"><?php echo $val[0]['InHouse'];?></a></td>
                                <td style="text-align: center;"><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=OnSite"><?php echo $val[0]['OnSite'];?></a></td>
                                <td style="text-align: center;"><a href="<?php echo $this->webroot;?>EmployeeStatusReports/download?BranchName=<?php echo $val['Masjclrentry']['BranchName'];?>&CostCenter=<?php echo $val['Masjclrentry']['CostCenter'];?>&EmpLoc=ALL"><?php echo $val[0]['TotActive'];?></a></td>
                            </tr>
                            <?php }?>
                            <tr>
                                <td style="text-align: center;"><strong>Total</strong></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"><?php echo $Field;?></td>
                                <td style="text-align: center;"><?php echo $InHouse;?></td>
                                <td style="text-align: center;"><?php echo $OnSite;?></td>
                                <td style="text-align: center;"><?php echo $TotalActive;?></td>
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



