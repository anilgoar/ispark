
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
                    
                    <span>Invoice View</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
            <div class = "form-horizontal">
		<div class="form-group">
                
                    <label class="col-sm-1 control-label">Company</label>
                    <div class="col-sm-3">
                    <?php	
                        echo $this->Form->input('company_name', array('label'=>false,'class'=>'form-control','options' => array('All'=>'All','Mas Callnet India Pvt Ltd'=>'Mas Callnet India Pvt Ltd','IDC'=>'IDC'),'empty' => 'Select Company','required'=>true));
                    ?>
                    </div>
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                    <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                    ?>
                    </div>        

                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('finance_year', array('label' => false,'options'=>$finance_yearNew,'empty' => 'Finance Year','class'=>'form-control','required'=>true)); ?>
                    </div>
                </div>
		<div class="clearfix"></div>
		<div class="form-group">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-2">
                        <button type="button" value="Export" onclick="getImageExport(this.value)" class="btn btn-primary btn-label-left">Export</button>
                    </div>
<!--                    <div class="col-sm-1">
                        <button type="button" value="export" onclick="getImageExport(this.value)" class="btn btn-primary btn-label-left" >Export</button>
                    </div>-->
		</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Invoice View</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content" >
                <div class="wmd-view"><div id="data" class="scroll-div2"></div></div>
                
            </div>
        </div>
    </div>
</div>
<script>
    function getImageExport(val)
{
var companyName = document.getElementById('company_name').value;
var BranchName = document.getElementById('branch_name').value;
var year = document.getElementById('finance_year').value;

if(companyName == '')
{
    alert("Please Select Company Name");
    return false;
}
else if(BranchName == '')
{
    alert("Please Select Branch Name");
    return false;
}
else if(year == '')
{
    alert("Please Select Finance Year");
    return false;
}

		
var url = 'get_image_export/?BranchName='+BranchName+'&companyName='+companyName+'&year='+year;
if(val == 'show')
{
    
    $.post("get_image_export",
            {
             BranchName: BranchName,
             companyName: companyName,
             year:year
            },
            function(data,status){
                $("#data").html(data);
            }); 
}
else
{
    window.location.href = url;
} 
    return false;
}

$(function(){
    $(".wmd-view-topscroll").scroll(function(){
        $(".wmd-view")
            .scrollLeft($(".wmd-view-topscroll").scrollLeft());
    });
    $(".wmd-view").scroll(function(){
        $(".wmd-view-topscroll")
            .scrollLeft($(".wmd-view").scrollLeft());
    });
});

</script>