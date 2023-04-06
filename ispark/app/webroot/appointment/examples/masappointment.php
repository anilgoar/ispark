<?php
//mysql_connect("localhost", "root", "dial@mas123")or die("cannot connect");
mysql_connect("localhost", "root", "vicidialnow")or die("cannot connect");
mysql_select_db("db_bill")or die("cannot select DB");    
$empqry = mysql_query("SELECT * FROM `masjclrentry` WHERE EmpCode='{$_REQUEST['Empcode']}' LIMIT 1");
$empArr =mysql_fetch_assoc($empqry);
$DowCnt=$empArr['DownloadCount']+1;
mysql_query("UPDATE masjclrentry SET DownloadCount='$DowCnt' WHERE EmpCode='{$_REQUEST['Empcode']}'");

$newdate=$empArr['DOJ'];

if($empArr['DownloadCount'] >= 1){
    $downloadCopy="Duplicate Copy"; 
}
else{
   $downloadCopy="Original Copy"; 
}


$html .=" 
        <div style='height:1000px;'>
        <div style='margin-left:240px;text-decoration: underline;font-size:11px;' >$downloadCopy</div>
        <table>
            <tr>
                <td style='width:400px;'><span style='font-weight:bold;font-size:19pt;font-family:Arial;'>Mas Callnet India Pvt. Ltd.</span><p style='font-size:12pt;font-family:Arial;' >(An ISO 9001 : 2008 Certified Company)</p></td>
                <td style='width:400px;text-align:right;'><img style='margin-left:' src='maslogo.png' style='width:140px;'></td>
            </tr>
        </table>
        <div style='margin-top:40px;margin-left:20px;font-family:Trebuchet MS'>
            <p span style='font-weight:bold;font-size:9pt;' >To,</p>
            <div style='margin-top:20px;margin-left:20px;font-weight:bold;font-size:9pt;width:280px;'>
                <p>{$empArr['EmpName']}</p>
                <p>EMP Code - {$empArr['EmpCode']}</p>
               
            </div>
            
            <div style='margin-top:40px;font-family:Trebuchet MS'>
                <p span style='font-weight:bold;font-size:9pt;' >Date : <span>".date("d M Y")."</span></p>
                    <div >
                        <span style='font-weight:bold;font-size:9pt;' >Subject :</span>
                        <span style='font-size:10pt;font-weight:bold;'>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        APPOINTMENT LETTER
                        </span>
                    </div>
            </div>  
        </div>
        
        <div style='margin-top:10px;margin-left:30px;font-family:Trebuchet MS;font-size:9pt;'>
            <p span style='font-weight:bold;' >Dear {$empArr['EmpName']}</p>
            <p style='font-size:9pt;'>
            With   reference   to   your   application	and   your   subsequent   interview   with   us,   we   have
            pleasure to inform you that we have agreed to provide you an appointment with us.
            </p>
            <p style='font-weight:bold;margin-top:25px;'>ON THE FOLLOWING TERMS AND CONDITIONS</p>
            <p style='font-weight:bold;margin-top:25px;'>1. APPOINTMENT DATE</p>
            <p>1.1This appointment shall be effective from ".date('d-m-Y', strtotime($newdate))."</p>
            <p style='font-weight:bold;margin-top:25px;'>2. DESIGNATION</p>
            <p>2.1 You will be designated as  '{$empArr['Desgination']}' and you would be reporting to your Reporting Manager.</p>
            <p style='font-weight:bold;margin-top:25px;'>3. REMUNERATION</p>
            <p>3.1 Your Monthly Salary Breakup would be as follow (In INR)</p>
            
            <div style='margin-left:50px;'>
                <table style='width:300px;'>
                    <tr><td>Basic Salary</td><td >Rs. {$empArr['bs']}.00</td></tr>
                    <tr><td>House Rent Allowance</td><td >Rs. {$empArr['hra']}.00</td></tr>
                    <tr><td>Conveyance  Allowance</td><td >Rs. {$empArr['conv']}.00</td></tr>
                    <tr><td>Other Allowance</td><td >Rs. {$empArr['oa']}.00</td></tr>
                    <tr><td>Special Allowance</td><td >Rs. {$empArr['sa']}.00</td></tr>
                    <tr><td>Bonus</td><td >Rs. {$empArr['Bonus']}.00</td></tr>
                    <tr><td>Medical Allowance</td><td >Rs. {$empArr['ma']}.00</td></tr>
                    <tr><td>Portfolio</td><td >Rs. {$empArr['portf']}.00</td></tr>
                    <tr><td>PLI</td><td >Rs. {$empArr['PLI']}.00</td></tr>
                    <tr><td>Gross Salary</td><td >Rs. {$empArr['Gross']}.00</td></tr>                
                    <tr><td>&nbsp;</td><td >&nbsp;</td></tr>
                    <tr><td>ESIC</td><td >Rs. {$empArr['ESIC']}.00</td></tr>
                    <tr><td>EPF</td><td >Rs. {$empArr['EPF']}.00</td></tr>
                    <tr><td>&nbsp;</td><td >&nbsp;</td></tr>
                    <tr><td>Net Salary</td><td >Rs. {$empArr['NetInhand']}.00</td></tr>
                    <tr><td>Employer Cont. - ESIC</td><td >Rs. {$empArr['ESICCO']}.00</td></tr>
                    <tr><td>Employer Cont. - EPF</td><td >Rs. {$empArr['EPFCO']}.00</td></tr>
                    <tr><td>Admin Charges</td><td >Rs. {$empArr['AdminCharges']}.00</td></tr>
                    <tr><td>CTC</td><td >Rs. {$empArr['CTC']}.00</td></tr>
                </table>
            </div>
        </div> 
    </div> 
        ";

$html .="
        <div style='font-size:10pt;font-family:Trebuchet MS;word-spacing:4px;'>
            <p style='font-weight:bold;'>4.PROBATION</p>
            <p>
                4.1 You will be on a probation for a period of six-months from the date of your joining. This period of probation will be
                liable to such extension(s) as the management may deem fit at it's sole discretion. Unless an order in writing, confirming your 
                services is issued and accepted by you, your services will not be deemed to have been confirmed. But, if the management is not 
                satisfied with your work, conduct etc., your service shall be liable to terminate without any notice at any time without assigning 
                any reason during or on completion of the initial or extended probationary period. On Confirmation, the termination of this 
                employment can be affected with a notice period of one month or the basic salary of one month in lieu of the notice period.
            </p>
            <p style='font-weight:bold;margin-top:25px;'>5.PLACEMENT</p>
            <p>
                5.1 You will be liable to transferred to any existing or future department, office or establishment forming part and instruction 
                assigned or communicated to you by the management or those in authority over you from time to time. 
            </p>
            <p style='font-weight:bold;margin-top:25px;'>6.SECRECY</p>
            <p>
                6.1 You will not give out to any unauthorized person by word of mouth or otherwise particulars or details of manufacturing process, 
                data, technical know how, administration and organizational matters, operations plans etc. concerning the Company or its associates, 
                that you shall both during and after your employment take all reasonable precautions to keep such information secret. In the event of 
                any breach you shall indemnify company from any legal action.
            </p> 
            <p style='font-weight:bold;margin-top:25px;'>7.DUTIES/RESPONSIBILITIES</p>
            <p style='margin-top:25px;'>
                7.1 You will perform, observe and conform to such duties, directions, instructions assigned or communicated to you by the Management 
                and those in authority over you.
            </p>

             <p style='margin-top:25px;'>
                7.2 You will have the responsibility of efficient, satisfactory and economical discharge of duties, directions and instructions 
                assigned or communicated to you by the management or those in authority over from time to time.
            </p>

             <p style='margin-top:25px;'>
                7.3 You shall at all times, well and truly account for and shall when so required, make over to responsible authority all moneys, 
                properties and things belonging to the company which may have been placed in your custody or under supervision or may otherwise 
                have come into your possession or under control.
            </p>

             <p style='margin-top:25px;'>
                7.4 You may be required to travel on company work as and when required. In such cases you will be entitled to travel expenses/allowances 
                as  may be in force from time to time.
            </p>

            <p style='margin-top:25px;'>
                7.5 You will devote your whole time during working hours in the work of the company and will not undertake any part time or other work
                whether honorary or remunerative without prior permission of the management.
            </p>
        </div>
        ";

$html .="
        <div style='margin-top:25px;' >
        <table >
            <tr>
            <td style='width:400px;'><p style='font-size:13pt;font-family:Courier New;'>E-mail : care@teammas.in</p></td>
            <td style='width:400px;text-align:right;'><p style='font-size:13pt;font-family:Courier New;'>Web : WWW.teammas.in</p></td>
        </tr>
        </table>
        </div>
        ";

$html .="
        <div style='font-size:10pt;font-family:Trebuchet MS;word-spacing:4px;height:800px;'>
            <p style='font-weight:bold;'>8.OTHER RULES AND REGULATIONS</p>
            <p>
                8.1	You	will	not	without	prior		permission		of		Management,	engage	yourself	or	be
                interested	or		concerned		in	any	other		business	or	activity	of	any	kind	whether	directly	or
                indirectly	or		publish	any	information		about	the		affairs	or	business		or	the	company	or
                enter for any part			of		your	time  in		any	capacity	the	services	of	or	be	employed	by	any
                other firm, company				or		person		whether		honorary,		remuneratory			or		otherwise.	You
                will   devote   whole   time   and   attention   in   discharging	your	duties   with		a	high	standard	of
                initiative, efficiency and economy.																										
            </p>
            <p style='margin-top:25px;'>
                8.2	You	will   not   enter   any   commitments		or	dealings		on	behalf	of	the	Management	for
                which   you   have   no   express   authority		nor	alter	or	be		a	part	to		any		alternation	of	any
                principle	or	policy		of		the		Management	or	exceed	the	authority	or		discretion	vested	in
                you without the prior sanction of the company or those in authority over you.											
            </p>
            <p style='margin-top:25px;'>
                8.3 You	will		disclose	to	us	forthwith	any	discovery,		invention,	process	or	improvement
                made	or	discovered	by	you	while	in		our	service,	and	such	discovery,	invention,	process
                or   improvement   shall   belong   absolutely   to   and   be	the		sole	and	absolute	property	of	the
                Company.	If	and		when		required	to	do	so	by   the   company,   you   shall	at	the	Company's
                expense,	take		out		or		apply	for	Latter's		Patent,	Licenses	or	other		rights,	privileges	or
                protection	as		may	be	required	by	us		in	respect		of	any	such	discovery,	invention,	process
                or	improvement	so	that	the	benefit		thereof	shall	accrue	to	us	for	assigning,	transferring
                or	otherwise	vesting	the	same	and	all	benefits	arising	in	respect	thereof	in	our  favor	or
                in	favor	of  such	other	person	or	persons,		firms	or	companies,	as	we	may   direct	as	the
                sole beneficiary thereof.																														
            </p>
            <p style='margin-top:25px;'>
                8.4	You	shall	not	seek   membership   of		affiliation   of   any   body,   local	or	public	or
                otherwise			including		educational	institutions		without		first	obtaining	permission	from
                the Management.																																	
            </p>
            <p style='font-weight:bold;margin-top:25px;'>9.TERMINATION OF SERVICES</p>
            <p>
                9.1 You	will		automatically	retire	from	the	service	of		the	company		on	attaining	the
                superannuation age of 58 years.																												
            </p>
            <p style='margin-top:25px;'>
                9.2 In	case	you		remain	absent	without		prior		permission	or	authorization	or	overstay
                leave	for	three	consecutive	calendar		days,		beyond		the		period	of	leave	originally	granted
                or   subsequently   extended   it   shall	be		deemed	that	you	have	left		the		services	of	the
                company	on   your   own   accord   without		notice	and		the	same		shall		be	treated	as
                abandonment of service on your part.																										
            </p>
            <p style='margin-top:25px;'>
                9.3 During	Probation,	termination	of	your		employment		will	be	subject	to	Fifteen   Days
                notice in writing from you.																														
            </p>
            <p style='margin-top:25px;'>
                9.4	On	satisfactory   completion   of   the   probation	period	and	after		your	confirmation	in
                writing	except	for		the	reasons	mentioned		in	this	appointment	letter,		your	services	can
                be	terminated		by	giving	notice	of	one	month	or	payment	of   basic	salary	in	lieu	thereof
                on	either	side.	However,in	event	of		your		resignation,	the	company		in		its	sole	discretion
                will	have	an	option	to   accept   the		same	and	relieve	you	prior	to	completion	of	the
                stipulated			notice period of one month, without any pay in lieu of the notice period.					
            </p>

        </div>
        ";

$html .="
        <div style='margin-top:5px;' >
        <table >
            <tr>
            <td style='width:400px;'><p style='font-size:13pt;font-family:Courier New;'>E-mail : care@teammas.in</p></td>
            <td style='width:400px;text-align:right;'><p style='font-size:13pt;font-family:Courier New;'>Web : WWW.teammas.in</p></td>
        </tr>
        </table>
        </div>
        ";


$html .="
        <div style='font-size:10pt;font-family:Trebuchet MS;word-spacing:4px;height:800px;'>
            <p style='margin-top:25px;'>
                9.5	If	at	any   time		in	our	opinion,	which	in   final	in	this	matter,		you	are	insolvent	or
                found   guilty   of   negligence   or   in-discipline	or	of		any	other	conduct		considered		by	us	as
                detrimental   to   our   interest,   or   of	violation	of	one	or	more	terms	of	this	letter,		your
                services are liable to be terminated without any notice or compensation in lieu thereof.												
            </p>
            <p style='margin-top:25px;'>
                9.6 You	are	also	required	to		update	yourself	about	Code	of	Conduct		guidelines,
                company	policies	and		procedures	as	framed	and	changed	by		company	from	time	to	time
                in	the	light	of	changing	business	scenarios.	Any		Violation   of	the	above	terms	and		any
                other   Code   of   Conduct   guidelines	or	Company		policies	and		procedures	would	result		in
                immediate	termination		of	service		without   any	notice	or	warning	or		compensation	in		lieu
                thereof.																																
            </p>
            <p style='margin-top:25px;'>
                9.7 In	case	any	declaration	or	particulars	given	by	you	in		your		application		for
                employment   is   found   to   be   wrong	or	you	are		found	to	have   willfully	suppressed		any
                material	information,		this		appointment	will	be	liable	to	termination	without	any	notice
                or compensation in lieu thereof.																									
            </p>
            <p style='margin-top:25px;'>
                All	terms	and	conditions	will	be		governed	by	the	Company's		policies		as	stated	from	time
                to  time  and  the  company  may  in		its	sole	discretion	as	it	deems	fit	revoke	or	change		such
                Policies.																																
            </p>
            <p style='margin-top:25px;'>
                The   terms   of   this   offer   shall   be	kept	strictly		confidential.		You	shall	execute	all		the
                documents as indicated in Annexure-I so as to give effect to this offer.													
            </p>
            <p style='margin-top:25px;'>
                Please   return   the   duplicate   copy	of	this	letter	duly	signed	in	token		of	your	having
                accepted		the	offer.	Please	initial		each	page	in		acceptance	of	the		terms	and	conditions
                set	out		herein	latest	by	10 days	of	the	issuance	of	the	letter	else	this	offer	stands
                automatically withdrawn.																											
            </p>
            <p style='margin-top:25px;'>
                We	welcome	you	and	wish	you	every	success	in		your	career		with	Mas	Callnet	India		Pvt.
                Ltd.																																	
            </p>
            <p style='margin-top:25px;'>
                Sincerely,
            </p>
            <p style='margin-top:25px;'>
                For Mas Callnet India Pvt. Ltd.
            </p>
            <p style='margin-top:25px;'>
                Authorized Signatory
            </p>
            <p style='margin-top:25px;'>
                Date of Joining:	".date('d-m-Y', strtotime($newdate))."
            </p>
           

        </div>
        ";
 
$html .="
        <div style='margin-top:100px;' >
        <table >
            <tr>
            <td style='width:400px;'><p style='font-size:13pt;font-family:Courier New;'>E-mail : care@teammas.in</p></td>
            <td style='width:400px;text-align:right;'><p style='font-size:13pt;font-family:Courier New;'>Web : WWW.teammas.in</p></td>
        </tr>
        </table>
        </div>
        ";


$html .="
        
        <div style='font-size:9pt;font-family:Trebuchet MS;height:800px;'>
            <p style='text-align:center;font-weight:bold;'>ANNEXURE-I</p>
            <p style='text-align:center;font-weight:bold;margin-top:50px;'>DOCUMENTS/CREDENTIALS/REQUIRED AT THE TIME OF JOINING</p>
        
            <ol style='font-size:9pt' >
                <li>Six recent passport sized photographs.</li>
                <li>A Copy of updated Curriculum Vitae</li>
                <li>A Copy of Appointment letter</li>
                <li>Proof of Address ( Copy of Rent Agreement, Ration Card, Voter's ID card, Driving License, Electricity Bill, Landline Bill)</li>
                <li>Secondary School Certificate (10th) / 10th Mark sheet</li>
                <li>Senior Secondary School Certificate (12th)/ 12th Mark sheet</li>
                <li>Bachelor's Degree, All yrs. Mark sheet / Graduation degree certificate/ diploma/ Certification Course</li>
                <li>Post Graduation Certificate.</li>
                <li>Additional Qualification</li>
                <li>Proof of Identity ( Copy of passport/ driving license/ voter's ID card/ bank pass book with photo/ pan card)</li>
                <li>Appointment Letter of Last Organization Served.</li>
                <li>Last Pay Slip drawn</li>
                <li>Form 16 (1) (Pertaining to Tax deducted at source) from the previous or salary certificate.</li>
            </ol>
            <p style='text-align:center;font-weight:bold;margin-top:25px;'>DOCUMENTS TO BE DULY FILLED AND SIGNED AT THE TIME OF JOINING</p>

            <ol style='font-size:9pt;margin-top:25px;' >
                <li>Employee's Record Form</li>
                <li>Code of Conduct</li>
                <li>Phone Undertaking/Asset Undertaking</li>
                <li>ESI Form</li>
                <li>EPF Form</li>
            </ol>

            <p style='text-align:center;font-weight:bold;margin-top:35px;text-decoration:underline;'>INFORMATION REQUIRED FOR TRANSFERRING PROVIDENT FUND/<br/>SUPERANNUATION FROM PREVIOUS COMPANY</p>

            <p style='margin-top:25px;'>If already a member of a Provident Fund (PF)/ Superannuation Scheme with Previous employer,</p>
            
            <ol style='font-size:9pt;margin-top:35px;' >
                <li>Employer's name</li>
                <li>Date of Joining and leaving service with them</li>
                <li>Name and address of the PF/ Superannuation<br/> Trust or the Regional Provident Fund</li>
                <li>Personal PF/ Superannuation Account No.</li>
                <li>Social Security No. (SSN) if allotted</li>
            </ol>
            
            
           

        </div>
        ";

$html .="
        <div style='margin-top:100px;' >
        <table >
            <tr>
            <td style='width:400px;'><p style='font-size:13pt;font-family:Courier New;'>E-mail : care@teammas.in</p></td>
            <td style='width:400px;text-align:right;'><p style='font-size:13pt;font-family:Courier New;'>Web : WWW.teammas.in</p></td>
        </tr>
        </table>
        </div>
        ";
 
//==============================================================
//==============================================================
//==============================================================
include("../mpdf.php");

$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list



// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$arr = array (

  'odd' => array (

    'L' => array (

      'content' => 'E-mail : care@teammas.in',

      'font-size' => 9,

      //'font-style' => 'B',

      'font-family' => 'Courier New',

      'color'=>'#000000'

    ),

    'R' => array (

      'content' => 'Web : WWW.teammas.in',

      'font-size' => 9,

      //'font-style' => 'B',

      'font-family' => 'Courier New',

      'color'=>'#000000'

    ),

    'line' => 1,

  ),

  'even' => array ()

);


          
//$mpdf->SetFooter($arr);



$mpdf->WriteHTML($html,2);

$mpdf->Output('mpdf.pdf','I');
exit;

//==============================================================
//==============================================================
//==============================================================


?>