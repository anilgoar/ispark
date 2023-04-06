
<style>
    table td{margin: 5px;}
</style>


<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<?php echo $this->Form->create('upload',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Show File</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                
		
       <div class="form-group has-success has-feedback">
                <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('file_year',array('label' => false,'options'=>array('2017'=>'2017','2018'=>'2018','2019'=>'2019'),'class'=>'form-control','empty'=>'Select','id'=>'file_year')); ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php echo $this->Form->input('month',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'month')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    </div>
<?php echo $this->Form->end(); ?>
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                       <input type="submit" class="btn btn-info"  name='export' value="Show" >
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>
<form method="Get"  class="form-horizontal row-border" onsubmit="return getPdf()" target="posthereonly">
<div id="mm">
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active','','info'); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>File Name</th>
							
							
							<th colspan="2">Action</th>
						</tr>
						<?php   foreach ($data as $post):  ?>
						<tr class="<?php  echo $case[$i%2]; $i++;?>" align="center">
							<?php $id= $post['SaveFile']['FIleName']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['SaveFile']['FileName']; ?></td>
							
							
                                                        <td ><a href="<?php echo $this->webroot.'app/webroot/uploads_File/'.$post['SaveFile']['FileName'];?>" download>Download</a></td>
							
						</tr>
                                                <?php $array[] = $id; endforeach; ?>
						<?php  ?>
					</tbody>
				</table>
                          </div>
						
						  </form>
<?php 

echo "<script>closeWin();</script>"
?>

