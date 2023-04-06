
<?php ?>

<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
        <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>

<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Download Appointment Letter <?php echo $this->Session->flash(); ?></h4>
    <?php echo $this->Form->create('Jclr',array('class'=>'form-horizontal','action'=>'appointment_letter')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Employ Id</label>
        <div class="col-sm-3">
            <div class="input-group">
                <input type="text" name='empid' value="<?php echo isset($data['Jclr']['EmpCode'])?$data['Jclr']['EmpCode']:'' ?>" id="empid" class="form-control" required="" >
            </div>
            <input type='submit' class="btn btn-info" value="Submit">
        </div>
    </div>
    <?php echo $this->Form->end();?>   
    <div class="clearfix"></div>
    <?php if(!empty($data)){?>
    <div>
        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <tbody>
                <tr class="info" align="center">
                        <th>EmpCode</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Doj</th>
                        <th>Count</th>
                        <th colspan="2">Action</th>
                </tr>
                <tr>
                    <td><?php echo $data['Jclr']['EmpCode']; ?></td>
                    <td><?php echo $data['Jclr']['EmapName']; ?></td>
                    <td><?php echo $data['Jclr']['FatherName']; ?></td>
                    <td><?php echo $data['Jclr']['DOFJ']; ?></td>
                    <td><?php echo $data['Jclr']['DownloadCount']; ?></td>
                    <td>
                        <?php if($data['Jclr']['DownloadCount'] < 3){?>
                        <a target="_blank" href="<?php echo $this->webroot?>appointment/examples/appointment.php?Empcode=<?php echo $data['Jclr']['EmpCode'];?>">Download</a>
                        <?php }?>
                    </td>
                </tr>   
            </tbody>
        </table>
    </div>
    <?php }?>
</div>


  
