<script>
function getCostCenter(val)
{
    $.post("get_costcenter2",{branch_name:val},function(data)
    {$("#cost_center").html(data);});
}
function getData(val)
{
    $.post("get_po_data",{cost_center:val},function(data)
    {$("#mm").html(data);});
}
</script>

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
                    <span>Search PO</span>
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
                <?php echo $this->Form->create('PoNumber',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group has-feedback">
                <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label'=>false,'options'=>$branch_master,'empty'=>
                    'Select Branch','class'=>'form-control','required'=>true,'onChange'=>'getCostCenter(this.value)')); ?>
                    </div>
                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-3"> 
                        <div id='cost_center'>
                            <?php echo $this->Form->input('cost_center',array('label'=>false,'options'=>'','empty'=>
                            'Select Cost Center','class'=>'form-control','required'=>true,'onChange'=>'getData(this.value)')); ?>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary btn-label-left">&nbsp;&nbsp;&nbsp;<b>Search</b>&nbsp;&nbsp;&nbsp;&nbsp;</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
	</div>
    </div>
</div>
	
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>View & Download PO</span>
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
                <div id="mm">
                    <?php

if(!empty($Data))
{
?>
<table class="table table-hover table-bordered" border="1">
    <tr>
        <th>Sr. No.</th>
        <th>Branch Name</th>
        <th>Cost Center</th>
        <th>Agreement Status</th>
        <th>Amount</th>
        <th>Balance</th>
        <th>Period From</th>
        <th>Period To</th>
        <th>PO Number</th>
        <th>Download</th>
        <th>Edit</th>
    </tr>
    <?php $i =1;
            foreach($Data as $d):
                echo "<tr>";
                   echo "<td>".$i++."</td>";
                   echo "<td>".$d['t2']['branch']."</td>";
                   echo "<td>".$d['t2']['cost_center']."</td>";
                   echo "<td>".$d['0']['Agri_status']."</td>";
                   echo "<td>".$d['t3']['amount']."</td>";
                   echo "<td>".$d['t3']['balAmount']."</td>";
                   echo "<td>".$d['t1']['periodFrom']."</td>";
                   echo "<td>".$d['t1']['periodTo']."</td>";
                   echo "<td>";
                    $arr = explode(',',$d['t1']['poNumber']);
                    foreach($arr as $a){echo $a."<br>";}
                    echo "</td>";
                    echo "<td>";
                    if(!empty($d['t1']['image_upload']))
                    {
                        $arr = explode(',',$d['t1']['image_upload']);
                        foreach($arr as $a) 
                        {
                            echo '<a href="'.$this->webroot.'app/webroot/PO/'.$d['t1']['data_id'].'/'.$a.'">'.$a."</a><br>";
                        }
                    }
                    echo "</td>";
                    echo "<td>";
                    echo $this->Html->link('Edit',array('controller'=>'poNumbers','action'=>'edit','?'=>array('id'=>$d['t1']['data_id']),'full_base' => true));
                    echo "</td>";
                echo "</tr>";
            endforeach;
    ?>
</table>
    
<?php }

?>
                    
                    
                </div>
            </div>
	</div>
    </div>
</div>