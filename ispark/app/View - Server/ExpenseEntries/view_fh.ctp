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
                            <span>Expense Details</span>
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
                <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                    <div class="form-horizontal">
                    <?php echo $this->Form->create(); ?>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <?php echo $this->Form->input('BranchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','value'=>$BranchId,'id'=>'branchId')); ?>
                                    <span class="input-group-addon"><i class="fa fa-group"></i></span>
                                </div>    
                            </div>
                            <label class="col-sm-1 control-label">Year</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <?php echo $this->Form->input('FinanceYear',array('label' => false,'options'=>$financeYearArr,'class'=>'form-control','empty'=>'Select','value'=>$FinanceYear,'id'=>'finance_year')); ?>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
                                </div>    
                            </div>
                            <label class="col-sm-1 control-label">Month</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                   <?php echo $this->Form->input('FinanceMonth',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                                       'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                                       'class'=>'form-control','empty'=>'Select','value'=>$FinanceMonth,'id'=>'finance_month')); ?>

                                 <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
                                </div>   
                            </div>
                            <div class="col-sm-2">
                                <button type='submit' class="btn btn-info" value="Submit">View</button>
                            </div>
                    </div>  
                    <?php echo $this->Form->end(); ?>
                </div>
                <?php echo $this->Form->create('ExpenseEntries',array('action'=>'fh_multi_final_save')); ?>
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                    <thead>
                            <tr>
                                <th>Select</th>
                            <th>S. No.</th>
                            <th>Branch</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Total</th>
                            <th>Expense Head</th>
                            <th>Expense Sub Head</th>
                            <th>Create Date</th>
                            
                            </tr>
                    </thead>
                    <tbody>
                    <?php $i =1; //print_r($data); exit;
                         foreach($data as $exp):
                            echo "<tr>";
                               echo '<td><input type="checkbox" name="check[]" value="'.$exp['em']['Id'].'"></td>';
                                echo "<td>".$i++."</td>";
                                echo "<td align=\"center\">".$this->Html->link($exp['em']['Branch'],array('controller'=>'ExpenseEntries','action'=>'edit_tmp_fh','?'=>array('id'=>$exp['em']['Id'],'qry'=>$qry),'full_base' => true))."</td>";
                                echo "<td>".$exp['em']['FinanceMonth']."</td>";
                                echo "<td>".$exp['em']['FinanceYear']."</td>";
                                echo "<td>".$exp['em']['Amount']."</td>";
                                echo "<td>".$exp['hm']['HeadingDesc']."</td>";
                                echo "<td>".$exp['shm']['SubHeadingDesc']."</td>";
                                echo "<td>".$exp['0']['date']."</td>";
                            echo "</tr>";
                         endforeach;
                                    ?>
                    </tbody>
                    </table>
                <div class="form-group form-horizontal ">
                    <label class="col-sm-4 control-label"></label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="submit" value="Approve" name="submit" class="btn btn-info">
                                </div>    
                            </div>
                </div>
                 <br/>
                <br/>
                <br/>
                 <?php   echo $this->Form->input('qry',array('label'=>FALSE,'type'=>'hidden','value'=>$qry));
                    echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<!--<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>-->