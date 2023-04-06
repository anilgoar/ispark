

<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		
	</div>
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Paragraph View</span>
		</div>
            
            <div class="no-move"></div>
            </div>
            <div class="box-content">
		<h4 class="page-header"><?php //echo $this->Session->flash(); ?></h4>
                
                <table id="tbl1" class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable">
                    <thead>
                    <tr>
                        <th style="width:300px;">Sr. No.</th>
                        <th>Heading</th>
                        <th>Paragraph</th>
                        <th style="width:110px;">View</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        
                        
                        <?php  $srno = 1; 
                                foreach($record_para as $record)
                                {
                                    echo '<tr>';
                                    echo '<td>'.($srno++).'</td>';
                                    echo '<td>'.$record['tqp']['heading_name'].'</td>';
                                    echo '<td>'.$record['tqp']['paragraph'].'</td>';
                                    echo '<td>'.'<a href="view_para_detail?para_id='.$record['tqp']['id'].'">view</a>'.'</td>';
                                    echo '</tr>';
                                    $total += $record['tqt']['marks'];
                                }
                        ?>
                    </tbody>
                </table>                
            </div>
	</div>
        
    </div>
</div>


<input type="hidden" id="unique_id" name="unique_id" value="<?php echo $quest_unique_id; ?>" />
<?php echo $this->Form->end(); ?>	

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot.'app/webroot/css/'; ?>dist/css/chung-timepicker.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="<?php echo $this->webroot.'app/webroot/css/'; ?>dist/js/chung-timepicker.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
$('#para_time').chungTimePicker({
        callback: function(e) {
                //alert('Callback');
        }
});
</script>
