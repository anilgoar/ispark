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
                    <span>ONSITE ATTENDANCE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
                <?php echo $this->Form->create('ProcessAttendances',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >         
                    <thead>
                            <tr>                	
                                <th style="text-align: center;width:30px;" >SNo</th>
                                <th>Cost Center</th>
                                <th style="text-align: center;">No Of Employees</th>
                                <th style="text-align: center;width:100px;">Action</th>
                            </tr>
                    </thead>
                    <tbody>

                        <?php
                        $total=0;
                        $i=1; foreach ($fieldArr as $val){
                        $total=$total+$val['TotalEmp'];
                        $cosc = base64_encode($val['CostCenter']);

                        ?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $val['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                            <td style="text-align: center;"><a href="<?php $this->webroot;?>OnSiteAttendances/markfield?CSN=<?php echo $cosc;?>">Mark Attendance</a></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td style="text-align: center;"><?php echo $total;?></td>
                            <td></td>
                        </tr>
                    </tbody>           
                </table>
                    </div>
                </div>
                 <div class="form-group">
                     <div class="col-sm-6">
                         <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=Mg%3D%3D"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                     </div>
                 </div>
               <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



