<script>
  function DashboardProcess(branch)
  {
      $.post("Dashboards/get_process",{branch:branch},function(data){
        $('#process').html(data);});
        document.getElementById("dash").innerHTML="";
  }
function costcenter(tower)
  {
      $.post("Dashboards/get_tower",{tower},function(data){
        $('#tower').html(data);});
  } 
  
function get_freeze_data(cost_id)
{
    
    if(cost_id!='')
    {
        $.post("Dashboards/get_freeze_data",
    {cost_id},function(data){
       if(data=='NotFound')
       {
           alert("Please Add Aspirational Target First.");
           $('#Save').prop("disabled",true);
       }
       else
       {
           var Newdata = data.split("##");
           if(Newdata[0]=='')
           {
               $('#DashboardCommitFreeze').val(0);
           }
           else
           {
               $('#DashboardCommitFreeze').val(Newdata[0]);
           }
           if(Newdata[1]=='')
           {
               $('#DashboardDirectCostFreeze').val(0);
           }
           else
           {
               $('#DashboardDirectCostFreeze').val(Newdata[1]);
           }
           if(Newdata[2]=='')
           {
               $('#DashboardIndirectCostFreeze').val(0);
           }
           else
           {
               $('#DashboardIndirectCostFreeze').val(Newdata[2]);
           }
           if(Newdata[3]=='')
           {
               $('#DashboardOS').val(0);
           }
           else
           {
               $('#DashboardOS').val(Newdata[3]);
           }
           if(Newdata[4]=='')
           {
               $('#DashboardFinCost').val(0);
           }
           else
           {
               $('#DashboardFinCost').val(Newdata[4]);
           }
           $('#Save').prop("disabled",false);
           
       }
    });
    }
}
  function DashboardData(process)
  {
        var branch=''; var tower = '';
        try{
            branch = document.getElementById("DashboardBranch").value;
        }
        
        catch(err){}
        try{
            tower = document.getElementById("DashboardBranchProcess").value;
        }
        
        catch(err){}

        $.post("Dashboards/get_dash_data",{process:process,branch:branch},function(data){
        $('#dash').html(data); });
        costcenter(tower);
  }
function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode == 46)
        {            
		return false;
        }
//        else if(val.length>1 &&  (charCode> 47 && charCode<58) || charCode == 8 || charCode == 110 )
//        {
//            if(val.indexOf(".") >= 0 && val.indexOf(".") <= 3 || charCode == 8 ){
//                 
//            }
//            else{
//               alert("please enter the value in Lakhs");
//                 return false; 
//           
//           
//        }
//        }
	return true;
}

function get_ebidta()
{
    
    var cost_center = $('#DashboardCostCenterId').val();
    $.post("Dashboards/get_os",{cost_center},function(data){
        $('#DashboardEVITAFreeze').val(data);
        //
    });
    
    
//    var ev = parseFloat((commit*1.83)/100).toFixed(2); 
//    $('#DashboardEVITA').val(ev);
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
		<!--	<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a> -->
		</div>
	</div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name"><span>Dashboard Actual Entry</span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
            <div class="box-content" style="overflow: auto;"><h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                <?php 
                echo $this->Form->create('Dashboard',array('class'=>'form-horizontal')); 
                    if(!empty($branchName))
                    {$countArr = array('branch'=>'Branch','cost_centerId'=>$tower1,'commit'=>'Commitment Revenue','direct_cost'=>'Direct Cost','indirect_cost'=>'Indirect Cost','EVITA'=>'Finance Cost');}
                    else
                    {
                        $countArr = array('cost_centerId'=>'cost_centerId','commit'=>'Commitment Revenue','direct_cost'=>'Direct Cost','indirect_cost'=>'Indirect Cost','EVITA'=>'Finance Cost');
                    }
                    $count = count($countArr); $float = true;
                    //$count = 2; 
                    $keys = array_keys($countArr); 
                    //print_r($keys); die;
                    $i=0;
                    $flag = true;
                    
                        for(; $i<$count; $i++)
                        {
                            
                            
                            $field = $keys[$i];
                            
                            if($keys[$i]=='branch')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Branch</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'empty'=>'Select','onChange'=>'DashboardProcess(this.value)','required'=>true,'class'=>'form-control'));
                                echo '</div></div>';
                            }
                            else if($keys[$i]=='branch_process')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Tower</label>';
//                                echo '<div class="col-sm-3">';
//                                echo $this->Form->input('aspirational_process',array('label'=>false,'options'=>$process,'empty'=>'Select','required'=>true,'onChange'=>'DashboardData(this.value)','class'=>'form-control','multiple'=>false));
//                                echo '</div>';
                                echo '<div class="col-sm-3"><div id="process">';
                                
                                if(empty($process))
                                {$process='';}
                                echo $this->Form->input('branch_process',array('label'=>false,'options'=>$process,'empty'=>'Select','required'=>true,'onChange'=>'DashboardData(this.value)','class'=>'form-control','multiple'=>false));
                                echo '</div></div></div>';
                            }
                            else if($keys[$i]=='cost_centerId')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Cost Center</label>';
                                echo '<div class="col-sm-3"><div id="tower">';
                                if(empty($tower1))
                                {$tower1='';}
                                echo $this->Form->input('cost_centerId',array('label'=>false,'options'=>$tower1,'empty'=>'Select','required'=>true,'class'=>'form-control','onchange'=>'get_freeze_data(this.value)'));
                                echo '</div></div></div>';
                            }
                            else if($keys[$i]=='date')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">'.$countArr[$keys[$i]].'</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input($field,array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>true,'onClick'=>"displayDatePicker('data[Dashboard][date]');",'class'=>'form-control'));
                                echo '</div></div>';
                            }
                            else if($keys[$i]=='EVITA')
                            {
                                
                                $readonly = false; 
                                if($keys[$i]=='EVITA')
                                {
                                    $req=false; $readonly = true; 
                                }
                                echo '<div class="form-group">';
                                
                                echo '<label class="col-sm-3 control-label">Finance Cost</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input('Fin_Cost',array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>true,'class'=>'form-control','readonly'=>true));
                                echo '</div></div><div class="form-group">';
                                echo '<label class="col-sm-3 control-label">OutStanding As Per Last Month</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input('OS',array('label'=>false,'placeholder'=>'OutStanding','required'=>true,'class'=>'form-control','readonly'=>true));
                                echo '</div></div>';
                            }
                            else
                            {
                                
                                $req=true;
                                if($float)
                                {
                                    $float = false;
                                    echo '<div class="form-group">';
                                    echo '<label class="col-sm-3 control-label"></label>';
                                    echo '<label class="col-sm-1 control-label">Aspirational</label>';
                                    echo '<label class="col-sm-3 control-label">Actual</label>';
                                    echo '</div>';
                                }
                                
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">'.$countArr[$keys[$i]].'</label>';
                                
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input($field.'_freeze',array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>$req,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control','disabled'=>true));
                                echo '</div>';
                                
                                
                                echo '<div class="col-sm-2">';
                                echo $this->Form->input($field,array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>$req,"onKeyPress"=>"return checkNumber(this.value,event)",'onblur'=>"get_ebidta()",'onpaste'=>"return false",'class'=>'form-control','readonly'=>$readonly));
                                echo '</div>';
                                if($flag)
                                {
                                    echo '<label class="col-sm-3 control-label">Amount in Rupees e.g. 3042725</label>';
                                    $flag = false;
                                }
                                echo '</div>'; 
                                
                            }
                        }
                        
                           echo '<div class="form-group">'; 
                           echo '<label class="col-sm-3 control-label">&nbsp;</label>';                            
                           echo '<div class="col-sm-3">';
                           echo "<input type='submit' id='Save' value='Save' class='btn btn-info'>";
                           echo '</div></div>';
                        
                        
                    
		 echo $this->Form->end(); 
                 ?>
            </div>
        </div>
    </div>
</div>

<div id='dash'> 

 </div>