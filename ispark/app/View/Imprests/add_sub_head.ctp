<div class="box-content">
				<h4 class="page-header">Add Expense Sub Head</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Imprest',array('class'=>'form-horizontal')); ?>
					<div class="form-group">
                                               <label class="col-sm-2 control-label">Expense Head</label>
						<div class="col-sm-3">
							<?php echo $this->Form->input('HeadingId',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>$head1,'required'=>true)); ?>
						</div>
						<label class="col-sm-3 control-label">Expense Sub Head</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('SubHeadingDesc',array('label' => false,'class'=>'form-control','placeholder'=>'Expense Sub Head Name According To Tally','required'=>true)); ?>
						</div>
                                                   
					</div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Head Type</label>
                                            <div class="col-sm-3">
                                            <?php echo $this->Form->input('HeadType',array('label' => false,'class'=>'form-control','empty'=>'Type','options'=>$HeadType,'required'=>true)); ?>
                                            </div> 
                                            <label class="col-sm-3 control-label">TDS To Be Deducted</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('SubHeadTDSEnabled',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>array('Yes'=>'Yes','No'=>'No'),'onchange'=>"displayTDS(this.value)",'required'=>true)); ?>
                                            </div> 
                                        </div>
                                
                                
                                    <div class="form-group">
                                        <div id="TDSDisplay" style="display: none">
                                            <label class="col-sm-2 control-label">TDS Section</label>
                                            <div class="col-sm-3">
                                                <?php	
                                                    echo $this->Form->input('TDSSection', array('label'=>false,'class'=>'form-control','id'=>'TDSSection','options'=>$TdsMaster,'empty'=>'select','onchange'=>'get_tds(this.value)'));
                                                ?>
                                            </div>
                                            <label class="col-sm-3 control-label">TDS RATE%</label>
                                            <div class="col-sm-4">
                                                    <?php
                                                        echo $this->Form->input('TDS', array('label'=>false,'class'=>'form-control','id'=>'TDS','placeholder'=>"TDS % e.g. 0.00",'value'=>'','readonly'=>true));
                                                    ?>
                                            </div>
                                            
                                         </div>
                                        <label class="col-sm-2 control-label"></label>
                                       
                                        <div class="col-sm-2">
                                            <button type="submit" onclick="return validate_head()" class="btn btn-primary btn-label-left">Submit</button>
                                            <a href="/ispark/Menuisps/sub?AX=NTk=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
                                        </div>
                                    </div>    
					<div class="clearfix"></div>
					
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Expense Head/Sub Head</span>
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
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php  $i=1; ?>
					<thead>
						<tr class="active">
							<td><b>Sr. No.</b></td>
							<td><b>Expense Head</b></td>
                                                        <td><b>Expense Sub Head</b></td>
                                                        <td><b>Expense Head Type</b></td>
                                                        <td><b>TDS Section</b></td>
                                                        <td><b>TDS%</b></td>
							<td><b>Action</b></td>
						</tr>
					</thead>
                                        <tbody>
						<?php foreach ($head as $post): ?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $post['head']['HeadingDesc']; ?></td>
                                                        <td><?php echo $post['subhead']['SubHeadingDesc']; ?></td>
                                                        <td><?php echo $post['subhead']['HeadType']; ?></td>
                                                        <td><?php echo $post['tm']['section']; ?></td>
                                                        <td><?php if(!empty($post['tm']['TDS'])) echo $post['tm']['TDS'].'%'; ?></td>
                                                        <td><code><?php echo $this->Html->link('Edit',array('controller'=>'Imprests','action'=>'add_sub_head_edit','?'=>array('subhead'=>$post['subhead']['SubHeadingId']),'full_base' => true)); ?></code></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
    
    function displayTDS(value)
    {
        if(value=='Yes')
        {
            $('#TDSDisplay').show();
        }
        else
        {
            $('#TDSDisplay').hide();
        }
    }
    
function get_tds(val)
{
    
    $.post("<?php echo $this->webroot;?>/Imprests/get_tds",
            {
             SectionId: val
            },
            function(data,status){
                $("#TDS").val(data);
            });  
}

function validate_head()
{
    var TdsEnabled = $('#ImprestSubHeadTDSEnabled').val();
    var TDSSection = $('#TDSSection').val();
    var TDS = $('#TDS').val();
    if(TdsEnabled=='Yes' && TDSSection=='')
    {
        alert("Please Select TDS Section");
        return false;
    }
    if(TdsEnabled=='Yes' && TDS=='')
    {
        alert("Please Select TDS Section");
        return false;
    }
   return true; 
}
    
</script>
