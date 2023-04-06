<?php ?>
<script>   
function redirectUrl(){
    $("#msgerror").remove();
    
        window.location="<?php echo $this->webroot;?>Masjclrs";
   
}

function backpage(){
location.reload();   
// window.location="<?php echo $this->webroot;?>Masjclrs";
} 
  function Test() {
//alert('asasas');
    var str ='';
                        var str ='<input type="radio" name="od" required="" onclick="redirectUrl();" value="NewEmp"> New Employee';//<br><input type="radio" name="od" required="" value="Edit"> Edit<br>      
         document.getElementById('namerel').innerHTML=str;
}

function getData()
        {
            var dept1 = $("#JclrDept").val();;
            $.post("get_data",function(data)
            {$("#mm").html(data);});
            
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

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>Upload/Discard Attendance</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <span><?php echo $this->Session->flash();?></span>
                <?php echo $this->Form->create('OdApprovalDisapprovals',array('class'=>'form-horizontal','action'=>'odapproval','id'=>'showDetails')); ?>
                <div class="form-group">
                    <div class="col-sm-3"><div id = 'namerel'>
                        <input type="radio" name="od" required="" value="NewEmployee" onclick='Test();'> New Employee<br>
                        <input type="radio" name="od" required="" value="JCLREntry" onclick='getData();'> JCLR Entry<br>                       
                    </div></div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                         <input   type="button" name="back" value="Back" class="btn btn-primary btn-new pull-left"  onclick="backpage()" />
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
<div id='mm'></div>	
</div>

