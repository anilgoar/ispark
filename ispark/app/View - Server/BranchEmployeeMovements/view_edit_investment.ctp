<?php
 $dat=date("Y-04-01");
$date1=date_create($dat);
    $date2=date_create($data['0']['Masjclrentry']['DOJ']);
    $diff=date_diff($date2,$date1);
    $dif=$diff->format("%R%a");
    if($dif < 0){
        $datedoj =date_format($date2,'j-M-Y');
         $class='datepikddd';
         $read='readonly';
    }
    else{
       $datedoj='';
       $class='datepik';
    }
    
?>
<script>
//    $(document).on("change", ".qty1", function() {
//    var sum = 0;
//    $(".qty1").each(function(){
//        var tal = parseInt($(this).val()) || 0;
//      /// alert(tal);
//        sum += tal;
//    });
//    $("#total").val(sum);
//    if(sum > 150000)
//    { var st = "Total Is Greater then 150000.";
//       document.getElementById("bl").innerHTML = st;
//        $("#total").focus();
//        setInterval(blinker1, 2000);
//        return false;
//    }
//    return true;
//});
//    
    function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        
            
        return true;
        }
	
function blinker() {
    $('.blink').fadeOut(1000).fadeIn(1000);
    
}
 function blinker1() {
   
    $('#total').fadeOut(1000).fadeIn(1000);
}
setInterval(blinker, 2000);
    
</script>
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
                    <span style="text-align:center"><b><div style="text-align:center">Edit, Lock/Unlock,Approved/Disapproved Investment Declaration</div></b></span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
                <span><?php echo $this->Session->flash(); ?></span>
                <h3 align="center">EDIT INVESTMENTS DECLARATION FOR THE YEAR [<?php echo $investYear; ?> ]</h3>
                
                
                    <div class="form-group">
                    <label class="col-sm-2 control-label">Emp Code</label>
                    <label class="col-sm-2 control-label">
                       <?php echo $data['0']['Masjclrentry']['EmpCode']; ?>
                    </label>
                    <label class="col-sm-2 control-label">Employee Name</label>
                    <label class="col-sm-2 control-label">
                        <?php echo $data['0']['Masjclrentry']['EmpName']; ?>
                    </label>
                    <label class="col-sm-2 control-label">DOJ</label>
                    <label class="col-sm-2 control-label">
                        <?php echo $data['0']['Masjclrentry']['DOJ']; ?>
                    </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">DOB</label>
                        <label class="col-sm-2 control-label">
                            <?php echo $data['0']['Masjclrentry']['DOB']; ?>
                        </label>
                        <label class="col-sm-2 control-label">PAN</label>
                        <label class="col-sm-2 control-label">
                            <?php echo $data['0']['Masjclrentry']['PanNo']; ?>
                        </label>
                        <label class="col-sm-2 control-label">Gender</label>
                        <label class="col-sm-2 control-label">
                            <?php echo $data['0']['Masjclrentry']['Gendar']; ?>
                        </label>
                    </div>
            </div>
            <div class="box-content box-con">
                    <div class="box-header"  >
                <div class="box-name">
                    <span><b><div style="text-align:center">DECLARE YOUR INVESTMENT</div></b></span>
		</div>
             </div> 
                <?php echo $this->Form->create('BranchEmployeeMovements',array('class'=>'form-horizontal')); ?>
<table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
<thead>      
    <tr>
    <th>Particulars</th>
    <th>Limit</th>
    
    <th>Amount</th>
    </tr>
    </thead> 
     <tbody>  
         <tr>
             <td colspan="3">CHAPTER VI A</td>
         </tr>
         <tr>
             <th colspan="3">80C</th>
             
         </tr>
         <tr>
             <td>PF(Deduction from Salary)</td>
             <td rowspan="15">150000</td>
             <td><?php if($data['0']['Masjclrentry']['EPF']==0){echo '00.0';} else{ echo $data['0']['Masjclrentry']['EPF'];} ?>
                 <input type="hidden" name="EPF" class="qty1" id="EPF" value="<?php echo $data['0']['Masjclrentry']['EPF'] ?>" placeholder="0" />
             <input type="hidden" name="investYear"  id="investYear" value="<?php echo $investYear ?>" placeholder="0" />
<input type="hidden" name="AStatus"  id="AStatus" value="<?php echo $Id ?>" placeholder="0" /></td>
          </tr>
         <tr>
             <td>Housing loan (Principal)</td>
             <td><input type="text" class="qty1" name="HousingLoan" id="HousingLoan" value="<?php echo $data1['0']['inv']['HousingLoan'] ?>" placeholder="0" onKeyPress="return checkNumber(this.value,event)" /></td>
         </tr>
         <tr>
             <td>Notified Mutual Fund</td>
             <td><input type="text" name="NotifiedFund" class="qty1" id="NotifiedFund" value="<?php echo $data1['0']['inv']['NotifiedFund'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)" /></td>
         </tr>
         <tr>
             <td>Public Provident Fund</td>
             <td><input type="text" name="ProvidentFund" class="qty1" id="ProvidentFund" value="<?php echo $data1['0']['inv']['ProvidentFund'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>National Savings Certificates</td>
             <td><input type="text" name="NationalSavingCert" class="qty1" id="NationalSavingCert" value="<?php echo $data1['0']['inv']['NationalSavingCert'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Unit Linked Insurance Plan</td>
             <td><input type="text" name="InsurancePlan" id="InsurancePlan" class="qty1" value="<?php echo $data1['0']['inv']['InsurancePlan'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Equity Linked Saving Scheme</td>
             <td><input type="text" name="Equity" id="Equity" value="<?php echo $data1['0']['inv']['Equity'] ?>" class="qty1" placeholder="0" /></td>
         </tr>
         <tr>
             <td>Life Insurance Premium (self / Spouse / Children)</td>
             <td><input type="text" name="LifeInsurancePremium" id="LifeInsurancePremium" class="qty1" value="<?php echo $data1['0']['inv']['LifeInsurancePremium'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Mutual Fund Pension Plan</td>
             <td><input type="text" name="MutualFund" id="MutualFund" value="<?php echo $data1['0']['inv']['MutualFund'] ?>" class="qty1" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Tution Fees</td>
             <td><input type="text" name="TutionFees" id="TutionFees" value="<?php echo $data1['0']['inv']['TutionFees'] ?>" class="qty1" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Interest Accrued on Previous year NSC'S</td>
             <td><input type="text" name="PreNsc" id="PreNsc" value="<?php echo $data1['0']['inv']['PreNsc'] ?>" placeholder="0" class="qty1" onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Scheduled Bank FDS(5 yr lock)</td>
             <td><input type="text" name="ScheduledBank" id="ScheduledBank" value="<?php echo $data1['0']['inv']['ScheduledBank'] ?>" class="qty1" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Senior Citizens Savings Scheme</td>
             <td><input type="text" name="SavingScheme" id="SavingScheme" value="<?php echo $data1['0']['inv']['SavingScheme'] ?>" class="qty1" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Post Office Time Deposit</td>
             <td><input type="text" name="PostOfficeTimeDeposit" id="PostOfficeTimeDeposit" value="<?php echo $data1['0']['inv']['PostOfficeTimeDeposit'] ?>" class="qty1" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
         </tr>
         <tr>
             <td>Other</td>
             <td><input type="text" name="Other" id="Other" value="<?php echo $data1['0']['inv']['Other'] ?>" placeholder="0" class="qty1"  onKeyPress="return checkNumber(this.value,event)"/></td>
             
         </tr>
         
     </tbody>
    
     <thead>
         <tr>
             <td><b>80CCC-Contribution to Pension Fund</b></td>
             <td></td>
             <td><input type="text" name="CCC80" id="CCC80" value="<?php echo $data1['0']['inv']['Other'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
             </tr>
     </thead><thead>
         <tr>
             <th colspan="3"><b>DEDUCTION UNDER SECTION 80CCE</b> (The new section 80CCE in the income Tax Act seeks to provide that the aggregate amount of deductions under section 80C and section 80CCC shall not exceed One Lakh Rupees</th>
         </tr>
     </thead>
     
     
     
     
     <thead>
         <tr>
             <th colspan="3"><b>80D-Medical insurance</b></th>
             
         </tr>
     </thead>
     <tbody>
        <tr>
            <td><input type="radio" name="SelfMedical" id="SelfMedical1" value="Self / Spouse /Dependent Children" <?php echo ($data1['0']['inv']['SelfMedical']=='Self / Spouse /Dependent Children' || $data1['0']['inv']['SelfMedical']=='')?'checked':'' ?> />Self / Spouse /Dependent Children</td>
            <td><div>15000.00</div></td>
            
            
            
            <td rowspan="2">
                <input type="text" name="SelfMedicalAmount" id="SelfMedicalAmount" value="<?php echo $data1['0']['inv']['SelfMedicalAmount'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event);" />
            </td></tr>
        <tr><td>
                <input type="radio" name="SelfMedical" id="SelfMedical2" value="Self [ Sr Citizen] / Dependent Children" <?php echo ($data1['0']['inv']['SelfMedical']=='Self [ Sr Citizen] / Dependent Children')?'checked':'' ?>/>Self [ Sr Citizen] / Dependent Children</td>
            <td><div >20000.00</div></td>
            </tr>
        <tr>
            <td>
                <input type="radio" name="ParentMedical" id="ParentMedical1" value="Parents(Not Sr Citizen)" <?php echo ($data1['0']['inv']['ParentMedical']=='Parents(Not Sr Citizen)' || $data1['0']['inv']['ParentMedical']=='')?'checked':'' ?>/>Parents(Not Sr Citizen) </td>
            <td><div >15000.00</div>
            </td>
            <td rowspan="2">
                <input type="text" name="ParentMedicalAmount" id="ParentMedicalAmount" value="<?php echo $data1['0']['inv']['ParentMedicalAmount'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)" />
            </td>
        </tr>
        <tr>
            
            <td>
                <input type="radio" name="ParentMedical" id="ParentMedical1" value="Parents(Sr Citizen)" <?php echo ($data1['0']['inv']['ParentMedical']=='Parents(Sr Citizen)')?'checked':'' ?>/>Parents(Sr Citizen)</td>
            <td> <div>20000.00</div>
            </td>
        </tr>
        <tr>
            
        </tr>
        <tr>
            
        </tr>
     </tbody>
    
     <thead>
         <tr>
             <th colspan="3"><b>80DD-Physically Handicapped-Dependent</b></th>
             
         </tr>
     </thead>
     <tbody>
        <tr>
            <td>
                <input type="radio" name="PhysicallyHandicapped" id="PhysicallyHandicapped" value="Severity below 80%" <?php echo ($data1['0']['inv']['PhysicallyHandicapped']=='Severity below 80%' || $data1['0']['inv']['PhysicallyHandicapped']=='')?'checked':'' ?>/>Severity below 80%</td>
            
            <td>
                <div>50000.00</div>
            </td>
            
            <td rowspan="2">
                <input type="text" name="PhysicallyHandiAmount" id="PhysicallyHandiAmount" value="<?php echo $data1['0']['inv']['PhysicallyHandiAmount'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)" />
            </td>
            </tr>
        <tr>
            <td>
                <input type="radio" name="PhysicallyHandicapped" id="PhysicallyHandicapped" value="Severity 80% and above" <?php echo ($data1['0']['inv']['PhysicallyHandicapped']=='Severity 80% and above')?'checked':'' ?>/>Severity 80% and above </td>
            <td>
                <div>75000.00</div>
            </td> </tr>
        <thead>
            <tr>
             <td><b>80E-Interest On Education Loan</b></td>
             <td></td>
             <td><input type="text" name="EducationLoan" id="EducationLoan" value="<?php echo $data1['0']['inv']['EducationLoan'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
        </tr>
         <tr>
             <th colspan="3"><b>80U-Permanent Physical Disability</b></th>
        </tr>
     </thead>
     <tbody>
        <tr>
            <td>
                <input type="radio" name="PermanentPhysicallyHandicapped" id="PermanentPhysicallyHandicapped1" value="Severity below 80%" <?php echo ($data1['0']['inv']['PermanentPhysicallyHandicapped']=='Severity below 80%' || $data1['0']['inv']['PermanentPhysicallyHandicapped']=='')?'checked':'' ?>/>Severity below 80%</td>
            <td>
                <div>50000.00</div>
            </td>
            <td rowspan="2">
                <input type="text" name="PermanentPhysicallyHandiAmount" id="PermanentPhysicallyHandiAmount" value="<?php echo $data1['0']['inv']['PermanentPhysicallyHandiAmount'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/>
            </td>
        </tr>
        <tr>
            
            
             <td>
                <input type="radio" name="PermanentPhysicallyHandicapped" id="PermanentPhysicallyHandicapped2" value="Severity 80% and above" <?php echo ($data1['0']['inv']['PermanentPhysicallyHandicapped']=='Severity 80% and above')?'checked':'' ?>/>Severity 80% and above
               </td>
            <td> <div>75000.00</div>
            </td>
        </tr>
            
        
     </tbody>
     
     <thead>
         <tr>
             <th colspan="3"><b>80DDB-Medical Expenses</b></th>
            
        </tr>
     </thead>
     
     <tbody>
        <tr>
            <td>
                <input type="radio" name="MedicalExpense" id="MedicalExpense1" value="Till 65 Years of Age" <?php echo ($data1['0']['inv']['MedicalExpense']=='Till 65 Years of Age' || $data1['0']['inv']['MedicalExpense']=='')?'checked':'' ?>/>Till 65 Years of Age</td>
            <td>
                <div>40000.00</div>
            </td>
            <td rowspan="2">
                <input type="text" name="MedicalExpenseAmount" id="MedicalExpenseAmount" value="<?php echo $data1['0']['inv']['MedicalExpenseAmount'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)" />
            </td>
            </tr> <tr>
            <td>
                <input type="radio" name="MedicalExpense" id="MedicalExpense2" value="Above 65 Years of Age" <?php echo ($data1['0']['inv']['MedicalExpense']=='Above 65 Years of Age' || $data1['0']['inv']['MedicalExpense']=='')?'checked':'' ?>/>Above 65 Years of Age</td>
            <td>
                <div>60000.00</div>
            </td> </tr>
            
            
             </tbody>
     <thead>
        <tr>
            <th>80CCG(Rajiv Gandhi Equity Savings Scheme)</th>
            <td></td>
             <td><input type="text" name="RajivGandhiEquity" id="RajivGandhiEquity" value="<?php echo $data1['0']['inv']['RajivGandhiEquity'] ?>" placeholder="0" onKeyPress="return checkNumber(this.value,event)"/></td>
        </tr>
       
        <tr>
            <th>80G Exemptiontd </th>
            <td></td>
             <td><input type="text" name="Exemption" id="RajivGandhiEquity" value="<?php echo $data1['0']['inv']['Exemption'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/></td>
        </tr>
    </thead>
     <thead>
        
        <tr>
            <th colspan="3"><input type="radio" name="Section24" id="Section241" value="Interest Under Section 24" <?php echo ($data1['0']['inv']['Section24']=='Interest Under Section 24' || $data1['0']['inv']['Section24']=='')?'checked':'' ?> onclick="readonlyinput(this.value)"/><b>SECTION 24</b>
            </th>
            
        </tr> </thead>
     <tbody>
        <tr>
            <td>
                Interest On Housing</td>
            <td><div>200000.00</div>
            </td>
            <td>
                <input type="text" name="InterestOnHousing" id="InterestOnHousing" value="<?php echo $data1['0']['inv']['InterestOnHousing'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)" />
            </td>
            
        </tr>
     </tbody>
     
     <thead>
         <tr>
             <th colspan="3">
                <input type="radio" name="Section24" id="Section242" value="Deduction Under SECTION 24 for Rental Income" <?php echo ($data1['0']['inv']['Section24']=='Deduction Under SECTION 24 for Rental Income')?'checked':'' ?> onclick="readonlyinput(this.value)"/>Deduction Under SECTION 24 for Rental Income
            </th>
            
        </tr>
     </thead>
     <tbody>
         <tr>
            <td>
                Income from House Property </td>
            <td>
                
            </td>
            <td>
                <input type="text" name="InComeOnHousing" id="InComeOnHousing" value="<?php echo $data1['0']['inv']['InComeOnHousing'] ?>" placeholder="0" readonly="" onKeyPress="return checkNumber(this.value,event)" />
            </td></tr><tr>
            <td>
                Interest on Borrowed Capital </td>
            <td>
                
            </td>
            <td>
                <input type="text" name="InterestOnBorrowedCapital" id="InterestOnBorrowedCapital" value="<?php echo $data1['0']['inv']['InterestOnBorrowedCapital'] ?>" readonly="" placeholder="0" onKeyPress="return checkNumber(this.value,event)" />
            </td>
        </tr>
        
        <tr>
            <td>
                Interest on Pre-Construction Period </td>
            <td>
                
            </td>
            <td>
                <input type="text" name="InterestOnPre_Construction_Period" id="InterestOnPre_Construction_Period" readonly="" value="<?php echo $data1['0']['inv']['InterestOnPre_Construction_Period'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/>
            </td>
            
        </tr>
        
     </tbody>
     
     <thead>
         <tr>
             <th colspan="3">
                 <b>INCOME FROM OTHER SOURCES. (Optional if you want to add taxable Income)</b>
            </th>
            
        </tr>
     </thead>
     <tbody>
         <tr>
            <td>
                Bank Interest </td>
            <td>
               
            </td>
            <td>
                <input type="text" name="BankInterest" id="BankInterest" value="<?php echo $data1['0']['inv']['BankInterest'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/>
            </td>
            
        </tr>
        <tr>
            <td>
                Dividend </td>
            
            <td>
                
            </td>
            <td>
                <input type="text" name="Dividend" id="Dividend" value="<?php echo $data1['0']['inv']['Dividend'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/>
            </td>
        </tr>
        <tr>
            <td>
                Any other income (attached details) </td>
            
            <td>
                
            </td>
           <td>
                <input type="text" name="Anyotherincome" id="Anyotherincome" value="<?php echo $data1['0']['inv']['Anyotherincome'] ?>" placeholder="0"  onKeyPress="return checkNumber(this.value,event)"/>
                <input type="hidden" name="EmpCode" id="EmpCode" value="<?php echo $EmpCode; ?>" placeholder="0" />
            </td>
        </tr>
        <tr>
             <td colspan="3"><input type="submit" name="Submit" class="btn btn-info" value="Submit"></td>
        </tr>
        
     </tbody>
    </table>
                 <?php 
                
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
                        <td><input type="text" name="FromDate" id="FromDate" value="<?php echo $datedoj; ?>" class="<?php echo $class; ?>" <?php echo $read; ?>/></td>
                        <td><input type="text" name="ToDate" id="ToDate" value="" class="datepik" /></td>
                        <td><input type="text" name="RentPerMonth" id="RentPerMonth" value="" onKeyPress="return checkNumber(this.value,event)"/></td>
                        <td><input type="text" name="Address" id="Address" value="" /></td>
                        <td><input type="text" name="City" id="City" value="" /></td>
                        <td><input type="text" name="LandLordName" id="LandLordName" value="" /></td>
                        <td><input type="text" name="LandLordPanNo" id="LandLordPanNo" value="" /></td>
                        <td><div class="btn btn-info" onclick="checkvalidation()">Add</div></td>
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
                         <th>Action</th>
                    </tr></thead>';
                $i=1;
                foreach($dataArr as $data)
                { $id = 'id'.$i;
                    echo "<tr id ='".$id."'>";
                        echo "<td>".$data['io']['FromDate'].'</td>';
                        echo "<td>".$data['io']['ToDate'].'</td>';
                        echo "<td>".$data['io']['RentPerMonth'].'</td>';
                        echo "<td>".$data['io']['Address'].'</td>';
                        echo "<td>".$data['io']['City'].'</td>';
                        echo "<td>".$data['io']['LandLordName'].'</td>';
                        echo "<td>".$data['io']['LandLordPanNo'].'</td>';
                        echo "<td>".($data['io']['RentPerMonth']*$data['0']['Total']).'</td>';
                        ?>
                         <td><div class="btn btn-info" onclick="Jajax('<?php echo $id ?>','<?php echo $data['io']['Id'] ?>')">Edit</div></td>
                         <?php
                    echo "</tr>";
                    $i++;
                }
                echo "</table>";
                    
                    ?>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script>
    function dependentchild()
    {
      var valofc=  $('input[name=SelfMedical]:checked').val();
      if(valofc=='Self / Spouse /Dependent Children')
      {
          if($('#SelfMedicalAmount').val()>15000)
          {
              alert("Please Enter The Value Less Then 15000");
              return false;
          }
      }
      else if(valofc=='Self [ Sr Citizen] / Dependent Children')
      {
          if($('#SelfMedicalAmount').val()>20000)
          {
              alert("Please Enter The Value Less Then 15000");
              return false;
          }
      }
    }
    
     function dependentchild1()
    {
      var valofc=  $('input[name=ParentMedical]:checked').val();
      if(valofc=='Parents(Not Sr Citizen)')
      {
          if($('#ParentMedicalAmount').val()>15000)
          {
              alert("Please Enter The Value Less Then 15000");
              return false;
          }
      }
      else if(valofc=='Parents(Sr Citizen)')
      {
          if($('#ParentMedicalAmount').val()>20000)
          {
              alert("Please Enter The Value Less Then 15000");
              return false;
          }
      }
    }
    
    function dependentchild2()
    {
      var valofc=  $('input[name=PhysicallyHandicapped]:checked').val();
      if(valofc=='Severity below 80%')
      {
          if($('#PhysicallyHandiAmount').val()>50000)
          {
              alert("Please Enter The Value Less Then 50000");
              return false;
          }
      }
      else if(valofc=='Severity 80% and above')
      {
          if($('#PhysicallyHandiAmount').val()>75000)
          {
              alert("Please Enter The Value Less Then 75000");
              return false;
          }
      }
    }
    
    function dependentchild3()
    {
      var valofc=  $('input[name=PermanentPhysicallyHandicapped]:checked').val();
      if(valofc=='Severity below 80%')
      {
          if($('#PermanentPhysicallyHandiAmount').val()>50000)
          {
              alert("Please Enter The Value Less Then 50000");
              return false;
          }
      }
      else if(valofc=='Severity 80% and above')
      {
          if($('#PermanentPhysicallyHandiAmount').val()>75000)
          {
              alert("Please Enter The Value Less Then 75000");
              return false;
          }
      }
    }
    
     function dependentchild4()
    {
      var valofc=  $('input[name=MedicalExpense]:checked').val();
      if(valofc=='Till 65 Years of Age')
      {
          if($('#MedicalExpenseAmount').val()>40000)
          {
              alert("Please Enter The Value Less Then 40000");
              return false;
          }
      }
      else if(valofc=='Above 65 Years of Age')
      {
          if($('#MedicalExpenseAmount').val()>60000)
          {
              alert("Please Enter The Value Less Then 60000");
              return false;
          }
      }
    }
    
     function dependentchild5()
    {
      
          if($('#InterestOnHousing').val()>200000)
          {
              alert("Please Enter The Value Less Then 200000");
              return false;
          }
      
    }
    
    
    function readonlyinput(val){
        
        if(val == 'Interest Under Section 24')
        {
           
   document.getElementById("InComeOnHousing").value = '';
document.getElementById("InterestOnBorrowedCapital").value = '';
document.getElementById("InterestOnPre_Construction_Period").value = '';

document.getElementById("InComeOnHousing").readOnly = true;
document.getElementById("InterestOnBorrowedCapital").readOnly = true;
document.getElementById("InterestOnPre_Construction_Period").readOnly = true;
document.getElementById("InterestOnHousing").readOnly = false;
        }
        else if(val == 'Deduction Under SECTION 24 for Rental Income')
        {
            document.getElementById("InComeOnHousing").readOnly = false;
document.getElementById("InterestOnBorrowedCapital").readOnly = false;
document.getElementById("InterestOnPre_Construction_Period").readOnly = false;
document.getElementById("InterestOnHousing").readOnly = true;
document.getElementById("InterestOnHousing").value = '';
        }
        
    }
    </script>
<script>
    function checkvalidation()
    {
         var EmpCode = $('#EmpCode').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var RentPerMonth = $('#RentPerMonth').val();
        var Address = $('#Address').val();
        var City = $('#City').val();
        var LandLordName = $('#LandLordName').val();
        var LandLordPanNo = $('#LandLordPanNo').val();
        if(FromDate=='')
        {
            alert("Please Enter The From Date.");
            $('#FromDate').focus();
            return false;
        }
        else if(ToDate=='')
        {
            alert("Please Enter The To Date.");
            $('#ToDate').focus();
            return false;
        }
         else if(RentPerMonth=='')
        {
            alert("Please Enter The Rent Per Month.");
            $('#RentPerMonth').focus();
            return false;
        }
         else if(Address=='')
        {
            alert("Please Enter The Address.");
            $('#Address').focus();
            return false;
        }
         else 
        {
             var start= new Date(FromDate);
    var end= new Date(ToDate);
   var days = new Date(end- start) / (1000 * 60 * 60 * 24);
 
    var difdsys = Math.round(days);
    if(difdsys != 30)
    {
            alert("Please Enter Days Difference Between From Date And To Date Equal To 30 Days.");
            
            return false;
        }
        else{
           save_other();
           return true;
        }
        }
    }
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
    
    
    function checkval(id,value)
    {
         var EmpCode = $('#EmpCode').val();
        var FromDate = $('#FromDate1').val();
        var ToDate = $('#ToDate1').val();
        var RentPerMonth = $('#RentPerMonth1').val();
        var Address = $('#Address1').val();
        var City = $('#City1').val();
        var LandLordName = $('#LandLordName1').val();
        var LandLordPanNo = $('#LandLordPanNo1').val();
        if(FromDate=='')
        {
            alert("Please Enter The From Date.");
            $('#FromDate').focus();
            return false;
        }
        else if(ToDate=='')
        {
            alert("Please Enter The To Date.");
            $('#ToDate').focus();
            return false;
        }
         else if(RentPerMonth=='')
        {
            alert("Please Enter The Rent Per Month.");
            $('#RentPerMonth').focus();
            return false;
        }
         else if(Address=='')
        {
            alert("Please Enter The Address.");
            $('#Address').focus();
            return false;
        }
         else 
        {
             var start= new Date(FromDate);
    var end= new Date(ToDate);
   var days = new Date(end- start) / (1000 * 60 * 60 * 24);
 
    var difdsys = Math.round(days);
    if(difdsys != 30)
    {
            alert("Please Enter Days Difference Between From Date And To Date Equal To 30 Days.");
            
            return false;
        }
        else{
           update_other(id,value);
           return true;
        }
        }
    }
    function update_other(id,value)
    {
        var EmpCode = $('#EmpCode').val();
        var FromDate = $('#FromDate1').val();
        var ToDate = $('#ToDate1').val();
        var RentPerMonth = $('#RentPerMonth1').val();
        var Address = $('#Address1').val();
        var City = $('#City1').val();
        var LandLordName = $('#LandLordName1').val();
        var LandLordPanNo = $('#LandLordPanNo1').val();
        
        $.post("update_details",
            {
                DivId:id,
                ID:value,
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
                   $('#'+id).html(data);
                   $('#FromDate1').val("");
                   $('#ToDate1').val("");
                   $('#RentPerMonth1').val("");
                   $('#Address1').val("");
                   $('#City1').val("");
                   $('#LandLordName1').val("");
                   $('#LandLordPanNo1').val("");
            }); 
    } 
    
  

function Jajax(id,value)
{
    var url ='BranchEmployeeMovements/edit_detailsRent';
   $.post("<?php echo $this->webroot;?>"+url,
    {
        Id: value,
        DivId:id
    },
    function(data,status){
        $("#"+id).empty();
        $("#"+id).html(data);
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
    