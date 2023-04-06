<?php

?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            
            <div class="box-content box-con form-horizontal">
            <div class="box-header"  >
                <div class="box-name">
                    <span><b>Edit, Lock/Unlock,Approved/Disapproved Investment Declaration</b></span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
                <span><?php echo $this->Session->flash(); ?></span>
                <h3 align="center">EDIT INVESTMENTS DECLARATION FOR THE YEAR [2017-2018 ]</h3>
                
                
                    <div class="form-group">
                    <label class="col-sm-1 control-label">Emp Code</label>
                    <label class="col-sm-1 control-label">
                       <?php echo $data['0']['Masjclrentry']['EmpCode']; ?>
                    </label>
                    <label class="col-sm-2 control-label">Employee Name</label>
                    <label class="col-sm-2 control-label">
                        <?php echo $data['0']['Masjclrentry']['EmpName']; ?>
                    </label>
                    <label class="col-sm-1 control-label">DOJ</label>
                    <label class="col-sm-1 control-label">
                        <?php echo $data['0']['Masjclrentry']['DOJ']; ?>
                    </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">DOB</label>
                        <label class="col-sm-1 control-label">
                            <?php echo $data['0']['Masjclrentry']['DOB']; ?>
                        </label>
                        <label class="col-sm-2 control-label">PAN</label>
                        <label class="col-sm-2 control-label">
                            <?php echo $data['0']['Masjclrentry']['PanNo']; ?>
                        </label>
                        <label class="col-sm-1 control-label">Gender</label>
                        <label class="col-sm-1 control-label">
                            <?php echo $data['0']['Masjclrentry']['Gendar']; ?>
                        </label>
                    </div>
            </div>
            <div class="box-content box-con">
                    <div class="box-header"  >
                <div class="box-name">
                    <span><b>DECLARE YOUR INVESTMENT</b></span>
		</div>
             </div> 
                <?php echo $this->Form->create('BranchEmployeeMovements',array('class'=>'form-horizontal')); ?>
<table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
<thead>      
    <tr>
    <th>Particulars</th>
    <th>Amount</th>
    <th>Particulars</th>
    <th>Amount</th>
    </tr>
    </thead> 
     <tbody>  
         <tr>
             <td colspan="4">CHAPTER VI A</td>
         </tr>
         <tr>
             <td colspan="2">80C</td>
             <td colspan="2">Limit 150000</td>
         </tr>
         <tr>
             <td>PF(Deduction from Salary)</td>
             <td>0.00</td>
         
             <td>Housing loan (Principal)</td>
             <td><input type="text" name="HousingLoan" id="HousingLoan" value="" placeholder="0" /></td>
         </tr>
         <tr>
             <td>Notified Mutual Fund</td>
             <td><input type="text" name="NotifiedFund" id="NotifiedFund" value="" placeholder="0" /></td>
         
             <td>Public Provident Fund</td>
             <td><input type="text" name="ProvidentFund" id="ProvidentFund" value="" placeholder="0" /></td>
         </tr>
         <tr>
             <td>National Savings Certificates</td>
             <td><input type="text" name="NationalSavingCert" id="NationalSavingCert" value="" placeholder="0" /></td>
         
             <td>Unit Linked Insurance Plan</td>
             <td><input type="text" name="InsurancePlan" id="InsurancePlan" value="" placeholder="0" /></td>
         </tr>
         <tr>
             <td>Equity Linked Saving Scheme</td>
             <td><input type="text" name="Equity" id="Equity" value="" placeholder="0" /></td>
         
             <td>Life Insurance Premium (self / Spouse / Children)</td>
             <td><input type="text" name="LifeInsurancePremium" id="LifeInsurancePremium" value="" placeholder="0" /></td>
         </tr>
         <tr>
             <td>Mutual Fund Pension Plan</td>
             <td><input type="text" name="MutualFund" id="MutualFund" value="" placeholder="0" /></td>
         
             <td>Tution Fees</td>
             <td><input type="text" name="TutionFees" id="TutionFees" value="" placeholder="0" /></td>
         </tr>
         <tr>
             <td>Interest Accrued on Previous year NSC'S</td>
             <td><input type="text" name="PreNsc" id="PreNsc" value="" placeholder="0" /></td>
         
             <td>Scheduled Bank FDS(5 yr lock)</td>
             <td><input type="text" name="ScheduledBank" id="ScheduledBank" value="" placeholder="0" /></td>
         </tr>
         <tr>
             <td>Senior Citizens Savings Scheme</td>
             <td><input type="text" name="SavingScheme" id="SavingScheme" value="" placeholder="0" /></td>
         
             <td>Post Office Time Deposit</td>
             <td><input type="text" name="PostOfficeTimeDeposit" id="PostOfficeTimeDeposit" value="" placeholder="0" /></td>
         </tr>
         <tr>
             <td>Other</td>
             <td><input type="text" name="Other" id="Other" value="" placeholder="0" /></td>
             <td colspan="2"></td>
         </tr>
     </tbody>
     <tr><td colspan="4">&nbsp;</td></tr>
     <thead>
         <tr>
             <td><b>80CCC-Contribution to Pension Fund</b></td>
             <td><input type="text" name="CCC80" id="CCC80" value="" placeholder="0" /></td>
             <td colspan="2"><b>DEDUCTION UNDER SECTION 80CCE</b> (The new section 80CCE in the income Tax Act seeks to provide that the aggregate amount of deductions under section 80C and section 80CCC shall not exceed One Lakh Rupees</td>
         </tr>
     </thead>
     
     
     <tr><td colspan="4">&nbsp;</td></tr>
     
     <thead>
         <tr>
             <td colspan="2"><b>80D-Medical insurance</b></td>
             <td colspan="2"></td>
         </tr>
     </thead>
     <tbody>
        <tr>
            <td>
                <input type="radio" name="SelfMedical" id="SelfMedical1" value="Self / Spouse /Dependent Children" checked />Self / Spouse /Dependent Children
                <div align="right">15000.00</div>
            </td> 
            <td rowspan="2">
                <input type="text" name="SelfMedicalAmount" id="SelfMedicalAmount" value="" placeholder="0" />
            </td>
            <td>
                <input type="radio" name="ParentMedical" id="ParentMedical1" value="Parents(Not Sr Citizen)" />Parents(Not Sr Citizen)
                <div align="right">15000.00</div>
            </td>
            <td rowspan="2">
                <input type="text" name="ParentMedicalAmount" id="ParentMedicalAmount" value="" placeholder="0" />
            </td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="SelfMedical" id="SelfMedical2" value="Self [ Sr Citizen] / Dependent Children" />Self [ Sr Citizen] / Dependent Children
                <div align="right">20000.00</div>
            </td>
            <td>
                <input type="radio" name="ParentMedical" id="ParentMedical1" value="Parents(Not Sr Citizen)" />Parents(Not Sr Citizen)
                <div align="right">20000.00</div>
            </td>
        </tr>
        <tr>
            
        </tr>
        <tr>
            
        </tr>
     </tbody>
     <tr><td colspan="4">&nbsp;</td></tr>
     <thead>
         <tr>
             <td colspan="2"><b>80DD-Physically Handicapped-Dependent</b></td>
             <td colspan="2"><b>80U-Permanent Physical Disability</b></td>
         </tr>
     </thead>
     <tbody>
        <tr>
            <td>
                <input type="radio" name="PhysicallyHandicapped" id="PhysicallyHandicapped" value="Parents(Not Sr Citizen)" />Severity below 80%
                <div align="right">Limit 50000.00</div>
            </td>
            
            <td rowspan="2">
                <input type="text" name="PhysicallyHandiAmount" id="PhysicallyHandiAmount" value="" placeholder="0" />
            </td>
            <td>
                <input type="radio" name="PermanentPhysicallyHandicapped" id="PermanentPhysicallyHandicapped1" value="Parents(Not Sr Citizen)" />Severity below 80%
                <div align="right">Limit 50000.00</div>
            </td>
            <td rowspan="2">
                <input type="text" name="PermanentPhysicallyHandiAmount" id="PermanentPhysicallyHandiAmount" value="" placeholder="0" />
            </td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="PhysicallyHandicapped" id="PhysicallyHandicapped" value="Parents(Not Sr Citizen)" />Severity 80% and above
                <div align="right">Limit 75000.00</div>
            </td>
            
             <td>
                <input type="radio" name="PermanentPhysicallyHandicapped" id="PermanentPhysicallyHandicapped2" value="Parents(Not Sr Citizen)" />Severity 80% and above
                <div align="right">Limit 75000.00</div>
            </td>
        </tr>
            
        
     </tbody>
     <tr><td colspan="4">&nbsp;</td></tr>
     <thead>
         <tr>
             <td colspan="2"><b>80DDB-Medical Expenses</b></td>
            <td colspan="2"></td>
        </tr>
     </thead>
     
     <tbody>
        <tr>
            <td>
                <input type="radio" name="MedicalExpense" id="MedicalExpense1" value="Parents(Not Sr Citizen)" />Till 65 Years of Age
                <div align="right">Limit 50000.00</div>
            </td>
            <td rowspan="2">
                <input type="text" name="MedicalExpenseAmount" id="MedicalExpenseAmount" value="" placeholder="0" />
            </td>
            <td>80CCG(Rajiv Gandhi Equity Savings Scheme)</td>
             <td><input type="text" name="RajivGandhiEquity" id="RajivGandhiEquity" value="" placeholder="0" /></td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="MedicalExpense" id="MedicalExpense2" value="Parents(Not Sr Citizen)" />Above 65 Years of Age
                <div align="right">Limit 75000.00</div>
            </td>
            <td>80G Exemption</td>
             <td><input type="text" name="Exemption" id="RajivGandhiEquity" value="" placeholder="0" /></td>
        </tr>
     </tbody>
     <thead>
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr>
            <td colspan="2"><input type="radio" name="Section24" id="Section241" value="" /><b>SECTION 24</b>
            </td>
            <td <td colspan="2"></td>
        </tr>
        <tr>
            <td>
                Interest On Housing
                <div align="right">Limit 200000.00</div>
            </td>
            <td rowspan="2">
                <input type="text" name="InterestOnHousing" id="InterestOnHousing" value="" placeholder="0" />
            </td>
            <td colspan="2"></td>
        </tr>
     </thead>
     
     <thead>
         <tr>
             <td colspan="2">
                <input type="radio" name="Section24" id="Section242" value="Parents(Not Sr Citizen)" />Deduction Under SECTION 24 for Rental Income
            </td>
            <td colspan="2"></td>
        </tr>
     </thead>
     <tbody>
         <tr>
            <td>
                Income from House Property
                <div align="right">Limit 200000.00</div>
            </td>
            <td>
                <input type="text" name="InComeOnHousing" id="InComeOnHousing" value="" placeholder="0" />
            </td>
            <td>
                Interest on Borrowed Capital
                <div align="right">Limit 200000.00</div>
            </td>
            <td>
                <input type="text" name="InterestOnBorrowedCapital" id="InterestOnBorrowedCapital" value="" placeholder="0" />
            </td>
        </tr>
        
        <tr>
            <td>
                Interest on Pre-Construction Period
                <div align="right">Limit 200000.00</div>
            </td>
            <td>
                <input type="text" name="InterestOnPre_Construction_Period" id="InterestOnPre_Construction_Period" value="" placeholder="0" />
            </td>
            <td colspan="2"></td>
        </tr>
        
     </tbody>
     <tr><td colspan="4">&nbsp;</td></tr>
     <thead>
         <tr>
             <td colspan="2">
                 <b>INCOME FROM OTHER SOURCES. (Optional if you want to add taxable Income)</b>
            </td>
            <td colspan="2"></td>
        </tr>
     </thead>
     <tbody>
         <tr>
            <td>
                Bank Interest
                <div align="right">Limit 200000.00</div>
            </td>
            <td rowspan="3">
                <input type="text" name="BankInterest" id="InterestOnHousing" value="" placeholder="0" />
            </td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>
                Dividend
                <div align="right">Limit 200000.00</div>
            </td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>
                Any other income (attached details)
                <div align="right">Limit 200000.00</div>
            </td>
            <td colspan="2"><input type="submit" name="Submit" value="Submit"></td>
        </tr>
        
     </tbody>
    </table>
                 <?php 
                 echo $this->Form->input('EmpCode',array('type'=>'hidden','id'=>'EmpCode','value'=>$EmpCode));
                 echo $this->Form->end(); ?>

            </div>
            
            <div class="box-content box-con">
                <table class = "table table-striped table-hover  responstable" style="font-size: 13px;">
                    <tr><td colspan="8">Declare Your Rent</td></tr>
                    <tr>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td>Rent Per Month</td>
                        <td>Address</td>
                        <td>City</td>
                        <td>Landlord Name</td>
                        <td>Landlord Pan No</td>
                        <td>Action</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="FromDate" id="FromDate" value="" class="datepik" /></td>
                        <td><input type="text" name="ToDate" id="ToDate" value="" class="datepik" /></td>
                        <td><input type="text" name="RentPerMonth" id="RentPerMonth" value="" /></td>
                        <td><input type="text" name="Address" id="Address" value="" /></td>
                        <td><input type="text" name="City" id="City" value="" /></td>
                        <td><input type="text" name="LandLordName" id="LandLordName" value="" /></td>
                        <td><input type="text" name="LandLordPanNo" id="LandLordPanNo" value="" /></td>
                        <td><div class="btn btn-info" onclick="save_other()">Add</div></td>
                    </tr>
                        
                   
                </table>
                <div id="otherDisplay">
                    <?php
                    
                    echo '<table class = "table table-striped table-hover  responstable" style="font-size: 13px;"><thead><tr>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Rent Per Month</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Landlord Name</th>
                        <th>Landlord Pan No</th>
                        <th>Total</th>
                    </tr></thead>';
                
                foreach($dataArr as $data)
                {
                    echo "<tr>";
                        echo "<td>".$data['io']['FromDate'].'</td>';
                        echo "<td>".$data['io']['ToDate'].'</td>';
                        echo "<td>".$data['io']['RentPerMonth'].'</td>';
                        echo "<td>".$data['io']['Address'].'</td>';
                        echo "<td>".$data['io']['City'].'</td>';
                        echo "<td>".$data['io']['LandLordName'].'</td>';
                        echo "<td>".$data['io']['LandLordPanNo'].'</td>';
                        echo "<td>".($data['io']['RentPerMonth']*$data['0']['Total']).'</td>';
                    echo "</tr>";
                }
                echo "</table>";
                    
                    ?>
                </div>
            </div>
        </div>
    </div>
    
</div>

<script>
    function save_other()
    {
        var EmpCode = $('#EmpCode').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var RentPerMonth = $('#RentPerMonth').val();
        var Address = $('#Address').val();
        var City = $('#City').val();
        var LandLordName = $('#LandLordName').val();
        var LandLordPanNo = $('#LandLordPanNo').val();
        
        $.post("save_details",
            {
             EmpCode:EmpCode,
             FromDate:FromDate,
             ToDate:ToDate,
             RentPerMonth:RentPerMonth,
             Address:Address,
             City:City,
             LandLordName:LandLordName,
             LandLordPanNo:LandLordPanNo
            },
            function(data,status){
                   $('#otherDisplay').html(data);
                   $('#FromDate').val("");
                   $('#ToDate').val("");
                   $('#RentPerMonth').val("");
                   $('#Address').val("");
                   $('#City').val("");
                   $('#LandLordName').val("");
                   $('#LandLordPanNo').val("");
            }); 
    }
</script>  
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
    <script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'd-M-yy'
    });
});
</script>
    