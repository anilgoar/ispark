<?php echo $this->Form->create('User',array('action'=>'manage_Access')); ?>
<?php //print_r($res); ?>
<?php
	 $i=0;
	 foreach($pages as $post): 
	 $data[$post['Pages']['id']]=$post['Pages']['page_name'];
	 $i++;
	 endforeach;
	 unset($Pages);
?>

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
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Page Access for Users</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
		<div class="no-move"></div>
            </div>
        <div class="box-content no-padding" style="overflow:auto">
        <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Page Type</th>
                <?php	
                    $j=0;
                        foreach($access as $post):
                            echo "<th>";
                                echo $post['Access']['user_type'];
                            echo "</th>";
                            $j++;
                        endforeach;
                ?>
            </tr>
        </thead>
        <tbody>

                <?php 
                    $j=1;$k=1;
                        while($j<=$i)
                        {
                            echo "<tr>";
                            echo "<td>".$k++."</td>";
                            echo "<td>";
                            echo $data[$j];
                            echo "</td>";

                            foreach($access as $post):
                            echo "<td>";
                                $flag=false;
                                    if(in_array($j,explode(',',$post['Access']['page_access'])))
                                    {$flag=true;}
                                        echo $this->Form->checkbox($post['Access']['id'].".".$j,array('label'=>false,'checked'=>$flag));
                                        echo "</td>";
                            endforeach; 
								
                            echo "</tr>";
                            $j++;
                        } 
                ?>			<!-- End: list_row -->
        </tbody>
    </table>
    </div>
</div>
</div>
</div>
<button type="submit" class="btn btn-success btn-label-left">
	<b>Assign</b>
</button>
<?php echo $this->Form->end(); ?>