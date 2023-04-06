<?php
 $days=5;
    $format = 'd-M-Y';
     $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
        $date=date($format, mktime(0,0,0,$m,($de-$i),$y)); 
       if (date("D", strtotime($date)) == "Sun"){
           $i=$i+1;
    $date=date($format, mktime(0,0,0,$m,($de-$i),$y));
    $days=$days+1;
}
        $dateArray[] = $date; 
    } $dateArray= array_reverse($dateArray);
    //print_r($dashddata);die;
    ?>
<script>
    
    function DashboardProcess(branch,idd)
  { datasow(idd);
      $.post("get_table",{branch:branch},function(data){
        $('#'+idd).html(data);});
       // document.getElementById("dash").innerHTML="";
  }
  
  function colorshow(backid,curid,i)
  {
      var valb=document.getElementById(backid).value; 
      var valc=document.getElementById(curid).value;
   var val1=parseFloat(document.getElementById(backid).value); 
   var val2=parseFloat(document.getElementById(curid).value);
   if(i>0 && val1>val2 && valc!='')
   {
      document.getElementById(curid).style.background='red'; 
      document.getElementById(curid).style.color='white'; 
   }
   else if(valc==''){
       document.getElementById(curid).style.background=''; 
      document.getElementById(curid).style.color='';
   }
   else if(i>0 && val1<val2 && valc!='')
   {
     document.getElementById(curid).style.background='#25B35E'; 
      document.getElementById(curid).style.color='white';   
   }
  }
  function colorshownew(backid,curid,i)
  {
      
      var valb=document.getElementById(backid).value; 
      var valc=document.getElementById(curid).value;
   var val1=parseFloat(document.getElementById(backid).value); 
   var val2=parseFloat(document.getElementById(curid).value);
   //alert(val2);
   if(i>0 && val1<val2 && valc!='')
   {
       //document.getElementById(backid).style.background='green'; 
      document.getElementById(curid).style.background='red'; 
      document.getElementById(curid).style.color='white'; 
   }
   else if(valc==''){
       document.getElementById(curid).style.background=''; 
      document.getElementById(curid).style.color='';
   }
   else if(i>0 && val1>val2 && valc!='')
   {
     document.getElementById(curid).style.background='#25B35E'; 
      document.getElementById(curid).style.color='white';   
   }
  }
  
  function datasow(vs){
      if(document.getElementById(vs).style.display=='none'){
         
        document.getElementById(vs).style.display="block";
      }
else{
     document.getElementById(vs).style.display="none";
}
  }
    function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
       
	return true;
}
</script>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
           
                    
                  <div id='dash'>
                      <?php if($role == 'admin')
                      { ?>
                      
                      
<?php
//print_r($dashddata) ;die;
 $days=5;
    $format = 'd-M-Y';
     $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
       $date=date($format, mktime(0,0,0,$m,($de-$i),$y)); 
       if (date("D", strtotime($date)) == "Sun"){
           $i=$i+1;
    $date=date($format, mktime(0,0,0,$m,($de-$i),$y));
    $days=$days+1;
}
        $dateArray[] = $date; 
    } $dateArray= array_reverse($dateArray);
    //print_r($array);die;
    ?>
                      
<style>
    .tbl{
       font-size:10px; 
      
    }
    .tbl td{
       padding: 0px !important; 
       width: 20px !important; 
    }
    
</style>
<div class="box-header">
                <div class="box-name"><span>Dashboard Entry Details</span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
                    <?php echo $this->Form->create('Dashboards',array('class'=>'form-horizontal','action'=>'get_data')); ?>
<div id="tableWrap" >
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom tbl" id="myTable" border="1">
                	
    <thead>
                              <tr>
                    	<th><strong ></strong><br/></th>
						
						<!--<td><strong style="margin-left:3px;"></strong><br/></td>-->
                                                <?php for($i=0;$i<=4;$i++){ ?>
						<th colspan="6"><strong  ><?php echo $dateArray[$i]; ?></strong><br/></th>
                                                <?php } ?>
                    </tr>  </thead><tbody>
					<tr>
                    	
						<!--<td><strong style="margin-left:3px;">Tower</strong><br/></td>-->
						<td><strong>Branch Name</strong><br/></td>
						   <?php for($i=0;$i<=4;$i++){ ?>
						<td><strong>Rev</strong><br/></td>
                                                <td><strong>DC</strong><br/></td>
                                                <td><strong>IDC</strong><br/></td>
                                                <td><strong >OP</strong><br/></td>
                                                <td><strong >OP%</strong><br/></td>
                                                <td><strong >Finance Cost</strong><br/></td>
						<?php } ?>
                    </tr>
				
                   
  
                      
                   <?php $j=0;
                   $Tcommit=array();
foreach($dashddata as $Fetch)
{//echo $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] ;die;
    
    $j++;
				?>
                  
                    <tr>
                        <td><strong style="margin-left:3px;"><a href="#" onclick="DashboardProcess('<?php echo $Fetch['dp']['cost_center'] ?>','iddiv<?php echo $j;?>')"><?php echo $Fetch['dp']['cost_center'] ?></a></strong><br/></td>
						<!--<td><strong style="margin-left:6px;"><?php echo $Fetch['dp']['cost_center']?><input type="hidden" name="SubCat<?php echo $i;?>" value="<?php echo $Fetch['cost_center']?>" style="width:25px"/></strong><br/></td>-->
						
                                                 <?php for($i=0;$i<=4;$i++){
                                                    if(!empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])){
                                                 $opper =round(100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])),2);
                                                    }
                                                    else{
                                                    $opper='';    
                                                    }
                                                    
                                                     $Tcommit[$dateArray[$i]]=($Tcommit[$dateArray[$i]]+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']);
                                                    $Tdircost[$dateArray[$i]] =($Tdircost[$dateArray[$i]]+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']);
                                                   $Tindir[$dateArray[$i]]= ($Tindir[$dateArray[$i]]+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']);
                                                   $TEVTA[$dateArray[$i]]= ($TEVTA[$dateArray[$i]]+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']);
                                                    ?>
                                                    <?php if($i>0 && !empty((100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])))) && (100-((($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']))) > (100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']))) ){ $colorp ='red';$textcp='white'; } 
                                                    else if((100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])))==0|| $opper=='') { $colorp ='';$textcp='';} 
                                                    else if(!empty((100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])))) && (100-((($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']))) < (100-((($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'])+(($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']*100)/$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']))) ){ $colorp='#25B35E'; $textcp='white';} ?>
                                                     
                                                     <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'])) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']) > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) ){ $coloro ='red';$textco='white'; } 
                                                      else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'])=='') { $coloro ='';$textco='';} 
                                                      else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'])) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost']) < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) ) { $coloro='#25B35E'; $textco='white';} ?>
                                                  <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ){ $color ='red';$textc='white'; } 
                                                  else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']=='') { $color ='';$textc='';} 
                                                  else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ){ $color='#25B35E'; $textc='white';} ?>
                                                <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost'] ){ $color1 ='red'; $textc1='white';} 
                                                else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']=='') { $color1 ='';$textc1='';} 
                                                else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost'] ){  $color1 ='#25B35E'; $textc1='white';}?>
                                                 <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ){ $color2 ='red'; $textc2='white';} 
                                                 else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']=='') { $color2 ='';$textc2='';}
                                                 else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ){$color2 ='#25B35E';$textc2='white';}?>
                        
                        <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['EVITA'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'] ){ $colorVA ='red'; $textVA='white';} 
                                                 else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']=='') { $colorVA ='';$textVA='';}
                                                 else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['EVITA'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'] ){$colorVA ='#25B35E';$textVA='white';}?>
                        
						<td style="color:<?php echo $textc; ?>; background: <?php echo $color;?>" ><?php echo round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'],2) ?></td>
						<td  style="color:<?php echo $textc1; ?>; background: <?php echo $color1;?>" ><?php echo round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost'],2)?></td>
                                                <td style=" color:<?php echo $textc2; ?>;; background: <?php echo $color2;?> "><?php echo round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'],2) ?> </td>
						<td style="color:<?php echo $textco; ?>; background: <?php echo $coloro;?>" ><?php echo round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']-($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']+$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']),2) ?></td>
                                                <td style="color:<?php echo $textcp; ?>; background: <?php echo $colorp;?>" ><?php echo $opper ?></td>
                                                <td style=" color:<?php echo $textVA; ?>;; background: <?php echo $colorVA;?> "><?php echo round($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'],2) ?> </td>
                                                    <?php }  ?></tr>
                    <tr>
                        <td colspan="26">
                            <div id="iddiv<?php echo $j;?>" style="display:none;">
                    
                    
                    
                    </div></td>
                         </tr>

                                              <?php    }  ?>
                         <tr>
                             
                             <td>Grand Total</td>
                               <?php for($i=0;$i<=4;$i++){
                                   
                                    if(!empty($Tcommit[$dateArray[$i]])){
                                                  $Topper =round(100-((($Tdircost[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])+(($Tindir[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])),2);    
                                                    }
                                                    else{
                                                    $Topper='';    
                                                    }
                                                    
                              
                                ?>
                               
                                                      <?php if($i>0 && !empty($Tcommit[$dateArray[$i]]-($Tdircost[$dateArray[$i]]+$Tindir[$dateArray[$i]])) && $Tcommit[$dateArray[$i-1]]-($Tdircost[$dateArray[$i-1]]+$Tindir[$dateArray[$i-1]]) > $Tcommit[$dateArray[$i]]-($Tdircost[$dateArray[$i]]+$Tindir[$dateArray[$i]]) ){ $coloro ='red';$textc='white'; } else if($Tcommit[$dateArray[$i]]-($$Tdircost[$dateArray[$i]]+$Tindir[$dateArray[$i]])=='') { $coloro ='';$textc='';} else if($i>0 && !empty($Tcommit[$dateArray[$i]]-($Tdircost[$dateArray[$i]]+$Tindir[$dateArray[$i]])) && $Tcommit[$dateArray[$i-1]]-($Tdircost[$dateArray[$i-1]]+$Tindir[$dateArray[$i-1]]) < $Tcommit[$dateArray[$i]]-($Tdircost[$dateArray[$i]]+$Tindir[$dateArray[$i]]) ){ $coloro='#25B35E'; $textc='white';} ?>
                                                  <?php if($i>0 && !empty($Tcommit[$dateArray[$i]]) && $Tcommit[$dateArray[$i-1]] > $Tcommit[$dateArray[$i]] ){ $color ='red';$textc='white'; } else if($Tcommit[$dateArray[$i]]=='') { $color ='';$textc='';} else if($i>0 && !empty($Tcommit[$dateArray[$i]]) && $Tcommit[$dateArray[$i-1]] < $Tcommit[$dateArray[$i]] ){ $color='#25B35E'; $textc='white';} ?>
                                                <?php if($i>0 && !empty($Tdircost[$dateArray[$i]]) && $Tdircost[$dateArray[$i-1]] < $Tdircost[$dateArray[$i]] ){ $color1 ='red'; $textc='white';} else if($Tdircost[$dateArray[$i]]=='') { $color1 ='';$textc='';} else if($i>0 && !empty($Tdircost[$dateArray[$i]]) && $Tdircost[$dateArray[$i-1]] > $Tdircost[$dateArray[$i]] ){  $color1 ='#25B35E'; $textc='white';}?>
                                                 <?php if($i>0 && !empty($Tindir[$dateArray[$i]]) && $Tindir[$dateArray[$i-1]] < $Tindir[$dateArray[$i]] ){ $color2 ='red'; $textc='white';} else if($Tindir[$dateArray[$i]]=='') { $color2 ='';$textc='';}else if($i>0 && !empty($Tindir[$dateArray[$i]]) && $Tindir[$dateArray[$i-1]] > $Tindir[$dateArray[$i]] ){$color2 ='#25B35E';$textc='white';}?>
                               <?php if($i>0 && !empty((100-((($Tdircost[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])+(($Tindir[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])))) && (100-((($Tdircost[$dateArray[$i-1]]*100)/$Tcommit[$dateArray[$i-1]])+(($Tindir[$dateArray[$i-1]]*100)/$Tcommit[$dateArray[$i-1]]))) > (100-((($Tdircost[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])+(($Tindir[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]]))) ){ $colorp ='red';$textc='white'; } else if((100-((($Tdircost[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])+(($Tindir[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])))==0|| $Topper=='') { $colorp ='';$textc='';} else if($i>0 && !empty((100-((($Tdircost[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])+(($Tindir[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])))) && (100-((($Tdircost[$dateArray[$i-1]]*100)/$Tcommit[$dateArray[$i-1]])+(($Tindir[$dateArray[$i-1]]*100)/$Tcommit[$dateArray[$i-1]]))) < (100-((($Tdircost[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]])+(($Tindir[$dateArray[$i]]*100)/$Tcommit[$dateArray[$i]]))) ){ $colorp='#25B35E'; $textc='white';} ?>
                              <?php if($i>0 && !empty($TEVTA[$dateArray[$i]]) && $TEVTA[$dateArray[$i-1]] < $TEVTA[$dateArray[$i]] ){ $colorrr ='red'; $textr='white';} else if($TEVTA[$dateArray[$i]]=='') { $colorrr ='';$textr='';}else if($i>0 && !empty($TEVTA[$dateArray[$i]]) && $TEVTA[$dateArray[$i-1]] > $TEVTA[$dateArray[$i]] ){$colorrr ='#25B35E';$textr='white';}?>
                               
                              
                             <td  style="color:<?php echo $textc; ?>; background: <?php echo $color;?>"><?php echo round($Tcommit[$dateArray[$i]],2); ?></td>
                             <td style=" color:<?php echo $textc; ?>;; background: <?php echo $color1;?> "><?php echo round($Tdircost[$dateArray[$i]],2); ?></td>
                             <td style=" color:<?php echo $textc; ?>;; background: <?php echo $color2;?> " ><?php echo round($Tindir[$dateArray[$i]],2); ?></td>
                             <td style=" color:<?php echo $textc; ?>;; background: <?php echo $coloro;?> "><?php echo round($Tcommit[$dateArray[$i]]-($Tdircost[$dateArray[$i]]+$Tindir[$dateArray[$i]]),2); ?></td>
                             <td style=" color:<?php echo $textc; ?>;; background: <?php echo $colorp;?> "><?php echo round($Topper,2); ?></td>
                             <td style=" color:<?php echo $textr; ?>;; background: <?php echo $colorrr;?> " ><?php echo round($TEVTA[$dateArray[$i]],2); ?></td>
                               <?php } ?>
                         </tr>
    </tbody>
</table></div>
                         <input 
                             type="button" name="button" id="button"
  
  value="Export"
>
			
		
			<script type="text/javascript">
//      function tableToExcel(table, name, filename) {
//          try {
//        let uri = 'data:application/vnd.ms-excel;base64,', 
//        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><title></title><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>', 
//        base64 = function(s) { return window.btoa(decodeURIComponent(encodeURIComponent(s))) },         format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; })}
//        
//        if (!table.nodeType) table = document.getElementById(table)
//        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
//
//        var link = document.createElement('a');
//        link.download = filename;
//        link.href = uri + base64(format(template, ctx));
//        link.click();
//    }
//    catch(err){
//    }
//}
$(function(){
    $('#button').click(function(){
        var url='data:application/vnd.ms-excel,' + encodeURIComponent($('#tableWrap').html()) 
        location.href=url
        return false
    })
})
                            </script>		</div>
			
                
                      <?php  echo $this->Form->end(); }   else
                      { ?> 
                      
                      
                      
                      
                      
                      
                      <style>
    .tbl{
       font-size:10px; 
      
    }
    .tbl td{
       padding: 0px !important; 
       width: 20px !important; 
    }
</style>
                      
            <div class="box-header">
                <div class="box-name"><span>Dashboard Entry Details</span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
                    <?php echo $this->Form->create('Dashboards',array('class'=>'form-horizontal','action'=>'get_data')); ?>
                         <table class="table table-striped table-bordered table-hover table-heading no-border-bottom tbl">
                	
				
                              <tr>
                    	<td><strong style="margin-left:3px;background: #436e90;color: white;">Branch Name </strong><br/></td>
					<td  colspan="2"><strong style="margin-left:3px;background: #436e90;color: white;"><?php echo $branchName;?></strong><br/></td>
							
						<!--<td><strong style="margin-left:3px;"></strong><br/></td>-->
                                                <?php for($i=0;$i<=4;$i++){ ?>
						<td colspan="4"><strong style="margin-left:3px;" ><?php echo $dateArray[$i]; ?></strong><br/></td>
                                                <?php } ?>
                    </tr>
					<tr>
                    	
						 <td><strong style="margin-left:3px;">Cost Center Name</strong><br/></td>
						  <td><strong style="margin-left:3px;">Owner</strong><br/></td>
						<td><strong style="margin-left:3px;">Cost Center</strong><br/></td>
                                               
						   <?php for($i=0;$i<=4;$i++){ ?>
						<td><strong style="margin-left:3px;">Rev</strong><br/></td>
                                                <td><strong style="margin-left:3px;">DC</strong><br/></td>
                                                <td><strong style="margin-left:3px;">IDC</strong><br/></td>
                                                <td><strong style="margin-left:3px;">Finance Cost</strong><br/></td>
						<?php } ?>
                    </tr>
				
                   
                        
                        <?php
foreach($dashddata as $Fetch)
{//echo $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] ;die;
				?>
                  
                    	 <tr>
                             <td><strong style="margin-left:6px;"><?php echo $Fetch['dp']['Cname']?></strong><br/></td>
							  <td><strong style="margin-left:6px;"><?php echo $Fetch['dp']['OwnerName']?></strong><br/></td>
                        <td><strong style="margin-left:3px;"><?php echo $Fetch['dp']['cost_center'] ?><input type="hidden" name="Cost<?php echo $i;?>" value="" style="width:25px"/></strong><br/></td>
						
						
                                                 <?php for($i=0;$i<=4;$i++){
                                                    
                                                  //  echo $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'];
                                                      ?>
                                                  <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ){ $color ='red';$textc='white'; } else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']=='') { $color ='';$textc='';} else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['commit'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ){ $color='#25B35E'; $textc='white';} ?>
                                                <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost'] ){ $color1 ='red'; $textc1='white';} else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']=='') { $color1 ='';$textc1='';} else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['direct_cost'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost'] ){  $color1 ='#25B35E'; $textc1='white';}?>
                                                 <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ){ $color2 ='red'; $textc2='white';} else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']=='') { $color2 ='';$textc2='';}else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['indirect_cost'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ){$color2 ='#25B35E';$textc2='white';}?>
                                                 <?php if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['EVITA'] < $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'] ){ $color3 ='red'; $textc3='white';} else if($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']=='') { $color3 ='';$textc3='';}else if($i>0 && !empty($array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']) && $array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['EVITA'] > $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'] ){$color3 ='#25B35E';$textc3='white';}?>
						<td><strong style="margin-left:3px;"><input type="hidden" name="Cost<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'];?>" value="<?php echo $Fetch['dp']['cid'] ?>" style="width:25px"/>
                                                        <input type="hidden" name="id<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'];?>" value="<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id']?>" style="width:25px"/>
                                                        <input type="text" name="comit<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" id="comit<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" value="<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit'] ?>" style="width:35px;color:<?php echo $textc; ?>; background: <?php echo $color;?>"   onKeyPress="return checkNumber(this.value,event);" onblur="colorshow('comit<?php echo $dateArray[$i-1]. $Fetch['dp']['cost_center'].$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['id'];?>','comit<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>','<?php echo $i ?>')"<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']==''?"":"readonly";?>/></strong><br/></td><?php $bMax[$l++] +=$Dat['Ahmedabad']; ?>
                                                <td><strong style="margin-left:3px;"><input type="text" name="dc<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" id="dc<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" value="<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']?>" style="width:35px;color:<?php echo $textc1; ?>; background: <?php echo $color1;?>"  onKeyPress="return checkNumber(this.value,event)" onblur="colorshownew('dc<?php echo $dateArray[$i-1]. $Fetch['dp']['cost_center'].$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['id'];?>','dc<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>','<?php echo $i ?>')"<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['direct_cost']==''?"":"readonly";?>/></strong><br/></td><?php $bMax[$l++] +=$Dat['Delhi']; ?>
                                                <td><strong style="margin-left:3px;"><input type="text" name="ic<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" id="ic<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" value="<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['indirect_cost'] ?>" style="width:35px; color:<?php echo $textc2; ?>;; background: <?php echo $color2;?> "  onKeyPress="return checkNumber(this.value,event)" onblur="colorshownew('ic<?php echo $dateArray[$i-1]. $Fetch['dp']['cost_center'].$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['id'];?>','ic<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>','<?php echo $i ?>')"<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['commit']==''?"":"readonly";?>/></strong><br/></td><?php $bMax[$l++] +=$Dat['Hyderabad']; ?>
                                                <td><strong style="margin-left:3px;"><input type="text" name="EV<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" id="EV<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>" value="<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA'] ?>" style="width:35px; color:<?php echo $textc3; ?>;; background: <?php echo $color3;?> "  onKeyPress="return checkNumber(this.value,event)" onblur="colorshownew('EV<?php echo $dateArray[$i-1]. $Fetch['dp']['cost_center'].$array[$dateArray[$i-1]][$Fetch['dp']['cost_center']]['id'];?>','EV<?php echo $dateArray[$i]. $Fetch['dp']['cost_center'].$array[$dateArray[$i]][$Fetch['dp']['cost_center']]['id'];?>','<?php echo $i ?>')"<?php echo $array[$dateArray[$i]][$Fetch['dp']['cost_center']]['EVITA']==''?"":"readonly";?>/></strong><br/></td><?php $bMax[$l++] +=$Dat['Hyderabad']; ?>
						
<?php }?></tr>

                                              <?php    } ?>
                    
					
			
					
					<tr>
					<td colspan="20" align="right">
					<input type="hidden" name="Cnt" value="<?php echo $i;?>" />
					<input type="submit" name="Save" value="Save" /></td>
					</tr>
			
                </table>
                      <?php  echo $this->Form->end(); } ?>
               
            </div>
        </div>
    </div>
</div>