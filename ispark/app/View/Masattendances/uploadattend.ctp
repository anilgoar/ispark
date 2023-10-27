<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
        var currentDate = new Date();
    $("#finance_year").datepicker1({
        changeMonth: true,
        changeYear: true,
        'dateTimeFormat':'yyyy-mm-dd',
        maxDate: currentDate
    });
});
$(function () {
    $("#ToDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<?php echo $this->Form->create('Masattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>UPLOAD ATTENDANCE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <?php echo $this->Session->flash(); ?>
		<div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Select Date</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('AttandDate',array('label' => false,'empty'=>'Select','class'=>'form-control','id'=>'finance_year')); ?>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Upload File</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <?php	
                        echo $this->Form->input('file', array('label'=>false,'type' => 'file','required'=>true));
                        ?>
                        </div>    
                    </div>
       
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-3">
                    <input type="checkbox" name="mail" value="mail"><span> Send Mail Also</span>
                    </div></div>
		<div class="form-group">
                    <div class="col-sm-4">
                        <input  onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' style="margin-left:10px;" type="button" name="back" value="Back" class="btn btn-primary btn-new pull-right" />
                        <input  type="submit"  value="submit" class="btn btn-primary btn-new pull-right">
                        
                    </div>
                    
		</div>
            </div>
        </div>
    </div>
</div>
<?php if(!empty($data)){  ?>
<div class="form-group">
                    <div class="col-sm-6">
<table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="width:50px;" >Sr.No</th>
                                                    
							<th style="width:10px;">BioCode</th>
							<th>Name</th>
							
							<th style="width:100px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $i=1;  foreach ($data as $post): ?> 
                                <tr>
                                    	
                                                    <td><?php echo $i++; ?></td>
                                                   
							<td><?php echo $post['Attandence']['BioCode']; ?></td>
							<td><?php echo $post['Attandence']['EmpName']; ?></td>
							
							<td>NIJCLR</td>
                                                        
							
						</tr>
                                                <?php $array[] = $id; endforeach;  ?>
						<?php  ?>
                            </tbody>   
                        </table>
                         </div>
                    
		</div>

<!--
                                <table class = "table table-striped table-hover  responstable"  >     
				
					<tbody>
						<tr class="info" >
                                                    <th>Sr.No</th>
                                                    
							<th>BioCode</th>
							<th>Name</th>
							
							<th>Status</th>
							
						</tr>
						<?php   foreach ($data as $post): ?> 
						
						<tr class="<?php   echo $case[$i%4]; $i++;?>" >
							
                                                    <td><?php echo $i; ?></td>
                                                   
							<td><?php echo $post['Attandence']['BioCode']; ?></td>
							<td><?php echo $post['Attandence']['EmpName']; ?></td>
							
							<td>NIJCLR</td>
                                                        
							
						</tr>
                                                <?php $array[] = $id; endforeach;  ?>
						<?php  ?>
					</tbody>
				</table>
-->
                                        
<?php } ?>