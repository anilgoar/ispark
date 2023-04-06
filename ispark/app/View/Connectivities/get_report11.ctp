<?php //print_r($result); ?>
<?php //print_r($res); ?>
<?php 
	$fileName = "ExportDataDaysWise";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$type.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>

             <?php      
                            if(!empty($Data))
        {
                                
                                if(($type=='HardWare'))
                                {
                                ?><div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">
            <tr>
                        <th >Devices Name</th>     
                       
                             <th >BranchName</th>   
                        <th >Owner</th>
                        <th >Working</th>
                        <th >Not working</th>
                        <th >Damage</th>
                        <th >Stand by </th>
                         <th >Stand by but notworking </th>
                         <th >Save Date </th>
                       
                        
            </tr>
            <?php
            


                

                    foreach($Data as $da):
                        
                        $d = $da['hardware'];
                         echo "<tr>";
                        echo "<td>".$d['DeviceName']."</td>";
                                   
                                    echo "<td>".$d['BranchName']."</td>";
                                    echo "<td>".$d['Owner']."</td>";
                                   echo "<td>".$d['working']."</td>";
                                    echo "<td>".$d['NotWorking']."</td>";
                                   echo "<td>".$d['Damage']."</td>";
                                   
                                   echo "<td>".$d['StandBy']."</td>";
                                    echo "<td>".$d['StandByNet']."</td>";
                                   echo "<td>".$da[0]['SaveDataDate']."</td>";
                                   //echo "<td>".$da[0]['Importdate']."</td>";
                        echo "</tr>";
                       
        endforeach;
                      
?>
        </table>
                                </div>


                                <?php } else if(($type=='Connectivity'))
                                {
                                ?><div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">
            <tr>
                        <th >Connectivity Type</th>     
                       
                             <th >Cunsumercode</th>   
                        <th >RelationshipNo</th>
                        <th >TariffPlan</th>
                        <th >BillingAddress</th>
                        <th >BillingPeriod</th>
                        <th >BillingType </th>
                         <th >Bandwidth </th>
                         
                          <th >PlanName</th>   
                        <th >Billdate</th>
                        <th >BillDuedate</th>
                        <th >securitydeposit</th>
                        <th >ContactPerson</th>
                        <th >MobileNo </th>
                         <th >Username </th>
                         
                         
                          <th >Ownership</th>   
                        <th >Rembursment</th>
                        <th >Branch</th>
                        <th >ActivePlan</th>
                        <th >ApprovedAmount</th>
                        <th >SaveDataDate </th>
                         
                       
                        
            </tr>
            <?php
            


                

                    foreach($Data as $da):
                        
                        $d = $da['tbl_connectivity'];
                         echo "<tr>";
                        echo "<td>".$d['ConnectivityType']."</td>";
                                   
                                    echo "<td>".$d['Cunsumercode']."</td>";
                                    echo "<td>".$d['RelationshipNo']."</td>";
                                   echo "<td>".$d['TariffPlan']."</td>";
                                    echo "<td>".$d['BillingAddress']."</td>";
                                   echo "<td>".$d['BillingPeriod']."</td>";
                                   
                                   echo "<td>".$d['BillingType']."</td>";
                                    echo "<td>".$d['Bandwidth']."</td>";
                                  
                                   
                                   
                                   
                                    echo "<td>".$d['PlanName']."</td>";
                                    echo "<td>".$d['Billdate']."</td>";
                                   echo "<td>".$d['BillDuedate']."</td>";
                                    echo "<td>".$d['securitydeposit']."</td>";
                                   echo "<td>".$d['ContactPerson']."</td>";
                                   
                                   echo "<td>".$d['MobileNo']."</td>";
                                    echo "<td>".$d['Username']."</td>";
                                   echo "<td>".$d['Ownership']."</td>";
                                   
                                   
                                    echo "<td>".$d['Rembursment']."</td>";
                                    echo "<td>".$d['Branch']."</td>";
                                   echo "<td>".$d['ActivePlan']."</td>";
                                    echo "<td>".$d['ApprovedAmount']."</td>";
                                  
                                   echo "<td>".$da[0]['SaveDataDate']."</td>";
                                   
                                   
                                   //echo "<td>".$da[0]['Importdate']."</td>";
                        echo "</tr>";
                       
        endforeach;
                      
?>
        </table>
                                </div>


 <?php } else if(($type=='Mobile Data'))
                                {
                                ?><div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">
            <tr>
                        <th >Connectivity Type</th>     
                       
                             <th >Cunsumercode</th>   
                        <th >RelationshipNo</th>
                        <th >TariffPlan</th>
                        <th >BillingAddress</th>
                        <th >BillingPeriod</th>
                        
                         
                          <th >PlanName</th>   
                        <th >Billdate</th>
                        <th >BillDuedate</th>
                        <th >securitydeposit</th>
                        <th >ContactPerson</th>
                        <th >MobileNo </th>
                         <th >Username </th>
                         
                         
                          <th >Ownership</th>   
                        <th >Rembursment</th>
                        <th >Rembursment</th>
                        <th >ActivePlan</th>
                        <th >ApprovedAmount</th>
                        <th >SaveDataDate </th>
                         
                       
                        
            </tr>
            <?php
            


                

                    foreach($Data as $da):
                        
                        $d = $da['tbl_mobile'];
                         echo "<tr>";
                        echo "<td>".$d['ConnectivityType']."</td>";
                                   
                                    echo "<td>".$d['Cunsumercode']."</td>";
                                    echo "<td>".$d['RelationshipNo']."</td>";
                                   echo "<td>".$d['TariffPlan']."</td>";
                                    echo "<td>".$d['BillingAddress']."</td>";
                                   echo "<td>".$d['BillingPeriod']."</td>";
                                   
                                  
                                  
                                   
                                   
                                   
                                    echo "<td>".$d['PlanName']."</td>";
                                    echo "<td>".$d['Billdate']."</td>";
                                   echo "<td>".$d['BillDuedate']."</td>";
                                    echo "<td>".$d['securitydeposit']."</td>";
                                   echo "<td>".$d['ContactPerson']."</td>";
                                   
                                   echo "<td>".$d['MobileNo']."</td>";
                                    echo "<td>".$d['Username']."</td>";
                                   echo "<td>".$d['Ownership']."</td>";
                                   
                                   
                                    echo "<td>".$d['Rembursment']."</td>";
                                    echo "<td>".$d['Branch']."</td>";
                                   echo "<td>".$d['ActivePlan']."</td>";
                                    echo "<td>".$d['ApprovedAmount']."</td>";
                                  
                                   echo "<td>".$da[0]['SaveDataDate']."</td>";
                                   
                                   
                                   //echo "<td>".$da[0]['Importdate']."</td>";
                        echo "</tr>";
                       
        endforeach;
                      
?>
        </table>
                                </div>




        <?php }} ?>

