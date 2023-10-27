<?php 
echo $this->Html->css('css/mystyle');
?>
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

<div class="row" id='main_container'>
    
    <div class="col-xs-12 col-sm-12" style='margin-bottom:5px;'> 
        
        <div class="box" id="panel_box"  style="height:150px;background-color:white;border:0px;box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.12), 0px 1px 1px 0 rgba(0, 0, 0, 0.12) !important;">
             <div class="box-header">
                <div class="box-name">
					Payroll Rights
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content box-con" style='padding-top:20px;font-size:16px;'>
				
                <div id="status" style="visibility:hidden;"><span id="status_span" style="color:green; display:block;padding-top:5px;padding-bottom:5px;margin-left:200px;">Updated Successfully</span></div>
                <!-- <div class="col-sm-2" style="font-size:16px;">Branch</div>
                <div class="col-sm-2">
                   <?php //echo $this->Form->input('Branch',array('label' => false,'options'=>$branch,'id'=>'Branch','class'=>'form-control','empty'=>'Select','onchange'=>"get_cost_center()",'required'=>true)); ?>
                </div> -->

                <!-- <div class="col-sm-2" style="font-size:16px;">Cost Center</div>
                <div class="col-sm-2">
                <?php //if(empty($cost_center)) { $cost_center='';} echo $this->Form->input('CostCenter',array('label' => false,'id'=>'CostCenter','class'=>'form-control','options'=>$cost_center,'required'=>true)); ?>
                </div> -->

                <div class="col-sm-2" style="font-size:16px;">Users</div>
                <div class="col-sm-2">
                    <select id="selected_user" name="selected_user" class="form-control">
                        <option value='none'>Select</option>
                    <?php 
                        foreach($users as $key => $user){
                            echo "<option value='".$user['tbl_user']['id']."'>".$user['tbl_user']['username']."</option>";
                        }
                    ?>

                    </select>

                </div>
                
                <label class="col-sm-2 control-label">
                    <input type="checkbox" id="all_check" name="all_check" onclick="auto_fill_all()" />&nbsp;&nbsp; Auto Fill  
                </label>
                <div class="col-sm-2"><a href="https://mascallnetnorth.in/ispark/users/view" class="btn btn-primary">Back</a></div>
            </div>
        
        <!-- new content -->
<div class="col-md-12" style="color:#828282;margin-top:20px;visibility:hidden;" id="menu_tree">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">User Right</label>
                                <div class="col-sm-1">
                                    <div class="assign-right" style="height:280px;width: 595px;margin-left:-100px;">
                                        <ol class="user-tree">
                                            <?php echo $UserRight;?>                                    
                                        </ol>
                                    </div>                              
                                </div>
                            </div>
                        </div>
                    </div>
<!-- end new content --> 

        


    




<style>
    .btn-new{
        width:70px;height:40px;font-size:16px;
        margin-top:20px;
    }
label{font-weight:normal;}
</style>
     
        <div class="col-sm-8" id="update_btn" style="visibility:hidden;margin-top:15px;">
            <input type="button" id="update_btn_main" value="Save" onclick="check()" class="btn btn-primary btn-new pull-right">
        </div>
    </div>        
</div>
</div>
<?php //echo "zzzzzzzz ".Router::url( $this->here, true ); ?> 




<script>

function auto_fill_all()
{
    //$("#main_container input:checkbox").prop("checked", true);
    //$('#3').prop('checked', true);
    if(document.getElementById('all_check').checked == true)
    {
        $("#main_container input:checkbox").prop("checked", true);
    }
    else
    {
        $("#main_container input:checkbox").prop("checked", false);
        var sel = $('#selected_user').val();
            $("#status").css({"visibility": "hidden"});
        if(sel=="none"){
            
            $("#panel_box").css({"height": "150",});
            $("#menu_tree").css({"visibility": "hidden",});
            $("#update_btn").css({"visibility": "hidden"});console.log("hidden");
        }else{
        $("#main_container input:checkbox").prop("checked", false); 
        
            $.getJSON("<?php echo 'https://'.$_SERVER['SERVER_NAME'].Router::url('/AccesCostCenterWise/show_data'); ?>", {user: $('#selected_user').val()}, function (data) {
            if(Object.keys(data).length === 0)
            {
               // $("#main_container input:checkbox").prop("checked", true);
                
            }
            else
            {
                
            $.each(data, function (key, val) {
                $.each(val, function (key1, val1) { 
                    var acces = val1.access;
                    var p_acces = val1.parent_access;
                    var acces_arr = acces.split(',');
                    $.each(acces_arr, function (index, value) {
                        
                        $('#3').prop('checked', true);
                        
                    });
                    
                    var p_acces_arr = p_acces.split(',');
                    $.each(p_acces_arr, function (index, value) { 
                        $('#3').prop('checked', true);
                        
                    });
                    
                }); 

            });
            
        }
        });
                    $("#status_span").html("Updated Successfully");
                    $("#menu_tree").css({"visibility": "visible"});
                    $("#update_btn").css({"visibility": "visible"});
                    $("#panel_box").css({"height": "500px",});                    
    }
        
        
        
    }
}


function show_child(id)
{
    if(document.getElementById(id).checked == true)
    {
        $("#a"+id+" input:checkbox").prop("checked", true);
    }
    else
    {
        $("#a"+id+" input:checkbox").prop("checked", false);
    }
}


    $('#selected_user').change(function () {
            var sel = $('#selected_user').val();
            $("#status").css({"visibility": "hidden"});
        if(sel=="none"){
            
            $("#panel_box").css({"height": "150",});
            $("#menu_tree").css({"visibility": "hidden",});
            $("#update_btn").css({"visibility": "hidden"});console.log("hidden");
        }else{
        $("#main_container input:checkbox").prop("checked", false); 

        
        $.getJSON("<?php echo 'https://'.$_SERVER['SERVER_NAME'].Router::url('/AccesCostCenterWise/show_data'); ?>", {user: $('#selected_user').val()}, function (data) {
            
            if(Object.keys(data).length === 0)
            {
               // $("#main_container input:checkbox").prop("checked", true);
                
            }
            else
            {
                //console.log('data');
                
                
                
            $.each(data, function (keys, val) {
                $.each(val, function (key, val1) { 
                    $('#' + key).prop('checked', true);
                   
                    $.each(val1, function (key1, value2) {
                        
                        $('#' + key1).prop('checked', true);
                        $.each(value2, function (key2, value3) { 
                            $('#' + key2).prop('checked', true);
                            $.each(value3, function (key3, value) { 
                        $('#' + key3).prop('checked', true);
                            });
                    });
                    });
                    
                    
                    
                    
                }); 

            });
            
        }
        });
                    $("#status_span").html("Updated Successfully");
                    $("#menu_tree").css({"visibility": "visible"});
                    $("#update_btn").css({"visibility": "visible"});
                    $("#panel_box").css({"height": "500px",});                    
    }});   
    
     
    
    
    function check() {
        var sel = $('#selected_user').val();
        if(sel=="none"){
            $("#status").css({"visibility": "visible"});
            $("#status_span").html("Please Select User");
        }else{
        
        var branch = "";
        var costcenter = "";
        var page = "";
        // $('input:checked').each(function () {
        //     ride = ride + $(this).val() + ",";

        // });
        $("input[name='branch[]']:checked").each( function () {
            //alert( $(this).val() );
            branch = branch + $(this).val() + ",";
        });

        $("input[name='cost_center[]']:checked").each( function () {
            //alert( $(this).val() );
            costcenter = costcenter + $(this).val() + ",";
        });

        $("input[name='page[]']:checked").each( function () {
            //alert( $(this).val() );
            page = page + $(this).val() + ",";
        });

        branch = branch.slice(0, branch.length - 1); 
        costcenter = costcenter.slice(0, costcenter.length - 1);
        page = page.slice(0, page.length - 1);
        
        
        $.post("<?php echo 'https://'.$_SERVER['SERVER_NAME'].Router::url('/AccesCostCenterWise/save_ride'); ?>", {branchs: branch,costcenters: costcenter,pages: page,user: $('#selected_user').val()}, function (data) {
            console.log(data);
            if (data = "save") {
                $("#main_container input:checkbox").prop("checked", false);
                $("#status").css({"visibility": "visible"});
                $('#selected_user').val("none");
            }
        });
        }
    }
    

</script>
