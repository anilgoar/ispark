<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#ResignationDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function goBack(){
    window.location="<?php echo $this->webroot;?>EmployeeDetails";  
}

function showdiv(id){
    $("#"+id).toggle();
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
                    <span>ACTIVE EMPLOYEE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('ActivateEmployees',array('action'=>'viewdetails','class'=>'form-horizontal')); ?>
                <input type="hidden" name="EJEID" value="<?php echo $data['Masjclrentry']['id'];?>" >
                <div class="form-group" style="position: relative;top:-10px;" >
                   
                    <div class="col-sm-12">
                        <table class = "table table-striped table-hover  responstable" style="margin-top:-5px;"  >     
                            <thead>
                                <tr>
                                    <th colspan="8" >Employee Details 
                                        <?php 
                                        if($data['Masjclrentry']['Status'] ==1){
                                            echo "(Status : Active)"; 
                                        }
                                        else{
                                            echo "(Status : Left [".date('d M Y',strtotime($data['Masjclrentry']['ResignationDate']))."])"; 
                                        }
                                        ?> 
                                    </th>
                                </tr>
                            </thead>
                            <tbody>         
                                <tr>
                                    <td style="text-align: left;"><strong>Employee Code</strong></td> <td><?php echo $data['Masjclrentry']['EmpCode'];?></td>
                                    <td><strong>Employee Name</strong></td> <td><?php echo $data['Masjclrentry']['EmpName'];?></td>
                                    <td><strong>Type</strong></td> <td><?php echo $data['Masjclrentry']['EmpType'];?></td>
                                    <td><strong>Father's Name</strong></td> <td><?php echo $data['Masjclrentry']['Father'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Gender</strong></td> <td><?php echo $data['Masjclrentry']['Gendar'];?></td>
                                    <td><strong>Date of Birth </strong></td> <td><?php echo date('d/M/Y',strtotime($data['Masjclrentry']['DOB']));?> <br/>(<?php echo $data['Masjclrentry']['Age'];?>)</td>
                                    <td><strong>Date of Join</strong></td> <td><?php echo date('d/M/Y',strtotime($data['Masjclrentry']['DOJ']));?></td>
                                    <td><strong>Qualification</strong></td> <td><?php echo $data['Masjclrentry']['Qualification'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Marrital Status</strong></td> <td><?php echo $data['Masjclrentry']['MaritalStatus'];?></td>
                                    <td><strong>Blood Group</strong></td> <td><?php echo $data['Masjclrentry']['BloodGruop'];?></td>
                                    <td><strong>Email ID</strong></td> <td><?php echo $data['Masjclrentry']['EmailId'];?></td>
                                    <td><strong>Designation</strong></td> <td><?php echo $data['Masjclrentry']['Desgination'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Department</strong></td> <td><?php echo $data['Masjclrentry']['Dept'];?></td>
                                    <td><strong>Stream</strong></td> <td><?php echo $data['Masjclrentry']['Stream'];?></td>
                                    <td><strong>Process</strong></td> <td><?php echo $data['Masjclrentry']['Process'];?></td>
                                    <td><strong>Profile</strong></td> <td><?php echo $data['Masjclrentry']['Profile'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Location</strong></td> <td><?php echo $data['Masjclrentry']['BranchName'];?></td>
                                    <td><strong>Documentation</strong></td> <td><?php echo $data['Masjclrentry']['documentDone'];?></td>
                                    <td><strong>Created Date</strong></td> <td><?php if($data['Masjclrentry']['EntryDate'] !=""){ echo date('d/m/Y H:i:s',strtotime($data['Masjclrentry']['EntryDate']));}?></td>
                                    <td><strong>Code Created on</strong></td> <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Biometric Code</strong></td> <td><?php echo $data['Masjclrentry']['BioCode'];?></td>
                                    <td><strong>Employee Location</strong></td> <td><?php echo $data['Masjclrentry']['EmpLocation'];?></td>
                                    <td><strong>Cost Center</strong></td> <td><?php echo $data['Masjclrentry']['CostCenter'];?></td>
                                    <td><strong></strong></td> <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;color:red;"><strong>Loan From</strong></td> <td></td>
                                    <td style="text-align: left;color:red;"><strong>Loan To</strong></td> <td></td>
                                    <td style="text-align: left;color:red;"><strong>Loan Amount</strong></td> <td></td>
                                    <td><strong></strong></td> <td></td>
                                </tr>                 
                            </tbody>   
                        </table>
                    </div>
                </div>
                
                <div class="form-group" >
                    <div class="col-sm-6">
                        <textarea id="Reason" name="Reason" class="form-control"  required="" ></textarea>
                    </div>
                </div>
                <div class="form-group" >
                    <div class="col-sm-6">
                       <input onclick='return window.location="<?php echo $this->webroot;?>ActivateEmployees"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                    </div>
                    
                </div>
                    
                <div class="form-group">  
                    <div class="col-sm-12"><?php echo $this->Session->flash();?> </div> 
                </div>
               
                <?php echo $this->Form->end(); ?>
                
                
            </div>
        </div>
    </div>	
</div>



