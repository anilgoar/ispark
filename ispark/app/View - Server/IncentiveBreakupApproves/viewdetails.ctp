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
</script>
<script>
function searchEmployee(){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var SearchType=$("#SearchType").val();
    var SearchValue=$("#SearchValue").val();
    
    if(SearchType ===""){
        $("#SearchType").focus();
        $("#SearchType").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select search type.</span>");
        return false;
    }
    else if(SearchValue ===""){
        $("#SearchValue").focus();
        $("#SearchValue").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter search item.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>EmployeeDetails/show_employee",{BranchName:BranchName,SearchType:SearchType,SearchValue:$.trim(SearchValue)}, function(data) {
            if(data !=""){
                $("#divEmployee").html(data);
            }
            else{
                $("#divEmployee").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
            }
        });
    }
}

function goBack(){
    window.location="<?php echo $this->webroot;?>EmployeeDetails";  
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
                    <span>EMPLOYEE SUMMARY</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('EmployeeDetails',array('action'=>'viewdetails','class'=>'form-horizontal')); ?>
                <input type="hidden" name="EJEID" value="<?php echo $data['Masjclrentry']['id'];?>" >
                <div class="form-group" style="position: relative;top:-30px;" >
                    <div class="col-sm-12">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th colspan="8" >Employee Details 
                                        <?php 
                                        if($data['Masjclrentry']['Status'] ==1){
                                            echo "(Status : Active)"; 
                                        }
                                        else{
                                            echo "(Status : Left [".date('d M Y',strtotime($data['Masjclrentry']['DOL']))."])"; 
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
                                    <td><strong>Date of Birth </strong></td> <td><?php echo date('d/M/Y',strtotime($data['Masjclrentry']['DOB']));?></td>
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
                                    <td><strong>Stream</strong></td> <td></td>
                                    <td><strong>Process</strong></td> <td><?php echo $data['Masjclrentry']['CostCenter'];?></td>
                                    <td><strong>Profile</strong></td> <td><?php echo $data['Masjclrentry']['Profile'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Location</strong></td> <td><?php echo $data['Masjclrentry']['BranchName'];?></td>
                                    <td><strong>Documentation</strong></td> <td></td>
                                    <td><strong>Created Date</strong></td> <td><?php echo date('d/m/Y H:i:s',strtotime($data['Masjclrentry']['CreateDate']));?></td>
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
                
                <div class="form-group" style="position: relative;top:-60px;" >
                    <div class="col-sm-12">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th colspan="8" >Employee Address</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <tr>
                                    <td style="text-align: left;"><strong>Present Address</strong></td> <td><?php echo $data['Masjclrentry']['Adrress1'];?></td>
                                    <td><strong>Permanent Address</strong></td> <td><?php echo $data['Masjclrentry']['Adrress2'];?></td>
                                    <td><strong>City</strong></td> <td><?php echo $data['Masjclrentry']['City'];?></td>
                                    <td><strong>City</strong></td> <td><?php echo $data['Masjclrentry']['City1'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>State</strong></td> <td><?php echo $data['Masjclrentry']['State'];?></td>
                                    <td><strong>State</strong></td> <td><?php echo $data['Masjclrentry']['State1'];?></td>
                                    <td><strong>Mobile No</strong></td> <td><?php echo $data['Masjclrentry']['Mobile'];?></td>
                                    <td><strong>Mobile No</strong></td> <td><?php echo $data['Masjclrentry']['Mobile1'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Landline No</strong></td> <td></td>
                                    <td><strong>Landline No</strong></td> <td></td>
                                    <td><strong>Pin Code</strong></td> <td><?php echo $data['Masjclrentry']['PinCode'];?></td>
                                    <td><strong>Pin Code</strong></td> <td><?php echo $data['Masjclrentry']['PinCode1'];?></td>
                                </tr>                 
                            </tbody>   
                        </table>
                    </div>
                </div>
                
                <div class="form-group" style="position: relative;top:-90px;" >
                    <div class="col-sm-12">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th colspan="8" >Salary/Account Details</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <tr>
                                    <td style="text-align: left;"><strong>Account No.</strong></td> <td><?php echo $data1['MasJclrMaster']['AcNo'];?></td>
                                    <td><strong>Bank Name </strong></td> <td><?php echo $data1['MasJclrMaster']['Bank'];?></td>
                                    <td><strong>PassPort No.</strong></td> <td><?php echo $data['Masjclrentry']['PassportNo'];?></td>
                                    <td><strong>DL No.</strong></td> <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>EPF No</strong></td> <td><?php echo $data['Masjclrentry']['EPFNo'];?></td>
                                    <td><strong>ESI No</strong></td> <td><?php echo $data['Masjclrentry']['ESICNo'];?></td>
                                    <td><strong>PAN No</strong></td> <td><?php echo $data['Masjclrentry']['PanNo'];?></td>
                                    <td><strong>UAN</strong></td> <td></td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align:center;">CTC offered at the time of Joining: <?php echo $data['Masjclrentry']['CTC'];?> (<span style="font-style: italic;" >following are the salary backup</span>)</td>
                                </tr>  
                                <tr>
                                    <td style="text-align: left;"><strong>Basic Salary</strong></td> <td><?php echo $data1['MasJclrMaster']['Basic'];?></td>
                                    <td><strong>HRA</strong></td> <td><?php echo $data1['MasJclrMaster']['HRA'];?></td>
                                    <td><strong>Conv. Allow.</strong></td> <td><?php echo $data1['MasJclrMaster']['Conveyance'];?></td>
                                    <td><strong>DA</strong></td> <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Portfolio Allow.</strong></td> <td><?php echo $data1['MasJclrMaster']['Portfolio'];?></td>
                                    <td><strong>Special Allow.</strong></td> <td><?php echo $data1['MasJclrMaster']['Special'];?></td>
                                    <td><strong>Other Allow.</strong></td> <td><?php echo $data1['MasJclrMaster']['OtherAllow'];?></td>
                                    <td><strong>Bonus</strong></td> <td><?php echo $data1['MasJclrMaster']['Bonus'];?></td>
                                </tr> 
                                <tr>
                                    <td style="text-align: left;"><strong>Gross</strong></td> <td><?php echo $data1['MasJclrMaster']['Gross'];?></td>
                                    <td><strong>PLI</strong></td> <td><?php echo $data1['MasJclrMaster']['PLI'];?></td>
                                    <td><strong>Admin Charges</strong></td> <td><?php echo $data1['MasJclrMaster']['Admin'];?></td>
                                    <td><strong>ESIC</strong></td> <td><?php echo $data1['MasJclrMaster']['ESIC'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Employer ESIC</strong></td> <td><?php echo $data1['MasJclrMaster']['ESICCO'];?></td>
                                    <td><strong>EPF </strong></td> <td><?php echo $data1['MasJclrMaster']['EPF'];?></td>
                                    <td><strong>Employer EPF</strong></td> <td><?php echo $data1['MasJclrMaster']['EPFCO'];?></td>
                                    <td><strong>In Hand</strong></td> <td><?php echo $data1['MasJclrMaster']['Inhand'];?></td>
                                </tr> 
                            </tbody>   
                        </table>
                    </div>
                </div>
                
                <?php if($data['Masjclrentry']['Status'] ==1){ ?>
                <div class="box" style="position: relative;top:-110px;">
                    <div class="box-header"  >
                        <div class="box-name">
                            <span>Left Employee</span>
                        </div>
                        <div class="box-icons">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            <a class="expand-link"><i class="fa fa-expand"></i></a>
                            <a class="close-link"><i class="fa fa-times"></i></a>
                        </div>
                        <div class="no-move"></div>
                    </div>
                    <div class="box-content box-con">
                        <div class="form-group">  
                            <label class="col-sm-2 control-label">Resignation Date</label>
                            <div class="col-sm-2">
                                <input type="text" id="ResignationDate" name="ResignationDate" autocomplete="off" class="form-control"  required="" >
                            </div>
                            
                            <label class="col-sm-2 control-label">Authentication Code</label>
                            <div class="col-sm-2">
                                <input type="text" id="AuthenticationCode" name="AuthenticationCode" autocomplete="off" class="form-control"  required="" >
                            </div>
                            
                            <label class="col-sm-1 control-label">Reason</label>
                            <div class="col-sm-2">
                                <select id="Reason" name="Reason" class="form-control"  required="" >
                                    <option value="">Select</option>
                                    <option value="Documents not available">Documents not available</option>
                                    <option value="Further Studies">Further Studies</option>
                                    <option value="Dual Employment">Dual Employment</option>
                                    <option value="Pick and Speak Rejection">Pick and Speak Rejection</option>
                                    <option value="Shift Problem">Shift Problem</option>
                                    <option value="Supervisor issue">Supervisor issue</option>
                                    <option value="Absconded">Absconded</option>
                                    <option value="Better Opportunity">Better Opportunity</option>
                                    <option value="Family Problem">Family Problem</option>
                                    <option value="Health problem">Health problem</option>
                                    <option value="Salary Problem">Salary Problem</option>
                                    <option value="Employee Movement from Off-roll to On-roll">Employee Movement from Off-roll to On-roll</option>
                                    <option value="Performance Issue">Performance Issue</option>
                                    <option value="Asked To Leave">Asked To Leave</option>
                                    <option value="Disciplinary Problem">Disciplinary Problem</option>
                                    <option value="Terminated">Terminated</option>
                                    <option value="Behavioral Issue">Behavioral Issue</option>
                                    <option value="Process Closed">Process Closed</option>
                                    <option value="Employee Expired">Employee Expired</option>
                                </select  >
                            </div>
                            
                            <div class="col-sm-1">
                                <input type="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                            </div>
                        </div>
                        
                        <div class="form-group">  
                            <div class="col-sm-12"><?php echo $this->Session->flash();?> </div> 
                        </div>
                    </div>    
                </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-sm-12"><input type='button' style="position: relative;top:-100px;" onclick="goBack();" class="btn btn-primary pull-right btn-new"  value="Go Back"></div>
                </div>
                <?php echo $this->Form->end(); ?>
                
                
            </div>
        </div>
    </div>	
</div>



