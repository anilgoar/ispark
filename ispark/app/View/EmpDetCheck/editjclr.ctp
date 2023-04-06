
<?php //print_r($Jclr);die; ?>

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
        function getData(val)
        {
            var dept1 = $("#JclrDept").val();;
            $.post("get_package",{desgn:val},function(data)
            {$("#mm").html(data);});
             getNetData(val);
             getCTC(val);
        }
        
        function getCTC(val2)
        {
            document.getElementById('CTC').value=val2; 
        }
        function getpackageData(val2)
        {
            
            
            $.post("showpack",{pack:val2},function(data)
            {$("#data").html(data);});
           
        }
        
        function getNetData(val2)
        {
            
            
            $.post("showctc",{desgn:val2},function(data)
            {$("#data12").html(data);});
        }
        
        
        
    function checkNumber(val,evt)
       {
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        
            else{
              
                 return true;    
        }
        }
	
 function Design(val)
  {
      $.post("get_design",{val},function(data){
        $('#tower').html(data);});
  }
  function band(val)
  {
      $.post("get_band",{val},function(data){
        $('#band').html(data);});
  }
function name(){
    //var nam ="";
   if("<?php echo $Jclr['MasJclrMaster']['FatherName']; ?>"==''){
      var nam = "<?php echo $Jclr['MasJclrMaster']['FatherName']; ?>";
  }
  else if("<?php echo $Jclr['MasJclrMaster']['HusbandName']; ?>"==''){
      var nam = "<?php echo $Jclr['MasJclrMaster']['FatherName']; ?>";
  }
  else{
  var nam ="";
  }
       // alert(nam);
          document.getElementById('CustomerNameNew').value=nam;
     }

        </script>
        
<script>
    
    $(document).ready(function(){
        
        var str ='';
            var radioValue = $("input[name='Sw']:checked").val();
            if(radioValue){
               
                str +='<input type="text" name="Father" id ="CustomerNameNew" class="form-control" style="width:202px;" value=""  placeholder="Father Name">';
           
            }
       //alert(str);
        $('#namerel').html(str);
         name();
    });
    
     
    function backpage(){
//location.reload();   
 window.location="<?php echo $this->webroot;?>Masjclrs/newemp";
} 
    
    function Test(val) {
       // alert(nam);
    var str ='';
    if(val=='Husband'){
       str +='<input type="text" name="Husband" id ="CustomerNameNe" class="form-control" style="width:202px;" value=""  placeholder="Husband Name">';
    }
    else if(val=='Father')
    {
    str +='<input type="text" name="Father" id ="CustomerNameNew" class="form-control" style="width:202px;" value=""  placeholder="Father Name">';
        
        }
         document.getElementById('namerel').innerHTML=str;
          name();  
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


<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
<div class="box-header"  >
    <div class="box-name">
        <span>JCLR Entry</span>
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

    <?php echo $this->Form->create('MasJclrMaster',array('class'=>'form-horizontal')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Title*</label>
        <div class="col-sm-3">
            <?php   echo $this->Form->input('Title', array('label'=>false,'class'=>'form-control','style'=>'width:202px;','value'=>$Jclr['MasJclrMaster']['Title'],'options'=>array('MR.'=>'MR.','MS.'=>'MS.','MRS.'=>'MRS.'),'style'=>'width:202px;','required'=>true)); ?>
        </div>
        <label class="col-sm-3 control-label">Employee Type*</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('EmpType', array('label'=>false,'class'=>'form-control','style'=>'width:202px;','value'=>$Jclr['MasJclrMaster']['EmpType'],'options'=>array('OnRoll'=>'OnRoll','Mgmt. Trainee'=>'Mgmt. Trainee'),'style'=>'width:202px;','required'=>true)); ?>
        </div>
    </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Emp Name*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('EmpName', array('label'=>false,'class'=>'form-control','placeholder'=>'Emp Name','value'=>$Jclr['MasJclrMaster']['EmpName'],'style'=>'width:202px;','required'=>true)); ?>
            </div>    
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-2" style="position:relative;left:70px;" > 
            <input type="radio" name="Sw" value="Father" onclick='Test(this.value);'  checked/> <strong>Father</strong>
             <input type="radio" name="Sw" value="Husband" onclick='Test(this.value);'  /> <strong>Husband</strong>
        </div>
           
        <div class="col-sm-3">
            <div id="namerel"></div> 
            
        </div>
        
        
    </div>

    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">DOB*</label>
        <div class="col-sm-3">
           <div class="input-group">
                <?php	echo $this->Form->input('DOB', array('label'=>false,'type'=>'text','id'=>'datepick1','value'=>date_format(date_create($Jclr['MasJclrMaster']['DOB']),'d-M-Y'),'placeholder'=>'DOB','required'=>true,'readonly'=>true)); ?>

                
        </div></div>
        <label class="col-sm-3 control-label">DOJ*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('DOJ', array('label'=>false,'type'=>'text','class'=>'','placeholder'=>'DOJ','value'=>date_format(date_create($Jclr['MasJclrMaster']['DOJ']),'d-M-Y'),'id'=>'datepick','style'=>'width:202px;','required'=>true,'readonly'=>true)); ?>
                
            </div>
        </div>
    </div>
	<div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Gendar*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Gendar', array('label'=>false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['Gendar'],'options'=>array('Male'=>'Male','Female'=>'Female'),'style'=>'width:202px;','required'=>true)); ?>

                
            </div>
        </div>
        <label class="col-sm-3 control-label">Pin Code*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('PinCode', array('label'=>false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['PinCode'],'placeholder'=>'Pin Code','style'=>'width:202px;','onKeyPress'=>'return checkNumber(this.value,event)','required'=>true)); ?>

                
            </div>
        </div>
    </div>
    
    
        <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Address Line 1</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Adrress1', array('type'=>'textarea','label'=>false,'value'=>$Jclr['MasJclrMaster']['Adrress1'],'class'=>'form-control','style'=>'width:202px;')); ?>

               
            </div>
        </div>
        <label class="col-sm-3 control-label">Address Line 2</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Adrress2', array('type'=>'textarea','label'=>false,'value'=>$Jclr['MasJclrMaster']['Adrress2'],'class'=>'form-control','style'=>'width:202px;',)); ?>
                
            </div>
        </div>
    </div>
        
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">City*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('City', array('label'=>false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['City'],'placeholder'=>'City','style'=>'width:202px;','required'=>true)); ?>

                
            </div>
        </div>
        <label class="col-sm-3 control-label">State*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('State', array('label'=>false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['State'],'placeholder'=>'State','style'=>'width:202px;','required'=>true)); ?>
                
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Department*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('Dept',array('label' => false,'options'=>$Depart,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['Dept'],'empty'=>'Select','style'=>'width:202px;','id'=>'finance_year','style'=>'width:202px;','onChange'=>'Design(this.value)')); ?>
                
            </div>    
        </div>
        <label class="col-sm-3 control-label">Desgination*</label>
        <div class="col-sm-3">
            <div class="input-group"><div id="tower">
              <?php echo $this->Form->input('Desgination',array('label' => false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['Desgination'],'style'=>'width:202px;','id'=>'finance_year','readonly'=>true)); ?>
                
                </div> </div>  
        </div>
    </div>
    
	    
    


    
    
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Band*</label>
        <div class="col-sm-3">
            <div class="input-group"><div id="band">
                <?php	echo $this->Form->input('Band', array('label'=>false,'empty'=>'Select','value'=>$Jclr['MasJclrMaster']['Band'],'class'=>'form-control','style'=>'width:202px;','style'=>'width:202px;','required'=>true,'readonly'=>true)); ?>
            </div>
                
            </div>
        </div>
        <label class="col-sm-3 control-label">Package*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div id="data">
                <?php	echo $this->Form->input('Package', array('label'=>false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['Package'],'style'=>'width:202px;','placeholder'=>'Package','required'=>true,'readonly'=>true)); ?>
                     </div>
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">CTC Offered*</label>
        <div class="col-sm-3">
            <div class="input-group"><div id="data1">
                <?php	echo $this->Form->input('CTC', array('label'=>false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['CTC'],'onKeyPress'=>'return checkNumber(this.value,event)','placeholder'=>'CTC','id'=>'CTC','required'=>true)); ?>
                </div>
                
            </div>
        </div>
        <label class="col-sm-3 control-label">Cost Center*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('CostCenter', array('label'=>false,'class'=>'form-control','value'=>$Jclr['MasJclrMaster']['CostCenter'],'style'=>'width:202px;','options'=>$tower1,'empty'=>'Select CostCenter','required'=>true)); ?>
                
            </div>
        </div>
    </div>
     <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Net In Hand*</label>
        <div class="col-sm-3">
            <div class="input-group"><div id="data12">
                <?php	echo $this->Form->input('NetInHand', array('label'=>false,'class'=>'form-control','style'=>'width:202px;','value'=>$Jclr['MasJclrMaster']['NetInHand'],'onKeyPress'=>'return checkNumber(this.value,event)','placeholder'=>'In Hand.','required'=>true)); ?>

                </div>
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