
<?php ?>

<style>
    .hasDatepicker{
        border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    width: 200px;
    }
</style>

<script language="javascript">
    $(function () {
    $("#datepick").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#datepick1").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<script>
//    function checkdate(){
//        var date1 = new Date('7/11/2010');
//var date2 = new Date('12/12/2001');
//var diffDays = date2 - date1; 
//alert(diffDays)
//    }
//        function getData(val)
//        {
//            var dept1 = $("#JclrDept").val();;
//            $.post("Masjclrs/get_package",{desgn:val},function(data)
//            {$("#mm").html(data);});
//             getNetData(val);
//             getCTC(val);
//        }
//        
//        function getCTC(val2)
//        {
//            document.getElementById('CTC').value=val2; 
//        }
//        function getpackageData(val2)
//        {
//            
//            
//            $.post("Masjclrs/showpack",{pack:val2},function(data)
//            {$("#data").html(data);});
//           
//        }
//        
//        function getNetData(val2)
//        {
//            
//            
//            $.post("Masjclrs/showctc",{desgn:val2},function(data)
//            {$("#data12").html(data);});
//        }
//        
//        
//        
//    function checkNumber(val,evt)
//       {
//
//    
//    var charCode = (evt.which) ? evt.which : event.keyCode
//	
//	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
//        {            
//		return false;
//        }
//        
//            else{
//              
//                 return true; 
//           
//           
//        }
//        }
//	
// function Design(val)
//  {
//      $.post("Masjclrs/get_design",{val},function(data){
//        $('#tower').html(data);});
//  }
//  function band(val)
//  {
//      $.post("Masjclrs/get_band",{val},function(data){
//        $('#band').html(data);});
//  }

function get_cost_center(branchId)
{
     $.post("EmpDetCheck/get_cost_center",{branchId},function(data){
        $('#data').html(data);});
}
        </script>
        
<script>
    
//    $(document).ready(function(){
//        var str ='';
//            var radioValue = $("input[name='Sw']:checked").val();
//            if(radioValue){
//                str +='<input type="text" name="Father" id ="CustomerNameNew" class="form-control" style="width:202px;" value=""  placeholder="Father Name">';
//            }
//       //alert(str);
//        $('#namerel').html(str);
//    });
    
    
    function backpage(){
//location.reload();   
 window.location="<?php echo $this->webroot;?>Masjclrs/newemp";
} 
    
//    function Test(val) {
//    var str ='';
//    if(val=='Husband'){
//       str +='<input type="text" name="Husband" id ="CustomerNameNew" class="form-control" style="width:202px;" value=""  placeholder="Husband Name">';
//    }
//    else if(val=='Father')
//    {
//    str +='<input type="text" name="Father" id ="CustomerNameNew" class="form-control" style="width:202px;" value=""  placeholder="Father Name">';
//           
//        }
//         document.getElementById('namerel').innerHTML=str;
//}
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
<div class="box-header"  >
    <div class="box-name">
        <span>Maker & Checker</span>
    </div>
    <div class="box-icons">
        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        <a class="expand-link"><i class="fa fa-expand"></i></a>
        <a class="close-link"><i class="fa fa-times"></i></a>
    </div>
    <div class="no-move"></div>
</div>
<div class="box-content box-con" >
    <span><?php echo $this->Session->flash();?></span>

    <?php echo $this->Form->create('EmpDetCheck',array('class'=>'form-horizontal')); ?>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('branch', array('label'=>false,'class'=>'form-control','options'=>$bm,'empty'=>'Select','style'=>'width:202px;','required'=>true,'onchange'=>"get_cost_center(this.value)")); ?>
            </div>
        </div>
        <label class="col-sm-1 control-label">Cost Center</label>
        <div class="col-sm-2">
            <div class="input-group">
                <?php	echo $this->Form->input('cost_center', array('label'=>false,'class'=>'form-control','options'=>'','empty'=>'Select','style'=>'width:202px;','required'=>true)); ?>
            </div>
        </div>
        <label class="col-sm-2 control-label">Search Type</label>
        <div class="col-sm-1">
            <div class="input-group">
                <?php	echo $this->Form->input('search_type', array('label'=>false,'class'=>'form-control','empty'=>'select','options'=>array('Employee Code'=>'Employee Code','Employee Name'=>'Employee Name'),'style'=>'width:202px;','required'=>true)); ?>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="input-group">
                <?php	echo $this->Form->input('search', array('label'=>false,'class'=>'form-control','placeholder'=>'Details','style'=>'width:202px;','required'=>true)); ?>
            </div>
        </div>
        
    </div>

    
    <div class="form-group has-info has-feedback">
        <div class="col-sm-2">
            <input type='submit' class="btn btn-info btn-new pull-right" value="Save"></div>
            <div class="col-sm-1">
             <input   type="button" name="back" value="Back" class="btn btn-primary btn-new pull-right"  onclick="backpage()" />
        </div>
    </div>
    <div id="mm"></div>
    <div class="clearfix"></div>
   
    <?php echo $this->Form->end(); ?>
    
</div>
      </div>
    </div>	
</div>
  
 <?php echo $this->Html->css('jquery-ui'); 
 
 echo $this->Html->script('jquery-ui');
 ?>