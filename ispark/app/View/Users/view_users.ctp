<?php //print_r($User); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right">
            <a href="#"><i class="fa fa-google-plus"></i></a>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-user"></i><span>View Users</span>
                </div>
                <!--
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
                -->
            </div>
            <div class="box-content">
                <!--
                <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                -->
                    <table class = "table table-striped table-hover  responstable"  >
                    <?php $case=array('active',''); $i=1; ?>
                    <thead>
                        <tr class="active" align="center">
                            <td>Sr. No.</td>
                            <td>User Name</td>
                            <td>Password</td>
                            <td>Email</td>
                            <td>Branch Name</td>
                            <td>Process Head</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                            foreach($User as $u):
                                echo "<tr>";
                                    echo "<td>".$i++."</td>";
                                    echo "<td>".$u['User']['username']."</td>";
                                    echo "<td>".$u['User']['password']."</td>";
                                    echo "<td>".$u['User']['email']."</td>";
                                    echo "<td>".$u['User']['branch_name']."</td>";
                                    echo "<td>".$u['User']['process_head']."</td>";
                                    $id = $u['User']['id'];
                                    echo "<td>".$this->Html->link('Edit',array('controller'=>'Users','action'=>'edit_users','?'=>array('id'=>$id)))."</td>";
                                echo "</tr>";
                            endforeach;
                    ?>
                    </tbody>    
                    </table>
                
            </div>
        </div>
    </div>
</div>
