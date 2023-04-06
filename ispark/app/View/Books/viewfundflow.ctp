<?php ?>




<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>

<div class="box-content">
				<h4 class="page-header">View Fund Flow</h4>
				
					<span style="color:green"><?php echo $this->Session->flash(); ?></span>
					<?php echo $this->Form->create('Books',array('class'=>'form-horizontal', 'action'=>'viewfundflow','enctype'=>'multipart/form-data')); ?>
					<div class="form-group has-success has-feedback">
						
                                                <label class="col-sm-2 control-label">Month</label>
						<div class="col-sm-4">
							<?php 

$c= 1; 
                                                       $month = array(date('Y-m-1', mktime(0, 0, 0,$c, 1))=>date('M-y', mktime(0, 0, 0,$c, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 1, 1))=> date('M-y', mktime(0, 0, 0,$c + 1, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 2, 1))=>date('M-y', mktime(0, 0, 0,$c + 2, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 3, 1))=> date('M-y', mktime(0, 0, 0,$c + 3, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 4, 1))=>date('M-y', mktime(0, 0, 0,$c + 4, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 5, 1))=>date('M-y', mktime(0, 0, 0,$c + 5, 1)),
                date('Y-m-1', mktime(0, 0, 0,$c + 6, 1))=>date('M-y', mktime(0, 0, 0,$c + 6, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 7, 1))=>date('M-y', mktime(0, 0, 0,$c + 7, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 8, 1))=>date('M-y', mktime(0, 0, 0,$c + 8, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 9, 1))=>date('M-y', mktime(0, 0, 0,$c + 9, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 10, 1))=>date('M-y', mktime(0, 0, 0,$c + 10, 1)),
                date('Y-m-1', mktime(0, 0, 0,$c + 11, 1))=>date('M-y', mktime(0, 0, 0,$c + 11, 1)));
                            
                                        echo $this->Form->input('month',array('label' => false,'options'=> $month,'empty' => 'Select Month','class'=>'form-control' ,'required'=>true)); ?>
						</div>
					</div>
                                       
                                        

					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button class="btn btn-info btn-label-left" value = "show" >Show</button>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
                        
                         <div class="row">
                <div id="breadcrumb" class="col-xs-12">
                        <a href="#" class="show-sidebar">
                                <i class="fa fa-bars"></i>
                        </a>
                        <ol class="breadcrumb pull-left">
                        </ol>
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
                            <i class="fa fa-search"></i>
                            <span>View Status Budget</span>
                        </div>
                    <div class="box-icons">
                        <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="expand-link">
                        <i class="fa fa-expand"></i>
                        </a>
                        <a class="close-link">
                        <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <div class="no-move"></div>
                    </div>
                    <div class="box-content">
                        <h4 class="page-header"></h4>
                        <div class="form-horizontal">
                        <div class="form-group">

                            <div class="col-sm-12">
                                <div id="nn">
                                   <?php
                                   //print_r($Data);die;
                            if(!empty($Data))
        {                 
                                ?><div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">
            <tr>
                <th >Date</th>
                        <th >Status</th>     
                       
                       
                        <th >Budget</th>
                        
                       
            </tr>
            <?php
            foreach ($Data as $d)
            {
                echo'<tr>';
            echo "<td>".$d['fundflow']['month']."</td>";
            echo "<td>".$d['fundflow']['Status']."</td>";
            echo "<td>".$d['fundflow']['Budget']."</td>";
           
             echo'</tr>';
            }
         ?>


        </table>
                                </div>

        <?php } ?>
    
  
                                    
                                </div>
                            </div>
                         </div>
        
                         </div>



                    </div>
                </div>
            </div>
        </div>
