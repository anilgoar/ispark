<div class="page1" >
        <div class="header">
            <h4>Mas Callnet India Pvt. Ltd.</h4>
            <div id="title" >(An ISO 9001 : 2008 Certified Company)</div>
            <div id="logo"><img src="./res/maslogo.png" alt="logo" style="width:105px;"></div>
        </div>
        
        
        <div id="to">To,</div>
        
        <div id="details">
            <div><?php echo $empArr['EmapName'];?></div>
            <div><?php echo $empArr['FatherName'];?></div>
            <div><?php echo $empArr['PerAdrress'];?></div>
        </div>
        
        
        <div id="contant">
            <div class="bdiv">Date:<span style="margin-left:20px;" ><?php echo date("d M Y");?></span></div>
            <div class="bdiv">Subject:<span id="sub">APPOINTMENT &nbsp;LETTER</span></div>
            
            <div>
                <div class="heading">Dear <?php echo $empArr['EmapName'];?></div>
                <div class="txt">With reference to your application and your subsequent interview with us, we have pleasure to inform you that we have agreed to provide you an appointment with us.</div>
                
                <div class="heading">ON THE FOLLOWING TERMS AND CONDITIONS</div>
            
                <div class="heading">1. APPOINTMENT DATE</div>
                <div class="txt" >1.1 This appointment shall take effe <span style="margin-left:50px;font-weight:bold;font-size:10px;"><?php echo  date('d M Y', strtotime($empArr['DOFJ']));?></span></div>
                
                <div class="heading">3. DESIGNATION</div>
                <div class="txt" >2.1 You will be designated as "<?php echo $empArr['Desg'];?>" and you would be reporting to your Reporting Manager.</div>

                <div class="heading">3. REMUNERATION</div>
                <div class="txt">3.1 Your Monthly Salary Breakup would be as follow (In INR)</div>
                
                <div class="table">
                    <table>
                        <tr><td>Basic Salary</td><th  >Rs. .00</th></tr>
                        <tr><td>House Rent Allowance</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Conveyance  Allowance</td><th >Rs.  .00</th></tr>
                        <tr><td>Other Allowance</td><th >Rs.  .00</th></tr>
                        <tr><td>Special Allowance</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Bonus</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Medical Allowance</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Portfolio</td><th >Rs. 4000.00</th></tr>
                        <tr><td>PLI</td><th >Rs. 4000.00</th></tr>
                        <tr><th>Gross Salary</th><th >Rs. .00</th></tr>                
                        <tr><td>&nbsp;</td><th >&nbsp;</th></tr>
                        <tr><th>Deduction</th><th >Rs. .00</th></tr>
                        <tr><td>ESIC</td><th >Rs. .00</th></tr>
                        <tr><td>EPF</td><th >Rs. .00</th></tr>
                        <tr><td>&nbsp;</td><th >&nbsp;</th></tr>
                        <tr><th>Net Salary</th><th >Rs. .00</th></tr>
                        <tr><td>Employer Cont. - ESIC</td><th >Rs. .00</th></tr>
                        <tr><td>Employer Cont. - EPF</td><th >Rs. .00</th></tr>
                        <tr><td>&nbsp;</td><th >&nbsp;</th></tr>
                        <tr><td>Admin Charges</td><th >Rs. 4000.00</th></tr>
                        <tr><th>CTC</th><th >Rs. .00</th></tr>
                    </table>
                </div>
         
                <hr/>
                <div class="footer-text">
                    <div>E-mail : care@teammas.in, Web : WWW.teammas.in</div>
                </div>
            </div>
        </div>
    </div>




<div style=' padding-top:40px;font-size:9.5pt;font-weight: bold;' >To,</div>
        
        <div style='margin-top:10px;'>
            <div>{$empArr['EmapName']}</div>
            <div>{$empArr['FatherName']}</div>
            <div>{$empArr['PerAdrress']}</div>
        </div>
        
        <div style='font-size: 12px;padding-top:40px;width:600px;'>
            <div class='bdiv'>Date:<span style='margin-left:20px;' >".date('d M Y')."</span></div>
            <div class='bdiv'>Subject:<span id='sub'>APPOINTMENT &nbsp;LETTER</span></div>
            
            <div>
                <div class='heading'>Dear <?php echo $empArr['EmapName'];?></div>
                <div class='txt'>With reference to your application and your subsequent interview with us, we have pleasure to inform you that we have agreed to provide you an appointment with us.</div>
                
                <div class='heading'>ON THE FOLLOWING TERMS AND CONDITIONS</div>
            
                <div class='heading>1. APPOINTMENT DATE</div>
                <div class="txt" >1.1 This appointment shall take effe <span style="margin-left:50px;font-weight:bold;font-size:10px;"><?php echo  date('d M Y', strtotime($empArr['DOFJ']));?></span></div>
                
                <div class="heading">3. DESIGNATION</div>
                <div class="txt" >2.1 You will be designated as "<?php echo $empArr['Desg'];?>" and you would be reporting to your Reporting Manager.</div>

                <div class="heading">3. REMUNERATION</div>
                <div class="txt">3.1 Your Monthly Salary Breakup would be as follow (In INR)</div>
                
                <div class="table">
                    <table>
                        <tr><td>Basic Salary</td><th  >Rs. <?php echo $empArr['Basic'];?>.00</th></tr>
                        <tr><td>House Rent Allowance</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Conveyance  Allowance</td><th >Rs. <?php echo $empArr['Conv'];?>.00</th></tr>
                        <tr><td>Other Allowance</td><th >Rs. <?php echo $empArr['OthAllw'];?>.00</th></tr>
                        <tr><td>Special Allowance</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Bonus</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Medical Allowance</td><th >Rs. 4000.00</th></tr>
                        <tr><td>Portfolio</td><th >Rs. 4000.00</th></tr>
                        <tr><td>PLI</td><th >Rs. 4000.00</th></tr>
                        <tr><th>Gross Salary</th><th >Rs. <?php echo $empArr['Gross'];?>.00</th></tr>                
                        <tr><td>&nbsp;</td><th >&nbsp;</th></tr>
                        <tr><th>Deduction</th><th >Rs. <?php echo $empArr['TotalDed'];?>.00</th></tr>
                        <tr><td>ESIC</td><th >Rs. <?php echo $empArr['ESI'];?>.00</th></tr>
                        <tr><td>EPF</td><th >Rs. <?php echo $empArr['PF'];?>.00</th></tr>
                        <tr><td>&nbsp;</td><th >&nbsp;</th></tr>
                        <tr><th>Net Salary</th><th >Rs. <?php echo $empArr['Netpay'];?>.00</th></tr>
                        <tr><td>Employer Cont. - ESIC</td><th >Rs. <?php echo $empArr['EmplrESI'];?>.00</th></tr>
                        <tr><td>Employer Cont. - EPF</td><th >Rs. <?php echo $empArr['EmplrPF'];?>.00</th></tr>
                        <tr><td>&nbsp;</td><th >&nbsp;</th></tr>
                        <tr><td>Admin Charges</td><th >Rs. 4000.00</th></tr>
                        <tr><th>CTC</th><th >Rs. <?php echo $empArr['CTC'];?>.00</th></tr>
                    </table>
                </div>
         
                <hr/>
                <div class="footer-text">
                    <div>E-mail : care@teammas.in, Web : WWW.teammas.in</div>
                </div>
            </div>
        </div>


             
            
            
            
            
            
            
           