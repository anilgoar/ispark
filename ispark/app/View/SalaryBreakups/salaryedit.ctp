
<?php ?>

<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
<script>
    function Design(val){
      $.post("Masmasters/get_design",{val},function(data){
        $('#tower').html(data);});
  }
    
    
    function Test(val,Id) {
    var str ='';
    if(val=='AMT'){
        str ='';
    }
    else if(val=='per')
    {
    str +='<input style="width:57px;" type="text" name='+"IN"+Id+' id ='+"IN"+Id+' value="" oninput='+"convenceValue"+Id+'(this.value); class="" placeholder="" onKeyPress="return checkNumber(this.value,event)">';
           
        }
         document.getElementById(Id).innerHTML=str;
}
function convenceValueNameShow(val)
{
    //alert(val);
  var Basic=  document.getElementById('Basic').value;
   //alert(Basic);
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('Conveyance').value =parseInt( amt);
        grossAmount(amt);
        }
}

function convenceValueNameShow1(val)
{
    
  var Basic=  document.getElementById('Basic').value;
 
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('Portfolio').value =parseInt( amt);
  grossAmount(amt);
}}
function convenceValueNameShow2(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('Medical').value =parseInt( amt);
  grossAmount(amt);
    }
    }
    function convenceValueNameShow3(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('Special').value =parseInt( amt);
  grossAmount(amt);
    }
    }
     function convenceValueNameShow4(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('OtherAllow').value =parseInt( amt);
  grossAmount(amt);
    }}
    function convenceValueNameShow5(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('HRA').value =parseInt( amt);
  grossAmount(amt);
    }
    }
     function convenceValueNameShow6(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('Bonus').value =parseInt( amt);
   var  gross = parseInt(amt)+parseInt(Basic);
  document.getElementById('Gross').value = parseInt(gross);
  grossAmount(amt);
    }}
     function convenceValueNameShow7(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('PLI').value =parseInt( amt);
   var  gross = parseInt(amt)+parseInt(Basic);
  document.getElementById('Gross').value = parseInt(gross);
  grossAmount(amt);
    }}
    
   function convenceValueNameShow8(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('EPF').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntepf(amt);
    }}
    function convenceValueNameShow9(val)
{
     var Gross=  document.getElementById('Gross').value;
   if(Gross==''){
   alert("Please Enter Gross The Amount."); 
   document.getElementById('Gross').focus();
		return false;

        }
        else{
  var amt  = Gross*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('ESIC').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntesic(amt);
    }}
    
    function convenceValueNameShow10(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('Professional').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntProfessional(amt);
    }}
    function convenceValueNameShow11(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('EPFCO').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntEPFCO(amt);
    }}
    function convenceValueNameShow12(val)
{
     var Gross=  document.getElementById('Gross').value;
   if(Gross==''){
   alert("Please Enter Gross The Amount."); 
   document.getElementById('Gross').focus();
		return false;

        }
        else{
  var amt  = Gross*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('ESICCO').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntESICCO(amt);
    }}
    function convenceValueNameShow13(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*parseFloat(val)/100;
 //alert(amt);
  document.getElementById('Admin').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntAdmin(amt);
    }}
    
</script>
<script>
 function grossAmount(){
     
     var Basic = parseInt(document.getElementById('Basic').value);
     if(Basic == '')
     {
       alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;
     }
     else
     {
      
  
  if(document.getElementById('Conveyance').value=='')
     {
         var Conveyance =0;
     }
     else
     {
      var Conveyance= parseInt( document.getElementById('Conveyance').value);
  }
 
  if(document.getElementById('Medical').value=='')
     {
         var Medical =0;
     }
     else
     {
       var Medical= parseInt( document.getElementById('Medical').value);
   }
   if(document.getElementById('Portfolio').value=='')
     {
         var Portfolio =0;
     }
     else
     {
        var Portfolio= parseInt( document.getElementById('Portfolio').value);
    }
     if(document.getElementById('Special').value=='')
     {
         var Special =0;
     }
     else
     {
       var Special= parseInt( document.getElementById('Special').value);
   }
   if(document.getElementById('OtherAllow').value=='')
     {
         var OtherAllow =0;
     }
     else
     {
        var OtherAllow= parseInt( document.getElementById('OtherAllow').value);
    }
    
    
    
     if(document.getElementById('Bonus').value=='')
     {
         var Bonus =0;
     }
     else
     {
         var Bonus= parseInt( document.getElementById('Bonus').value);
     }
     
    
    
     if(document.getElementById('HRA').value=='')
     {
         var HRA =0;
     }
     else
     {
          var HRA= parseInt( document.getElementById('HRA').value);
      }
      if(document.getElementById('PLI').value=='')
     {
         var PLI =0;
     }
     else
     {
           var PLI= parseInt( document.getElementById('PLI').value);
       }
      // alert(PLI);
      //var amt1 = parseInt(document.getElementById('Conveyance').value );
      
      
      
    var  gross = Basic+PLI+HRA+Bonus+OtherAllow+Special+Portfolio+Medical+Conveyance;
     //alert(gross);
    //document.getElementById('CntGross').value = gross;
    
    
    
    var epf = Math.round(Basic*12/100);
    var epfco =  Math.round(Basic*12/100);
    var admin =   Math.round(Basic*1/100);
    
    
    var radioValue = $("input[name='Bper']:checked").val();
    var bonusprval = $("#INNameShow6").val();
    var Bonus1 =    Math.round(Basic*8.33/100);
    
    
    if(radioValue =='per' && bonusprval !=""){
        var Bonus1 =   Bonus;
    }
    

    if(gross <= 21000)
    {
        var esico = Math.round(gross*3.25/100);
    var ecsic =  Math.round(gross*0.75/100);
    }
    else
    {
        var esico =0;
      var ecsic =0;  
    }//alert(ecsic);
    
     
    if(document.getElementById('Professional').value=='')
    {
      var prof=0;   
    }
    else
    {
    var prof = parseInt(document.getElementById('Professional').value);
    }
    
    
    var ctc = gross+epfco+esico+admin;
    var netinhand = gross-epf-ecsic-prof;
    
    
    
    <?php if($data['EmpType'] !="MGMT. TRAINEE"){ ?>
    document.getElementById('Gross').value = parseInt(gross);
    document.getElementById('EPF').value = parseInt(epf);
    document.getElementById('ESIC').value = parseInt(ecsic);
    document.getElementById('NetInHand').value = parseInt(netinhand);
     document.getElementById('EPFCO').value = parseInt(epfco);
      document.getElementById('ESICCO').value = parseInt(esico);
       document.getElementById('Admin').value = parseInt(admin);
        document.getElementById('CTC').value = parseInt(ctc);
        document.getElementById('Bonus').value = parseInt(Bonus1);
        
        
        
    <?php }else{?>
    document.getElementById('Gross').value = parseInt(Basic);
    document.getElementById('NetInHand').value = parseInt(Basic);  
    document.getElementById('CTC').value = parseInt(Basic);
     <?php }?>
         
    }
    }
function cntepf(val){
    if(val ==''){
        val=0;
    }
 if(document.getElementById('Professional').value==''){
         var Professional =0;
    }
    else{
    var Professional = parseInt( document.getElementById('Professional').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('ESIC').value==''){
        var ESIC  =0;
     }
     else{
   var ESIC  =parseInt( document.getElementById('ESIC').value);
     }
   var netinhand = Gross -(parseInt(val)+ESIC+Professional);
    document.getElementById('NetInHand').value = parseInt(netinhand);
}
function cntesic(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('Professional').value==''){
         var Professional =0;
    }
    else{
    var Professional = parseInt( document.getElementById('Professional').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('EPF').value ==''){
        var ESIC  =0;
     }
     else{
   var ESIC  =parseInt( document.getElementById('EPF').value);
     }
     
   var netinhand = Gross -(parseInt(val)+ESIC+Professional);
    document.getElementById('NetInHand').value = parseInt(netinhand);
}

function cntProfessional(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('ESIC').value==''){
         var Professional =0;
    }
    else{
    var Professional = parseInt( document.getElementById('ESIC').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('EPF').value ==''){
        var ESIC  =0;
     }
     else{
   var ESIC  =parseInt( document.getElementById('EPF').value);
     }
     
   var netinhand = Gross -(parseInt(val)+ESIC+Professional);
    document.getElementById('NetInHand').value = parseInt(netinhand);
}
function cntEPFCO(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('ESICCO').value==''){
         var ESICCO =0;
    }
    else{
    var ESICCO = parseInt( document.getElementById('ESICCO').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('Admin').value ==''){
        var Admin  =0;
     }
     else{
   var Admin  =parseInt( document.getElementById('Admin').value);
     }
     
   var netinhand = Gross +(parseInt(val)+Admin+ESICCO);
    document.getElementById('CTC').value = parseInt(netinhand);
}

function cntESICCO(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('EPFCO').value==''){
         var ESICCO =0;
    }
    else{
    var ESICCO = parseInt( document.getElementById('EPFCO').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('Admin').value ==''){
        var Admin  =0;
     }
     else{
   var Admin  =parseInt( document.getElementById('Admin').value);
     }
     
   var netinhand = Gross +(parseInt(val)+Admin+ESICCO);
    document.getElementById('CTC').value = parseInt(netinhand);
}

function cntAdmin(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('EPFCO').value==''){
         var ESICCO =0;
    }
    else{
    var ESICCO = parseInt( document.getElementById('EPFCO').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('ESICCO').value ==''){
        var Admin  =0;
     }
     else{
   var Admin  =parseInt(document.getElementById('ESICCO').value);
     }
     
   var netinhand = Gross +(parseInt(val)+Admin+ESICCO);
    document.getElementById('CTC').value = parseInt(netinhand);
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
	
</script>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-con">
<div class="box-content" >
    <?php echo $this->Session->flash(); ?>

    <?php echo $this->Form->create('SalaryBreakups',array('class'=>'form-horizontal')); ?>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">Band</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2">
            <div class="input-group">
                <?php	echo $this->Form->input('id', array('label'=>false,'type'=>'hidden','value'=>isset($data['id'])?$data['id']:'')); ?>
                <?php	echo $this->Form->input('Band', array('label'=>false,'id'=>'Band','value'=>isset($data['Band'])?$data['Band']:'','options'=>$BandList,'required'=>true,'empty'=>'Select Band','style'=>'width:125px;','onchange'=>'Design(this.value)')); ?>
            </div>    
        </div>
        <div class="col-sm-1"></div>
        <!--
        <label class="col-sm-1 control-label">PackageAmount</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2">
            <div class="input-group">
                <?php	echo $this->Form->input('PackageAmount', array('label'=>false,'placeholder'=>'Package','value'=>isset($data['package'])?$data['package']:'','id'=>'PackageAmount','required'=>true,'onKeyPress'=>'return checkNumber(this.value,event)')); ?>
            </div>    
        </div>
        -->
         </div>

    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">Basic</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2">
            <div class="input-group">
                <?php echo $this->Form->input('Basic',array('label' => false,'value'=>isset($data['bs'])?$data['bs']:'','placeholder'=>'Basic','id'=>'Basic', 'oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?> 
            </div>    
        </div>
        <div class="col-sm-1"></div>
        <label class="col-sm-1 control-label">Conveyance</label>
        <div class="col-sm-2" style="position:relative;left:21px;" >
            <input type="radio" name="per" value="AMT" onclick='Test(this.value,"NameShow");' checked="" />AMT
        
            <input type="radio" name="per" value="per" onclick='Test(this.value,"NameShow");' />%
        </div>
        <div class="col-sm-2" >
           <?php echo $this->Form->input('Conveyance',array('label' => false,'value'=>isset($data['conv'])?$data['conv']:'','id'=>'Conveyance','placeholder'=>'Conveyance' ,'oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?> 
        </div>
        <div class="col-sm-1" id="NameShow" ></div>
         </div>

    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">Portfolio</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="portper" value="AMT" onclick='Test(this.value,"NameShow1");' o checked="" />AMT
        <input type="radio" name="portper" value="per" onclick='Test(this.value,"NameShow1");' />%</div>
                
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Portfolio', array('label'=>false,'value'=>isset($data['portf'])?$data['portf']:'','type'=>'text','placeholder'=>'Portfolio','id'=>'Portfolio','oninput'=>'grossAmount(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>      
        </div>
        <div class="col-sm-1" id="NameShow1" ></div>  
    
        <label class="col-sm-1 control-label">MedicalAllowance	</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="Mtper" value="AMT" onclick='Test(this.value,"NameShow2");' checked="" />AMT
        <input type="radio" name="Mtper" value="per" onclick='Test(this.value,"NameShow2");' />%</div>
        <input type="hidden" name="CntGross" id="CntGross" value="" >
                
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Medical', array('label'=>false,'value'=>isset($data['ma'])?$data['ma']:'','placeholder'=>'Medical Allowance','id'=>'Medical','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow2" ></div> 
        </div>
    
   
    <div class="form-group has-info has-feedback">
        
        <label class="col-sm-1 control-label">SpecialAllowance</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="SAper" value="AMT" onclick='Test(this.value,"NameShow3");' checked="" />AMT
        <input type="radio" name="SAper" value="per" onclick='Test(this.value,"NameShow3");' />%</div>     
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Special', array('label'=>false,'value'=>isset($data['sa'])?$data['sa']:'','placeholder'=>'Special Allowance','id'=>'Special','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow3" ></div>   
    
        <label class="col-sm-1 control-label">OtherAllowance</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="Oper" value="AMT" onclick='Test(this.value,"NameShow4");' checked="" />AMT
        <input type="radio" name="Oper" value="per" onclick='Test(this.value,"NameShow4");' />%</div>
        <div class="col-sm-2" >
            <?php echo $this->Form->input('OtherAllow', array('label'=>false,'value'=>isset($data['oa'])?$data['oa']:'','placeholder'=>'Other Allowence','id'=>'OtherAllow','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow4" ></div>
        </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">HRA</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="Hper" value="AMT" onclick='Test(this.value,"NameShow5");' checked="" />AMT
        <input type="radio" name="Hper" value="per" onclick='Test(this.value,"NameShow5");' />%</div>  
        <div class="col-sm-2" >
            <?php echo $this->Form->input('HRA', array('label'=>false,'value'=>isset($data['hra'])?$data['hra']:'','placeholder'=>'HRA','required'=>true,'id'=>'HRA','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
        <div class="col-sm-1" id="NameShow5" ></div>
    
        <label class="col-sm-1 control-label">Bonus</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="Bper" value="AMT" onclick='Test(this.value,"NameShow6");' checked="" />AMT
        <input type="radio" name="Bper" value="per" onclick='Test(this.value,"NameShow6");' />%</div>
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Bonus', array('label'=>false,'value'=>isset($data['Bonus'])?$data['Bonus']:'','placeholder'=>'Bonus','id'=>'Bonus','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
        <div class="col-sm-1" id="NameShow6" ></div>
        </div>

    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">PLI</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="PLIper" value="AMT" onclick='Test(this.value,"NameShow7");' checked="" /> AMT
        <input type="radio" name="PLIper" value="per" onclick='Test(this.value,"NameShow7");' /> % </div>    
        <div class="col-sm-2" >
            <?php echo $this->Form->input('PLI', array('label'=>false,'value'=>isset($data['PLI'])?$data['PLI']:'','placeholder'=>'PLI','id'=>'PLI','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow7" ></div>
  
       
        <label class="col-sm-1 control-label">Gross(Rs.)</label>
         <div class="col-sm-2" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Gross', array('label'=>false,'value'=>isset($data['Gross'])?$data['Gross']:'','placeholder'=>'Gross','required'=>true,'id'=>'Gross','onKeyPress'=>'return checkNumber(this.value,event)')); ?> 
        </div>
         <div class="col-sm-1"></div>
          </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">EPF</label>
        
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="EPFper" value="AMT" onclick='Test(this.value,"NameShow8");' checked="" />AMT
        <input type="radio" name="EPFper" value="per" onclick='Test(this.value,"NameShow8");' />%</div>  
        <div class="col-sm-2" >
            <?php echo $this->Form->input('EPF', array('label'=>false,'value'=>isset($data['EPF'])?$data['EPF']:'','placeholder'=>'EPF','id'=>'EPF','oninput'=>'cntepf(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow8" ></div> 
    
        <label class="col-sm-1 control-label">ESIC</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="ESICper" value="AMT" onclick='Test(this.value,"NameShow9");'  checked="" />AMT
        <input type="radio" name="ESICper" value="per" onclick='Test(this.value,"NameShow9");' />%</div>   
        <div class="col-sm-2" >
            <?php echo $this->Form->input('ESIC', array('label'=>false,'value'=>isset($data['ESIC'])?$data['ESIC']:'','placeholder'=>'ESIC','required'=>true,'oninput'=>'cntesic(this.value);','id'=>'ESIC','onKeyPress'=>'return checkNumber(this.value,event)')); ?>    
        </div>
        <div class="col-sm-1" id="NameShow9" ></div>
        </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">ProfessionalTax</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="PTIper" value="AMT" onclick='Test(this.value,"NameShow10");' checked="" />AMT
        <input type="radio" name="PTIper" value="per" onclick='Test(this.value,"NameShow10");' />%</div>   
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Professional', array('label'=>false,'value'=>isset($data['ProfessionalTax'])?$data['ProfessionalTax']:'','placeholder'=>'Professional Tax.','id'=>'Professional','oninput'=>'cntesic(this.value);','oninput'=>'cntProfessional(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow10" ></div>
    
        <label class="col-sm-1 control-label">NetInHand(Rs.)</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2" >
            <?php echo $this->Form->input('NetInHand', array('label'=>false,'value'=>isset($data['NetInhand'])?$data['NetInhand']:'','placeholder'=>'Net In Hand','required'=>true,'id'=>'NetInHand','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
        <div class="col-sm-1"></div>
        </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">EPF CO</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="EPFCper" value="AMT" onclick='Test(this.value,"NameShow11");' checked="" />AMT
        <input type="radio" name="EPFCper" value="per" onclick='Test(this.value,"NameShow11");' />%</div>    
        <div class="col-sm-2" >
            <?php echo $this->Form->input('EPFCO', array('label'=>false,'value'=>isset($data['EPFCO'])?$data['EPFCO']:'','placeholder'=>'EPF Co.','id'=>'EPFCO','required'=>true,'oninput'=>'cntEPFCO(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow11" ></div>
     
        <label class="col-sm-1 control-label">ESIC CO</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="ESICper" value="AMT" onclick='Test(this.value,"NameShow12");' checked="" />AMT
        <input type="radio" name="ESICper" value="per" onclick='Test(this.value,"NameShow12");' />%</div>     
        <div class="col-sm-2" >
            <?php echo $this->Form->input('ESICCO', array('label'=>false,'value'=>isset($data['ESICCO'])?$data['ESICCO']:'','placeholder'=>'ESIC CO','id'=>'ESICCO','required'=>true,'oninput'=>'cntESICCO(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>      
        </div>
        <div class="col-sm-1" id="NameShow12" ></div>
        </div>
    
    
    <div class="form-group has-info has-feedback">   
        <label class="col-sm-1 control-label">AdminCharges</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ><input type="radio" name="ADper" value="AMT" onclick='Test(this.value,"NameShow13");' checked="" />AMT
        <input type="radio" name="ADper" value="per" onclick='Test(this.value,"NameShow13");' />%</div>      
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Admin', array('label'=>false,'value'=>isset($data['AdminCharges'])?$data['AdminCharges']:'','placeholder'=>'Admin Charges.','id'=>'Admin','required'=>true,'oninput'=>'cntAdmin(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow13" style="position:relative;right:0px;"></div>
    
        <label class="col-sm-1 control-label">CTC</label>
        <div class="col-sm-2" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2" >
            <?php echo $this->Form->input('CTC', array('label'=>false,'value'=>isset($data['CTC'])?$data['CTC']:'','placeholder'=>'CTC','id'=>'CTC','required'=>true,'onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
    </div>
    
    <div class="form-group has-info has-feedback">
        <div class="col-sm-6" id="msg" ></div>
        
        <div class="col-sm-4" >
            <input type='button' onclick="return submitForm(this.form,'<?php echo $this->webroot;?>SalaryBreakups/update_salary')" class="btn btn-info btn-new pull-right" value="Update">
        </div>
    </div>
    <!--
    <div class="form-group" id="tower" style="position: relative;top:-40px;" >
      -->
    </div>
   
    <div class="clearfix"></div>
    
    </div>
    
    
   
    <?php echo $this->Form->end(); ?>
    
    
    
</div>
</div>
    </div>	
</div>

