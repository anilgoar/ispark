

<div class="box-content">
				<h4 class="page-header">Edit Expense Sub Head</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Imprest',array('class'=>'form-horizontal')); ?>
					<div class="form-group">
                                               <label class="col-sm-2 control-label">Expense Head</label>
						<div class="col-sm-3">
							<?php echo $this->Form->input('HeadingId',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>$head1,
                                                            'value'=>$subhead['Tbl_bgt_expensesubheadingmaster']['HeadingId'],'required'=>true)); ?>
						</div>
						<label class="col-sm-3 control-label">Expense Sub Head</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('SubHeadingDesc',array('label' => false,'class'=>'form-control','value'=>$subhead['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'],'placeholder'=>'Expense Sub Head Name According To Tally','required'=>true)); ?>
						</div>
                                                   
					</div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Head Type</label>
                                            <div class="col-sm-3">
                                            <?php echo $this->Form->input('HeadType',array('label' => false,'class'=>'form-control','empty'=>'Type','value'=>$subhead['Tbl_bgt_expensesubheadingmaster']['HeadType'],'options'=>$HeadType,'required'=>true)); ?>
                                            </div> 
                                            <label class="col-sm-3 control-label">TDS To Be Deducted</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('SubHeadTDSEnabled',
                                                    array('label' => false,'class'=>'form-control','empty'=>'Select',
                                                        'value'=>$subhead['Tbl_bgt_expensesubheadingmaster']['SubHeadTDSEnabled'],'options'=>array('Yes'=>'Yes','No'=>'No'),'onchange'=>"displayTDS(this.value)",'required'=>true)); ?>
                                            </div> 
                                        </div>
                                
                                
                                    <div class="form-group">
                                        <div id="TDSDisplay" >
                                            <label class="col-sm-2 control-label">TDS Section</label>
                                            <div class="col-sm-3">
                                                <?php	
                                                    echo $this->Form->input('TDSSection', array('label'=>false,'class'=>'form-control',
                                                        'value'=>$subhead['Tbl_bgt_expensesubheadingmaster']['SubHeadTdsSection'],
                                                        'id'=>'TDSSection','options'=>$TdsMaster,'empty'=>'select','onchange'=>'get_tds(this.value)'));
                                                ?>
                                            </div>
                                            <label class="col-sm-3 control-label">TDS RATE%</label>
                                            <div class="col-sm-4">
                                                    <?php
                                                        echo $this->Form->input('TDS', array('label'=>false,
                                                            'value'=>$subhead['Tbl_bgt_expensesubheadingmaster']['SubHeadTds'],
                                                            'class'=>'form-control','id'=>'TDS','placeholder'=>"TDS % e.g. 0.00",'readonly'=>true));
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
                                        <input type="hidden" name="subheadid" value="<?php echo $subhead['Tbl_bgt_expensesubheadingmaster']['SubHeadingId']; ?>" />
				<?php echo $this->Form->end(); ?>
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
