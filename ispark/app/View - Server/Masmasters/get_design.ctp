<?php ?>


<div class="col-sm-12" style="overflow:scroll;height:300px;" >
        <table class = "table table-striped table-hover  responstable">
                    <thead>
                        <tr><th colspan="22" style="text-align: left;" >PACKAGE DETAILS</th></tr>
                        <tr>
                            <th>Branch</th>
                            <th>Band</th>
                            <th>Package</th>
                            <th>Basic</th>
                            <th>HRA</th>
                            <th>Conveyance</th>
                            <th>Portfolio</th>
                            <th>Medical Allowance</th>
                            <th>Special Allowance</th>
                            <th>Bonus</th>
                            <th>Other Allowance</th>
                            <th>Gross</th>
                            <th>Esic</th>
                            <th>Epf</th>
                            <th>Pro. Tax</th>
                            <th>In Hand</th>
                            <th>Epf Co</th>
                            <th>Esic Co</th>
                            <th>Admin Chg.</th>
                            <th>CTC</th>
                            <th>PLI</th>
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($Data as $val){?>
                    <tr>
                        <td><?php echo $val['maspackage']['BranchName'];?></td>
                        <td><?php echo $val['maspackage']['Band'];?></td>
                        <td><?php echo $val['maspackage']['PackageAmount'];?></td>
                        <td><?php echo $val['maspackage']['Basic'];?></td>
                        <td><?php echo $val['maspackage']['HRA'];?></td>
                        <td><?php echo $val['maspackage']['Conveyance'];?></td>
                        <td><?php echo $val['maspackage']['Portfolio'];?></td>
                        <td><?php echo $val['maspackage']['Medical'];?></td>
                        <td><?php echo $val['maspackage']['Special'];?></td>
                         <td><?php echo $val['maspackage']['Bonus'];?></td>
                        <td><?php echo $val['maspackage']['OtherAllow'];?></td>
                       
                        
                         <td><?php echo $val['maspackage']['Gross'];?></td>
                         <td><?php echo $val['maspackage']['ESIC'];?></td>
                        <td><?php echo $val['maspackage']['EPF'];?></td>
                        
                        <td><?php echo $val['maspackage']['Professional'];?></td>
                         <td><?php echo $val['maspackage']['NetInHand'];?></td>
                        <td><?php echo $val['maspackage']['EPFCO'];?></td>
                        
                        <td><?php echo $val['maspackage']['ESICCO'];?></td>
                         <td><?php echo $val['maspackage']['Admin'];?></td>
                        <td><?php echo $val['maspackage']['CTC'];?></td>
                         <td><?php echo $val['maspackage']['PLI'];?></td>
                        <?php 
                echo "<code><b><td>".$this->Html->link('Edit',array('controller'=>'Masmasters','action'=>'editpackage','?'=>array('id'=>$val['maspackage']['id']),'full_base' => true))."</td></code><br>";
               
?>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
</div>
                        

        
