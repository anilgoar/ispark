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
                    <span>EMPLOYEE DETAILS</span>
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
                <?php echo $this->Form->create('ChangeDojs',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">SearchType</label>
                    <div class="col-sm-2">
                        <select id="SearchType" name="SearchType" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option value="EmpName">Name</option>
                            <option value="EmpCode">Employee Code</option>
                            <option value="BioCode">Biometric Code</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-3">
                        <input type="text" id="SearchValue" name="SearchValue" autocomplete="off" placeholder="Search" class="form-control"  required="" >
                    </div>
                    
                    <div class="col-sm-2">
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
                                    <th>SNo</th>
                                    <th>EmpCode</th>
                                    <th>BioCode</th>
                                    <th>EmpName</th>
                                    <th>F/H Name</th>
                                    <th>DOJ</th>
                                    <th>DOB</th>
                                    <th>Department</th>
                                    <th>CostCenter</th>
                                    <th>EmpLocation</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php
                                $n=1; foreach ($data as $val){
                                    $EJEID = base64_encode($val['Masjclrentry']['id']);
                                ?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                                    <td><?php echo $val['Masjclrentry']['BioCode'];?></td>
                                    <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                                    <td>
                                        <?php 
                                        if($val['Masjclrentry']['ParentType'] =="Father"){ 
                                            echo $val['Masjclrentry']['Father'];
                                        }
                                        else if($val['Masjclrentry']['ParentType'] =="Husband"){ 
                                            echo $val['Masjclrentry']['Husband'];
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo date('d M Y',strtotime($val['Masjclrentry']['DOJ']));?></td>
                                    <td><?php echo date('d M Y',strtotime($val['Masjclrentry']['DOB']));?></td>
                                    <td><?php echo $val['Masjclrentry']['Dept'];?></td>
                                    <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                                    <td><?php echo $val['Masjclrentry']['EmpLocation'];?></td>
                                    <?php 
                                    if($val['Masjclrentry']['Status'] =="1"){echo "<td style='color:green;'>Active</td>";}else{echo "<td style='color:red;'>Left</td>";}
                                    ?>
                                    <td>
                                        <?php if($val['Masjclrentry']['Status'] =="1"){ ?>
                                            <a href="<?php $this->webroot;?>ChangeDojs/newjclr?id=<?php echo $val['Masjclrentry']['id'];?>">Edit</a>
                                        <?php }else{?>
                                            <span>Edit</span>
                                        <?php }?>
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



