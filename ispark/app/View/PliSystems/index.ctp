<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
  function getWeitage(type){
    $("#weitage_table").hide();
    $("#msgerr").remove();
    var user=$("#selected_user").val();
    var month=$("#month").val();

    if(user ===""){
        $("#selected_user").focus();
        $("#selected_user").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select user.</span>");
        return false;
    }
    else if(month ===""){
        $("#month").focus();
        $("#month").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
    }else{

        if(type ==="Show"){

            console.log(user);
            console.log(type);
            $.post("<?php echo $this->webroot;?>PliSystems/get_approved_pli",{'EmpCode':user,'Month':month}, function(data) {
                if(data !=""){
                    $("#weitage_table").show();
                    $("#weitage_table").html(data);
                }
                else{
                    $("#weitage_table").hide();
                }
            });
        }
        else if(type ==="Export"){

            window.location="<?php echo $this->webroot;?>PliSystems/export_approved_pli?EmpCode="+user+"&Month="+month;  
           
        }

    }

}



</script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
          <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right">
            <a href="#"><i class="fa fa-google-plus"></i></a>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>
<?php echo $this->Form->create('PliSystems',array('action'=>'create_weitage','class'=>'form-horizontal','id'=>'weitageform')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>View Approved Performance-linked incentive</span>
		        </div>
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
		    <div class="no-move"></div>
        </div>
           
            <div class="box-content box-con">
                <span><?php echo $this->Session->flash(); ?></span>
                

                <div class="form-group">
                    <div class="col-sm-3">
                        <label>User</label>
                        <select id="selected_user" name="selected_user" class="form-control">
                            <option value=''>Select</option>
                            <?php 
                                foreach($users as $key => $user){
                                    echo "<option value='".$user['masjclrentry']['EmpCode']."'>".$user['masjclrentry']['EmpName']."</option>";
                                }
                            ?>

                        </select>
                    </div>

                    <div class="col-sm-3">
                        <label>Month</label>
                        <select name="month" id="month" class="form-control" required="">
                            <option value="">Month</option>
                            <option value="All">All</option>
                            <?php
                                $TcurMonth = date('M');
                                if($TcurMonth=='Jan')
                                {?>
                                    <option value="Dec-<?php echo $curYear-1; ?>">Dec-<?php echo $curYear-1;?></option>
                                <?php }
                            ?>
                            <option value="Jan-<?php echo $curYear; ?>">Jan</option>
                            <option value="Feb-<?php echo $curYear; ?>">Feb</option>
                            <option value="Mar-<?php echo $curYear; ?>">Mar</option>
                            <option value="Apr-<?php echo $curYear; ?>">Apr</option>
                            <option value="May-<?php echo $curYear; ?>">May</option>
                            <option value="Jun-<?php echo $curYear; ?>">Jun</option>
                            <option value="Jul-<?php echo $curYear; ?>">Jul</option>
                            <option value="Aug-<?php echo $curYear; ?>">Aug</option>
                            <option value="Sep-<?php echo $curYear; ?>">Sep</option>
                            <option value="Oct-<?php echo $curYear; ?>">Oct</option>
                            <option value="Nov-<?php echo $curYear; ?>">Nov</option>
                            <option value="Dec-<?php echo $curYear; ?>">Dec</option>
                        </select>
                    </div>
                    <div class="col-sm-2" style="margin-top: 15px;">
                        <!-- <input onclick='return window.location="<?php //echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" /> -->
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MjAz"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="getWeitage('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="button" onclick="getWeitage('Show');" value="Show" class="btn pull-right btn-primary btn-new">
                    </div>

                </div>
            </div>
        </div>
    </div>	
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12" id="weitage_table">
        
    </div>	
</div>

<?php echo $this->Form->end(); ?>





                 
