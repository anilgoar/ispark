<?php ?>

<script>
  function get_det(GrnNo)
  {
      $.post("get_grn",{GrnNo:GrnNo},function(data){
        $('#disp_detail').html(data);});
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

<div class="box-content">
    <h4 class="page-header">Delete GRN Request Approval</h4>
				
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Gms',array('class'=>'form-horizontal')); ?>
    <table border="2">
        <tr>
            <th> S. No.<th>
            <th> Grn No.<th>
            <th>GRN Type<th>
            <th>GRN Delete Remarks<th>    
            <th>Finance Year<th>
            <th>Finance Month<th>
            <th>Head<th>
            <th>Sub Head<th>
            <th>Amount<th>
            <th>Description<th>
            <th>Delete Request By<th>
        </tr>
        <?php
                foreach($delete_arr as $data)
                {
                    echo "<tr>";
                    echo '<td>'.'<input type="checkbox" name="checkAll[]" value="'.$data['edl']['Id'].'" />'.'<td>';
                    echo '<td>'.$data['eem']['GrnNo'].'<td>';
                    echo '<td>'.$data['eem']['ExpenseEntryType'].'<td>';
                    echo '<td>'.$data['edl']['Remarks'].'<td>';
                    echo '<td>'.$data['eem']['FinanceYear'].'<td>';
                    echo '<td>'.$data['eem']['FinanceMonth'].'<td>';
                    echo '<td>'.$data['head']['HeadingDesc'].'<td>';
                    echo '<td>'.$data['subhead']['SubHeadingDesc'].'<td>';
                    echo '<td>'.$data['eem']['Amount'].'<td>';
                    echo '<td>'.$data['eem']['Description'].'<td>';
                    echo '<td>'.$data['tu']['username'].'<td>';
                    echo "</tr>";
                }
        ?>
    </table>
        
        
        
    
    <div id="disp_detail" style="overflow:auto"></div>
	<div class="clearfix"></div>
        <?php if(!empty($delete_arr)) {
        ?>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
                <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary btn-label-left">
                                Delete Request 
                        </button>
                    <a href="/ispark/Menuisps/sub?AX=NjA=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
                </div>
        </div>
        <?php } ?>
<?php echo $this->Form->end(); ?>
</div>
