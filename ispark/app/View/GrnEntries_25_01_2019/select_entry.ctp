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
                            <span>Grn Entry</span>
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
                <form name="sdf" method="post" class="form-horizontal">
<!--                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                            <input type="radio" name="selectEntry" value="Imprest">Imprest
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"> </label>
                        <div class="col-sm-6">
                            <input type="radio" name="selectEntry" value="Vendor" checked="">Vendor
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"> </label>
                        <div class="col-sm-6">
                            <input type="radio" name="selectEntry" value="Salary">Salary
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-4">
                            <button type="submit" name="submit" value="submit" class="btn btn-info">
                                Proceed
                            </button>
                        </div>
                    </div>
                    
                    
                </form>    
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>