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
                    <span>DEDUCTION UPLOAD </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <div class="row">
    <div class="col-xs-12 col-sm-5">
                <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" > 
                    <thead>
                        <tr>                	
                       
                        <th>Cost Center</th>
                        <th style="text-align: center;width:110px;" >Total Employees</th>
                        <th style="text-align: center;width:50px;" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($fieldArr as $val){
                        $cosc = base64_encode($val['CostCenter']);
                        ?>
                        <tr>
                           
                            <td><?php echo $val['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                            <td style="text-align: center;">
                                <?php 
                                if($val['Status'] =="Uploaded"){ 
                                    echo "Uploaded";
                                }
                                else if($val['Status'] =="Processed"){ 
                                    echo "Processed";
                                }
                                else{
                                ?>
                                <a href="<?php $this->webroot;?>UploadDeductions/upload?CSN=<?php echo $cosc;?>">Upload</a>
                                <?php }?>
                            </td> 
                        </tr>
                        <?php }?>
                    </tbody>           
                </table>
        </div>
                    
                     
                </div>
                
                <div class="row">
                     <div class="col-xs-12 col-sm-5">
                <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTM%3D"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                     </div>
                     </div>
                
            </div>
        </div>
    </div>	
</div>
