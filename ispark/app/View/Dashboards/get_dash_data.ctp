<?php if(!empty($dashboarddata))
{?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name"><span>Dashboard Entry Details</span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <div id='dash'>    
                            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                               <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Tower</th>
                                            <th>Commitment</th>
                                            <th>Direct Cost</th>
                                            <th>Indirect Cost</th>
                                            
                                            <th>OP</th>
                                        </tr>
                               </thead>
                               <?php 
                               foreach($dashboarddata as $dash):
                                    echo "<tr>";
                                        echo "<td>".$dash['0']['date']."</td>";
                                        echo "<td>".$dash['dp']['branch_process']."</td>";
                                        echo "<td>".$dash['dd']['commit']."</td>";
                                        echo "<td>".$dash['dd']['direct_cost']."</td>";
                                        echo "<td>".$dash['dd']['indirect_cost']."</td>";
                                        
                                        echo "<td>".number_format((float)($dash['dd']['commit']-$dash['dd']['direct_cost']-$dash['dd']['indirect_cost']), 2, '.', '')."</td>";
                                    echo "</tr>"; 
                               endforeach;
                               ?>
                            </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>