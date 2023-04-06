<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right"></div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name"><span>P&L Setup </span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
            <div class="box-content" style="overflow: auto;">
                <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                <?php echo $this->Form->create('Pnldetails',array('class'=>'form-horizontal'));  ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Field name</label>
                    <div class="col-sm-6">
                        <?php echo $this->Form->input('Description',array('label'=>false,'placeholder'=>'P&L Field name','required'=>true,'class'=>'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Records Reflected To</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('ForPnlType',array('label'=>false,'id'=>'ForPnlType','options'=>array('Branch'=>'P&L Branch Wise','Process'=>'P&L Process Wise','Both'=>'P&L Both Branch & Process Wise'),'empty'=>'Select','required'=>true,'onchange'=>'validate_pnl(this.value)','class'=>'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Entry Type</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('EntryType',array('label'=>false,'id'=>'AddType','options'=>'','empty'=>'Select','required'=>true,'class'=>'form-control')); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-2">
                        <input type="submit" name="Save" value="Save" class="btn btn-primary">
                    </div>
                </div>        
                        
                    
		<?php echo $this->Form->end();  ?>
            </div>
            <div class="box-content" style="overflow: auto;">
                <table class="table">
                    <tr>
                        <th>Description Field Name</th>
                        <th>Record Reflected To</th>
                        <th>Entry Type</th>
                    </tr>
                    <?php
                            foreach($pnlMaster as $pnl)
                            {
                                echo '<tr>';
                                    echo '<td>'.$pnl['PnlMaster']['Description'].'</td>';
                                    echo '<td>'.$pnl['PnlMaster']['ForPnlType'].'</td>';
                                    echo '<td>'.$pnl['PnlMaster']['EntryType'].'</td>';
                                echo '</tr>';
                            }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
         function validate_pnl(value)
         {
             var options = '<option value="">Select</option>';
             if(value=='Process')
             {
                options += '<option value="Process">Process Wise</option>';
             }
             else if(value=='Branch')
             {
                 options += '<option value="Branch">Branch Wise</option><option value="Process">Process Wise</option>';
             }
             else if(value=='Both')
             {
                 options += '<option value="Process">Process Wise</option>';
             }
             $('#AddType').html(options);
         }
</script>