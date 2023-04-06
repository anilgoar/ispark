<?php //print_r($ExpenseMaster); 

echo $this->Html->script('sample/datetimepicker_css');

echo $this->Form->create('Gms',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
if(!empty($ExpenseEntryMaster['0']))
{
    $readonly = true;
    //echo "<script>$('#GmsParticular').focus();</script>";
}
else
{
    $readonly = false;
}
?>

<div class="row">
<div id="breadcrumb" class="col-xs-12">
    <a href="#" class="show-sidebar">
    <i class="fa fa-bars"></i></a>
    <ol class="breadcrumb pull-left"></ol>
</div>
</div>

<div class="row">
<div class="col-xs-12 col-sm-12">
    <div class="box">
       
        <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
            <h4 class="page-header textClass" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">View GRN Imprest <?php echo $this->Session->flash(); ?></h4>
            <!--
            <h4 class="page-header textClass"><?php echo $this->Session->flash(); ?></h4>
            -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Year</label>
                <div class="col-sm-4">
                    
                    <?php echo $this->Form->input('FinanceYear', array('options' => $finance_yearNew,'empty' => 'Select Year','value'=>$ExpenseEntryMaster['0'],'label' => false,'id'=>'FinanceYear', 'div' => false,'class'=>'form-control','selected' => $ExpenseMaster['1'],'disabled'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">Month</label>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('Month', array('options' => array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                    'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),'empty' => 'Select Month','value'=>$ExpenseEntryMaster[1],'label' => false,'id'=>'FinanceMonth', 'div' => false,'class'=>'form-control','selected' => $ExpenseMaster['1'],'disabled'=>true)); ?>
                </div>        
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Head</label>
                <div class="col-sm-4"> 
                        <?php echo $this->Form->input('HeadId',array('label' => false,'options'=>$head,
                            'class'=>'form-control','empty'=>'Select','id'=>'head','onChange'=>"getSubHeading()",'value'=>$ExpenseEntryMaster[2],'required'=>true,'disabled'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">Sub Head</label>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('SubHeadId',array('label' => false,'options'=>$SubHeading,
                        'class'=>'form-control','empty'=>'Select','id'=>'subHead','value'=>$ExpenseEntryMaster[3],'required'=>true,'disabled'=>true)); ?>
                </div>        
            </div>
                        
            

       
            
        <div class="form-group has-info has-feedback">
            <label class="col-sm-2 control-label">Amount</label>
            <div class="col-sm-4">
                   <?php echo $this->Form->input('Amount',array('label' => false,'value'=>'','placeholder'=>'Amount',
                       'class'=>'form-control','id'=>'Amount','value'=>$ExpenseEntryMaster[7],'onKeypress'=>'return isNumberKey(event)','disabled'=>true)); ?>
            </div>
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-4">
                   <?php echo $this->Form->texArea('description',array('label' => false,'placeholder'=>'Description',
                       'class'=>'form-control','id'=>'description','value'=>$ExpenseEntryMaster[8],'required'=>true,'disabled'=>true)); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Company</label>
            <div class="col-sm-4">
               <?php echo $this->Form->input('CompId',array('label' => false,'options'=>$company_master,
                   'class'=>'form-control','id'=>'CompId','value'=>$ExpenseEntryMaster[11],'required'=>true,'disabled'=>true)); ?>
            </div>
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-4">
                   <?php echo $this->Form->input('EntryStatus',array('label' => false,'options'=>array('Open'=>'Open','Close'=>'Close'),
                       'class'=>'form-control','id'=>'entry_status','value'=>$ExpenseEntryMaster[10],'required'=>true,'disabled'=>true)); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4" style="display: none">
                   <?php echo $this->Form->input('EntryDate',array('label' => false,
                       'class'=>'form-control','placeholder'=>'Date','value'=>$ExpenseEntryMaster[11],'id'=>'entry_date','onclick'=>"javascript:NewCssCal ('entry_date','ddMMyyyy','arrow',false,'24',false,'')",'disabled'=>true,'required'=>true,'disabled'=>true)); ?>
            </div>
        </div>
        
        </div>
    </div>
    </div>	
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
		<h4 class="page-header" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">View Details</h4>
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom ">
                      
                    <tr>
                        <th>Sr. No.</th>
                        <th>Branch</th>
                        <th>Cost Center</th>
                        <th>Details</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Tax </th>
                        <th>Total Amount</th>
                      
                    </tr>
                        <?php if(empty($branchArr)) { $branchArr="";} ; ?>
                    
            
                    <?php  $i = 0; $idx ="";$Tot=0;$Tax = 0; $GTotal = 0; $CheckTotal=0;
                    foreach ($result as $post): 

                        $BranchTotal[$post['teep']['BranchId']] += $post['teep']['Amount'];
                        $CheckTotal += $post['teep']['Total'];
                        $idx.=$post['teep']['Id'].','; ?>
                            <tr <?php   $i++;?>>
                            <td><?php echo $i;?></td>
                            <td><?php echo $post['cm']['Branch'];?></td>
                            <td><?php echo $post['cm']['cost_center'];?></td>
                            <td><?php echo $post['teep']['Particular'];?></td>
                            <td><?php echo $post['teep']['Amount'];?></td>
                            <td><?php echo $post['teep']['Rate'];?></td>
                            <td><?php echo $post['teep']['Tax'];?></td>
                            <td><?php echo $post['teep']['Total'];?></td>
                            
                            </tr>
                    <?php $Tot+=$post['teep']['Amount']; $Tax += $post['teep']['Tax']; $GTotal += $post['teep']['Total'];
                    endforeach;  unset($result); 

                    echo $this->Form->input('checkTotal',array('label'=>false,'value'=>$CheckTotal,'type'=>'hidden','id'=>'checkTotal')); 
                    echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); 
                    if($Tot)
                    {
                    ?>
                            <tr>
                                <th colspan="4">Total</th>
                                <th><?php echo number_format((float)$Tot, 2, '.', ''); ?></th>
                                <th>Tax</th>
                                <th><?php echo number_format((float)$Tax, 2, '.', ''); ?></th>
                                <th><?php echo $GTotal = number_format((float)$GTotal, 2, '.', ''); //$GTotal = (int)($Tot+$Tax); ?></th>
                            </tr>

                    <?php } ?>
                    </table>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <div class="form-group">
                        <div class="col-sm-3" id="image_preview" <?php if(empty($ExpenseEntryMaster['12'])) { echo 'style="display:none"'; }?>>
                            <img id="previewing" src="<?php echo $this->webroot.'app/webroot/GRN/'.$ExpenseEntryMaster['12']; ?>" width="200" height="200" />
                            <br/>
                            <a href="<?php echo $this->webroot.'app/webroot/GRN/'.$ExpenseEntryMaster['12']; ?>">Click Here To View</a>
                        </div>
                        
                    </div>
                <div class="form-group">
                    <div id="BranchWiseTotal" style="display:none"><?php echo json_encode($BranchTotal); ?></div>;
                    <input type="hidden" value="<?php echo $GTotal;?>" name="BranchWiseTotal1" id="BranchWiseTotal1"   />
                    <input type="hidden" value="1" name="gstEnable" id="gstEnable" />
                    <input type="hidden" value="<?php echo $ExpenseId;?>" name="ExpenseId" id="ExpenseId" />
                    <label class="col-sm-4 control-label"></label>
                    <div class="col-sm-2">
                        <a href="<?php echo $this->webroot.'Gms/view_imprest/'; ?>"  class="btn btn-primary pull-right">Back</a>
                    </div>
                    
                </div>
				</div>
			</div>
		</div>
</div>
<?php echo $this->Form->end();?>

<script>
    $(document).ready(function (e) {
        
$(function() {
        $("#GMSPaymentFile").change(function() {
			
			var file = this.files[0];
			var imagefile = file.type;
			var match= ["image/jpeg","image/png","image/jpg"];	
			if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
			{
			$('#previewing').attr('src','noimage.png');
			$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
			return false;
			}
            else
			{
                var reader = new FileReader();	
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }		
        });
    });
	function imageIsLoaded(e) { 
		$("#file").css("color","green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
		$('#previewing').attr('width', '250px');
		$('#previewing').attr('height', '230px');
	};
});
    </script>
    
    
    