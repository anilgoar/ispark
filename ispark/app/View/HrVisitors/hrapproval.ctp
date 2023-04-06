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
                    <span>HR APPROVAL</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con form-horizontal">  
                <span><?php echo $this->Session->flash(); ?></span>
                <div class="form-group">
                    <div class="col-sm-12 " id="trainerdata" >
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Name</th>
                                    <th>Job Position</th>
                                    <th>Mobile No</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($data as $rowArr){$row=$rowArr['InterviewMaster'];?>
                                <tr>
                                    <td><?php echo $i++;?></td>
                                    <td><a href="<?php echo $this->webroot.'HrVisitors/hrupdate?url='.base64_encode($row['Interview_Id']);?>"><?php echo $row['Name']?></a></td>
                                    <td><?php echo $row['Job_Position']?></td>
                                    <td><?php echo $row['Mobile_No']?></td>
                                    <td><?php echo $row['Create_Date']?></td>
                                    <td style="text-align: center;">
                                        <a target="_blank" href="<?php echo 'http://mascallnetnorth.in/hrvisitors/viewempdetails.php?url='.base64_encode($row['Interview_Id']);?>"><i class="fa fa-pencil" style="font-size:20px;cursor: pointer;text-align: center;" ></i></a>
                                        <a href="<?php $this->webroot;?>deletehremp?id=<?php echo base64_encode($row['Interview_Id'])?>" onclick="return confirm('Are you sure you want to delete this record?');"><span class='icon' ><i class="material-icons" style="font-size:20px;cursor: pointer;text-align: center;" >delete</i></span></a>
                                    </td>
                                </tr>
                                <?php }?>
                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTI2"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>
