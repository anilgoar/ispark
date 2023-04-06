
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
					<span>Collection Tracking Matrix</span>
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

<?php echo $this->Form->create('CollectionReports', array('class'=>'form-horizontal')); ?>
<div class="box-content">
    <?php echo $this->Session->flash(); ?>
    <table border="2">
        <tr>
            <th style="text-align: center">Category</th>
            <th style="text-align: center">61-90</th>
            <th style="text-align: center">31-60</th>
            <th style="text-align: center">16-30</th>
            <th style="text-align: center">6-15</th>
            <th style="text-align: center">0-5</th>
            <th style="text-align: center">&lt;15</th>
        </tr>
        <?php foreach($data as $scm) { ?>
        <tr>
            <td style="text-align:center;width: 50px;"><?php echo $scm['CollectionTrackingMatrix']['CategoryName']; ?></td>
            <td><input style="text-align: center;width: 50px;" type="text" id="col_61_90_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" name="col_61_90_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" value="<?php echo $scm['CollectionTrackingMatrix']['col_61_90_after_lapsed']; ?>"  /></td>
            <td><input style="text-align: center;width: 50px;" type="text" id="col_31_60_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" name="col_31_60_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" value="<?php echo $scm['CollectionTrackingMatrix']['col_31_60_after_lapsed']; ?>"  /></td>
            <td><input style="text-align: center;width: 50px;" type="text" id="col_16_30_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" name="col_16_30_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" value="<?php echo $scm['CollectionTrackingMatrix']['col_16_30_after_lapsed']; ?>"  /></td>
            <td><input style="text-align: center;width: 50px;" type="text" id="col_6_15_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" name="col_6_15_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" value="<?php echo $scm['CollectionTrackingMatrix']['col_6_15_after_lapsed']; ?>"  /></td>
            <td><input style="text-align: center;width: 50px;" type="text" id="col_0_5_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" name="col_0_5_after_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" value="<?php echo $scm['CollectionTrackingMatrix']['col_0_5_after_lapsed']; ?>"  /></td>
            <td><input style="text-align: center;width: 50px;" type="text" id="col_less_15_before_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" name="col_less_15_before_lapsed<?php echo $scm['CollectionTrackingMatrix']['Id']; ?>" value="<?php echo $scm['CollectionTrackingMatrix']['col_less_15_before_lapsed']; ?>"  /></td>
        </tr>
            <?php
            
            $id_arr[] = $scm['CollectionTrackingMatrix']['Id'];
            
        } ?>
        
    </table>
    
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>					
        <div class="col-sm-2">
            <input type="hidden" id="id_arr" name="id_arr" value="<?php echo implode(',',$id_arr); ?>" />
            <button type="submit" class="btn btn-success btn-label-left" value = "Save"><b>Save</b></button>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>View Collection Reports</span>
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
				<div id="data"></div>           
			</div>
		</div>
	</div>
</div>