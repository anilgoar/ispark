<style>
.hideextra { white-space: nowrap; overflow: hidden; text-overflow:ellipsis; }
</style>
<?php
/*function convert_number_to_words($number) {
    
    $hyphen      = ' - ';
    $conjunction = ' & ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
		100000             => 'Lakh',
        10000000            => 'Crore',
        1000000000          => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(100, floor(log($number, 1000)))*10;
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}*/
/*
function convertNumberToWordsForIndia($number){
        $words = array(
        '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five',
        '6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten',
        '11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen',
        '16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty',
        '30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy',
        '80' => 'eighty','90' => 'ninty');

        //First find the length of the number
        $number_length = strlen($number);
        //Initialize an empty array
        $number_array = array(0,0,0,0,0,0,0,0,0);        
        $received_number_array = array();

        //Store all received numbers into an array
        for($i=0;$i<$number_length;$i++){    $received_number_array[$i] = substr($number,$i,1);    }

        //Populate the empty array with the numbers received - most critical operation
        for($i=9-$number_length,$j=0;$i<9;$i++,$j++){ $number_array[$i] = $received_number_array[$j]; }
        $number_to_words_string = "";        
        //Finding out whether it is teen ? and then multiplying by 10, example 17 is seventeen, so if 1 is preceeded with 7 multiply 1 by 10 and add 7 to it.
        for($i=0,$j=1;$i<9;$i++,$j++){
            if($i==0 || $i==2 || $i==4 || $i==7){
                if($number_array[$i]=="1"){
                    $number_array[$j] = 10+$number_array[$j];
                    $number_array[$i] = 0;
                }        
            }
        }

        $value = "";
        for($i=0;$i<9;$i++){
            if($i==0 || $i==2 || $i==4 || $i==7){    $value = $number_array[$i]*10; }
            else{ $value = $number_array[$i];    }            
            if($value!=0){ $number_to_words_string.= $words["$value"]." "; }
            if($i==1 && $value!=0){    $number_to_words_string.= "Crores "; }
            if($i==3 && $value!=0){    $number_to_words_string.= "Lakhs ";    }
            if($i==5 && $value!=0){    $number_to_words_string.= "Thousand "; }
            if($i==6 && $value!=0){    $number_to_words_string.= "Hundred &amp; "; }
        }
        if($number_length>9){ $number_to_words_string = "Sorry This does not support more than 99 Crores"; }
        return ucwords(strtolower("Rupees ".$number_to_words_string)." Only.");
    }
	*/
	
		function convertNumberToWordsForIndia($strnum)
{
        $words = array(
        '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five',
        '6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten',
        '11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen',
        '16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty',
        '30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy',
        '80' => 'eighty','90' => 'ninty');
		
		//echo $strnum = "2070000"; 
		 $len = strlen($strnum);
		 $numword = "Rupees ";
		while($len!=0)
		{
			if($len>=8 && $len<= 9)
			{
				$val = "";
				
				
				if($len == 9)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 7;
					$strnum =   substr($strnum,2,7);
				}
				if($len== 8)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =7;
					$strnum =   substr($strnum,1,7);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Crore ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
				$numword.=  "Crores ";
				}
				
			}
			if($len>=6 && $len<= 7)
			{
				$val = "";
				
				
				if($len == 7)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 5;
					$strnum =   substr($strnum,2,7);
				}
				if($len== 6)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =5;
					$strnum =   substr($strnum,1,7);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Lakh ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
				$numword.=  "Lakhs ";
				}
				
			}
		
			if($len>=4 && $len<= 5)
			{
				$val = "";
				if($len == 5)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 3;
					$strnum =   substr($strnum,2,4);
				}
				if($len== 4)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =3;
					$strnum =   substr($strnum,1,3);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Thousand ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
					$numword.=  "Thousand ";
				}
			}
			if($len==3)
			{
				$val = "";
				$value = substr($strnum,0,1);
				$val  = $value;
				$numword.= $words["$value"]." ";
				$len = 2;
				$strnum =   substr($strnum,1,2);

				if($val == 1)
				{
					$numword.=  "Hundred ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
					$numword.=  "Hundred ";
				}
			}
			if($len>=1 && $len<= 2)
			{
				if($len ==2)
				{
				$value = substr($strnum,0,1);
				$value = $value *10;
				$value1 = $value;
				$strnum =   substr($strnum,1,1);
				$value2 = substr($strnum,0,1);
				$value =$value1 + $value2;				
				}
				if($len ==1)
				{	
					$value = substr($strnum,0,1);
					
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
					$len =0;
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
					$len =0;
				}
				$numword.=  "Only ";

			}
			
			break;
		}
		return ucwords(strtolower($numword));

}

?>
<html>
<head>
<style>
th#t01 {
    border-right:1px solid black;
	border-bottom:1px solid black;
}
td#t02
{
	border-right:1px solid black;
}
</style>
<style>
th#t03
{
	border-bottom:1px solid black;
}
th#t04
{
	border: 1px solid black;
}
th#t05
{
	border-bottom:1px solid black;
	border-Left:1px solid black;
	border-Top:1px solid black;
}
td#t06
{
	border-bottom:1px solid black;
	border-Top:1px solid black;
}
td#t07
{
	border-bottom:1px solid black;
}

</style>
</head>
<body style="font-size:12px;  font-family:Arial, Helvetica, sans-serif;">
<br>
<br>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table>
			<tr>
				<td width="370" style="font-size:25px; font-family:Arial, Helvetica, sans-serif;">&nbsp;
					
				</td>

				<td align="right">
					<?php //echo $this->Html->image('MasLogo.jpg', array('fullBase' => true,'height'=>60));?>
				</td>
			</tr>
			</table>
		</td>	
	</tr>
</table>
<br>
<table width="544" border ="1" cellpadding="2" cellspacing="0">
	<tr>
		<td colspan="2">Bill to Address</td>
		<td width="130">Ship to Address</td>
		<td width="130">Date</td>
		<td width="130"><?php 
		$date=date_create($tbl_invoice['InitialInvoice']['invoiceDate']);
		echo date_format($date,"d-M-Y"); ?></td>
	</tr>

	<tr>
		<td colspan="2"  valign="top">
			<?php if($cost_master['CostCenterMaster']['as_client']) echo $btr = '<b>'.$cost_master['CostCenterMaster']['client'].'</b><br>'; ?>
			<?php if(!$cost_master['CostCenterMaster']['as_client']) echo $btr = '<b>'.$cost_master['CostCenterMaster']['bill_to'].'</b><br>'; ?>
			<?php echo $cost_master['CostCenterMaster']['b_Address1']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['b_Address2']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['b_Address3']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['b_Address4']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['b_Address5']; ?><br>
		</td>
		<td valign="top">
			<?php if($cost_master['CostCenterMaster']['as_bill_to']) { echo $btr; }?>
			<?php if(!$cost_master['CostCenterMaster']['as_bill_to']) echo '<b>'.$cost_master['CostCenterMaster']['ship_to'].'</b><br>'; ?>
			<?php echo $cost_master['CostCenterMaster']['a_address1']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['a_address2']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['a_address3']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['a_address4']; ?><br>
			<?php echo $cost_master['CostCenterMaster']['a_address5']; ?><br>
			<?php unset($CostCenterMaster); ?>
		</td>
		<td  valign="top">
			<table width="130" cellpadding="0" cellspacing="0">
					<tr><td width="130" id = "t07">Bill No</td></tr>
					<tr><td id = "t06">JCC No</td></tr>
					<tr><td><b>PO No</b></td></tr>
					<tr><td id = "t06">Pan Based Service Tax No</td></tr>
					<tr><td id = "t06">Service Tax Category</td></tr>
					<tr><td id = "t06">Pan No</td></tr>
					<tr><td id = "t06"><b>GRN No</b></td></tr>
		  </table>
		</td>
		<td height="100" valign="top">
			<table cellpadding="0" cellspacing="0" width="130">
					<tr><td width="130"  id = "t07"><?php echo $tbl_invoice['InitialInvoice']['bill_no']; ?>&nbsp;</td></tr>

					<tr><td id = "t06"><?php echo $tbl_invoice['InitialInvoice']['jcc_no']; ?>&nbsp;</td></tr>
					<tr><td id = "t06"><b><?php if($tbl_invoice['InitialInvoice']['approve_po']=='Yes')echo $tbl_invoice['InitialInvoice']['po_no']; ?>&nbsp;</b></td></tr>
					<tr><td id = "t06"><?php echo $company['Addcompany']['service_no'];?>&nbsp;</td></tr>
					<tr><td id = "t06">Business Auxillary Services</td></tr>
					<tr><td id = "t06"><?php echo $company['Addcompany']['pan_no'];?>&nbsp;</td></tr>
					<tr><td id = "t06"><b><?php if($tbl_invoice['InitialInvoice']['approve_grn']=='Yes')echo $tbl_invoice['InitialInvoice']['grn']; ?>&nbsp;</b></td></tr>
		  </table>

		</td>
	</tr>

	<tr>
	<td colspan="5"  valign = "top" style = "height:400">
	<table width="540" height = "400" cellpadding="0" cellspacing="0" >
		<tr>
		<th width = "20"  id="t01">S.No</th>
		<th width = "202" id="t01">Particulars</th>
		<th width = "48"  id="t01">Qty</th>
		<th width = "60"  id="t01">Rate</th>
		<th width = "110" id="t03">Amount</th>
		</tr>

		<?php
			$i=1; 
			foreach($inv_particulars as $post) :
			?>
				<tr>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$i++."."; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['Particular']['particulars']; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['Particular']['qty']; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['Particular']['rate']; ?></td>
				<td align="center" valign="top"><?php echo "<br>".round($post['Particular']['amount'],2); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php  if(isset($inv_deduct_particulars['0'])) { ?>
				<tr>
				<td align="center" valign="top" id = "t02"></td>
				<th align="center" valign="top" id = "t04">Less</th>
				<td align="center" valign="top" id = "t02"></td>
				<td align="center" valign="top" id = "t02"></td>
				<td align="center" valign="top" id = "t02"></td>
				</tr>
			<?php } ?>

			<?php $j = 1;
			foreach($inv_deduct_particulars as $post) :
			?>
				<tr>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$j++."."; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['DeductParticular']['particulars']; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['DeductParticular']['qty']; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['DeductParticular']['rate']; ?></td>
				<td align="center" valign="top"><?php echo "<br>".round($post['DeductParticular']['amount'],2); ?></td>
				</tr>
			<?php endforeach; ?>	
			
		<tr>
			<td height = "<?php echo 20*(13-$i-$j); ?>" id = "t02"></td>
			<td id = "t02"></td>
			<td id = "t02"></td>
			<td id = "t02"></td>
			<td></td>
		</tr>	
		<tr>
			<td rowspan="<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14")) {if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2016-05-31")) echo "6"; else echo "5"; } else echo "4"; ?>" id = "t02"></td>
                        <td rowspan="<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))  {if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2016-05-31")) echo "6";  else echo "5";} else echo "4"; ?>"></td>
			<td ></td>
			<td ></td>
			<td></td>
		</tr>

	<tr>
		<th colspan="2" id ="t04">Total</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['total']); ?></th>
	</tr>

	<tr>
		<th colspan="2" id ="t04">Service Tax @ 14%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['tax']); ?></th>
	</tr>
	<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14")) { ?>
	<tr>
		<th colspan="2" id ="t04">SBC Tax @ 0.5%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['sbctax']); ?></th>
	</tr>
	<?php } ?>
        <?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2016-05-31")) { ?>	
	<tr>
		<th colspan="2" id ="t04">KKC @ 0.5%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['krishi_tax']); ?></th>
	</tr>
	<?php } ?>
	<tr>
		<th colspan="2" id ="t04">G. Total</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['grnd']); ?></th>
	</tr>
	<tr>
		<td colspan="4" id ="t06"><b><i>Amount In Words : <?php 
		echo ucwords(convertNumberToWordsForIndia(round($tbl_invoice['InitialInvoice']['grnd'])));?></i></b></td>
		<th id ="t05"></th>
	</tr>

	<tr>
		<td colspan="5" id="t06">
		<br>
			Note : Please issue Ch/DD in favour of <br>
			SBI A/c. <?php if($cost_master['CostCenterMaster']['company_name']!='IDC')
                                        {echo "MAS Callnet India Pvt. Ltd.";}
                                        else {echo "ISPARK Dataconnect Pvt. Ltd.";}
                                ?> Payable at Delhi
		</td>
	</tr>

	<tr>
		<td colspan="4" valign="top">
		<b>Covered under MSME Act vide letter No : F/5/CL/EM/2012/2062 dated 19.12.12 <br>
		Enterpreneurs Memorandum No. : '070092201354'</b>
		</td>
		<td >
		<b>for <?php if($cost_master['CostCenterMaster']['company_name']!='IDC')
                                        {echo "MAS Callnet India Pvt. Ltd.";}
                                        else {echo "ISPARK Dataconnect Pvt. Ltd.";}
                                ?> </b>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<b>Authorised Signatory</b>
		</td>
	</tr>
		</table>	
	</td>		
	</tr>	

</table>
<br>
<br>
</body>
</html>