
<?php
//print_r($dashddata) ;die;
 $days=5;
    $format = 'd-M-Y';
     $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
        $date=date($format, mktime(0,0,0,$m,($de-$i),$y)); 
       if (date("D", strtotime($date)) == "Sun"){
           $i=$i+1;
    $date=date($format, mktime(0,0,0,$m,($de-$i),$y));
    $days=$days+1;
}
        $dateArray[] = $date;  
    } $dateArray= array_reverse($dateArray);
    ?>

                	
 <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" border="1">	
                               <tr>
                    	<td><strong style="background: #436e90;color: white;">Branch Name</strong><br/></td>
					<td><strong style="background: #436e90;color: white;"><?php echo $branchName;?></strong><br/></td>
							
						<!--<td><strong style="margin-left:3px;"></strong><br/></td>-->
                                                <?php for($i=0;$i<=4;$i++){ ?>
						<td colspan="6"><strong style="margin-left:3px;" ><?php echo $dateArray[$i]; ?></strong><br/></td>
                                                <?php } ?>
                    </tr>
					<tr>
                    	
						 <td><strong >Cost Center Name</strong><br/></td>
						<td><strong >Cost Center</strong><br/></td>
                                               
						   <?php for($i=0;$i<=4;$i++){ ?>
						<td><strong >Rev</strong><br/></td>
                                                <td><strong >DC</strong><br/></td>
                                                <td><strong >IDC</strong><br/></td>
                                                <td><strong >OP</strong><br/></td>
                                                <td><strong >OP%</strong><br/></td>
                                                 <td><strong >EVITA</strong><br/></td>
						<?php } ?>
                    </tr>
				
                   
                        
                        <?php
foreach($dashddata as $Fetch)
{//echo $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] ;die;
				?>
                  
                    	 <tr><td><strong style="margin-left:6px;"><?php echo $Fetch['dp']['Cname']?></strong><br/></td>
                        <td><strong style="margin-left:3px;"><?php echo $Fetch['dp']['cost_center'] ?><input type="hidden" name="Cost<?php echo $i;?>" value="" style="width:25px"/></strong><br/></td>
						<!--<td><strong style="margin-left:6px;"><?php echo $Fetch['dp']['cost_center']?><input type="hidden" name="SubCat<?php echo $i;?>" value="<?php echo $Fetch['cost_center']?>" style="width:25px"/></strong><br/></td>-->
						
                                                 <?php for($i=0;$i<=4;$i++){
                                                    if(!empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])){
                                                 $opper =round(100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])),2);
                                                    }
                                                    else{
                                                    $opper='';    
                                                    }
                                                  //  echo $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'];
                                                      ?>
                        <?php if($i>0 && !empty((100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])))) && (100-((($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']))) > (100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']))) ){ $colorp ='red';$textcp='white'; } 
                                                    else if((100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])))==0|| $opper=='') { $colorp ='';$textcp='';} 
                                                    else if(!empty((100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])))) && (100-((($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']))) < (100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']))) ){ $colorp='#25B35E'; $textcp='white';} ?>
                                                     
                                                     <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'])) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']) > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) ){ $coloro ='red';$textco='white'; } 
                                                      else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'])=='') { $coloro ='';$textco='';} 
                                                      else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'])) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']) < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) ) { $coloro='#25B35E'; $textco='white';} ?>
                                                  <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ){ $color ='red';$textc='white'; } 
                                                  else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']=='') { $color ='';$textc='';} 
                                                  else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ){ $color='#25B35E'; $textc='white';} ?>
                                                <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost'] ){ $color1 ='red'; $textc1='white';} 
                                                else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']=='') { $color1 ='';$textc1='';} 
                                                else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost'] ){  $color1 ='#25B35E'; $textc1='white';}?>
                                                 <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ){ $color2 ='red'; $textc2='white';} 
                                                 else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']=='') { $color2 ='';$textc2='';}
                                                 else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ){$color2 ='#25B35E';$textc2='white';}?>
                         <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['EVITA'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'] ){ $colorVA ='red'; $textVA='white';} 
                                                 else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']=='') { $colorVA ='';$textVA='';}
                                                 else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['EVITA'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'] ){$colorVA ='#25B35E';$textVA='white';}?>
						<td style="color:<?php echo $textc; ?>; background: <?php echo $color;?>"><?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ?></td>
						<td style="color:<?php echo $textc1; ?>; background: <?php echo $color1;?>"><?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']?></td>
                                                <td style="width:45px; color:<?php echo $textc2; ?>;; background: <?php echo $color2;?> "  ><?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ?></td>
                                                <td style="color:<?php echo $textco; ?>; background: <?php echo $coloro;?>" ><?php if(round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']),2)=='0'){ echo ''; } else { echo round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']),2); } ?></td>
                                                <td style="color:<?php echo $textcp; ?>; background: <?php echo $colorp;?>" ><?php echo $opper ?></td>
                                                  <td style=" color:<?php echo $textVA; ?>;; background: <?php echo $colorVA;?> "><?php echo round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'],2) ?> </td>
<?php }?></tr>

                                              <?php    } ?>
                    
					
</table>
					
		