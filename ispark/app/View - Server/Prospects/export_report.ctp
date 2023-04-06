<?php

    $fileName = "prospect";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
       
?>

<table border="2" >
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Company</th>
            <th>Branch</th>
            <th>Product</th>
            <th>Lead Source</th>
            <th>Client Name</th>
            <th>Contact No.</th>
            <th>Email</th>
            <th>Address</th>
            <th>Introduction</th>
            <th>Email To</th>
            <th>Email CC</th>
            <th>Email Sub</th>
            <th>Sender Email</th>
            <th>Lead Status</th>
            <th>Follow Date</th>
            <th>Follow Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; //print_r($data); exit;
                foreach($data as $exp)
                {
                    echo "<tr>";
                        echo "<td>".$i++."</td>";
                        echo "<td>".$exp['pc']['company']."</td>";
                        echo "<td>".$exp['pc']['branch']."</td>";
                        echo "<td>".$exp['pp']['ProductName']."</td>";
                        echo "<td>".$exp['pls']['LeadSource']."</td>";
                        echo "<td>".$exp['pls']['ClientName']."</td>";
                        echo "<td>".$exp['pc']['ContactNo']."</td>";
                        echo "<td>".$exp['pc']['Email']."</td>";
                        echo "<td>".$exp['pc']['Address']."</td>";
                        echo "<td>".$exp['pc']['Introduction']."</td>";
                        echo "<td>".$exp['pf']['to']."</td>";
                        echo "<td>".$exp['pf']['cc']."</td>";
                        echo "<td>".$exp['pf']['subject']."</td>";
                        echo "<td>".$exp['pec']['LeadSource']."</td>";
                        echo "<td>".$exp['pf']['LeadStatus']."</td>";
                        echo "<td>".$exp['pf']['FollowDate']."</td>";
                        echo "<td>".$exp['pf']['Remarks']."</td>";
                    echo "</tr>";
                }
        ?>
    </tbody>
</table>    