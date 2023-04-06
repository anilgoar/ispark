

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
                    <span>View Test Result</span>
		</div>
            
            <div class="no-move"></div>
            </div>
            <div class="box-content">
		<h4 class="page-header">View Test Result</h4>
                <form method="post">
                <div class="form-group form-horizontal">
                    <label class="col-sm-1 control-label">From </label>
                        <div class="col-sm-2">
                            <input type="text" id="FromDate" name="FromDate" class="form-control" onclick="displayDatePicker('FromDate');" required="" />
                        </div>
                    <label class="col-sm-1 control-label">To </label>
                        <div class="col-sm-2">
                                <input type="text" id="ToDate" name="ToDate" class="form-control" onclick="displayDatePicker('ToDate');" required="" />
                        </div>
                        <div class="col-sm-1">
                        <input type="submit" value="Export" class="btn btn-primary btn-new pull-center" />
                    </div>

                </div>
                    </form>
                <br/>
                <br/>
            </div>
	</div>
        
    </div>
</div>



<?php echo $this->Form->end(); ?>	






