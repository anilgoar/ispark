<?php ?>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

table th,td{text-align: center;font-size: 13px;}

</style>
<script>
    function get_freeze(cost_id,Rev_asp,Rev_act,Rev_bas,Rev_Proc,Dir_Asp,Dir_Act,Dir_Bas,Dir_proc,InDir_Asp,InDir_Act,InDir_Bas,InDir_proc,FinanceMonth,FinanceYear)
    {
        $.post("Targets/save_freeze_data",
        {
            cost_id:cost_id,
            Rev_asp:Rev_asp,
            Rev_act:Rev_act,
            Rev_bas:Rev_bas,
            Rev_Proc:Rev_Proc,
            Dir_Asp:Dir_Asp,
            Dir_Act:Dir_Act,
            Dir_Bas:Dir_Bas,
            Dir_proc:Dir_proc,
            InDir_Asp:InDir_Asp,
            InDir_Act:InDir_Act,
            InDir_Bas:InDir_Bas,
            InDir_proc:InDir_proc,
            FinanceMonth:FinanceMonth,
            FinanceYear:FinanceYear
        },function(data){
        //$('#').html(data);
        alert(data);
    });
    }
    function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) || charCode == 46)
        {            
		return false;
        }
        
	return true;
}

    function save_actual_data()
    {
        var commit = $('#commit').val();
        var direct = $('#direct_cost').val();
        var indirect = $('#indirect_cost').val();
        var id = $('#id').val();
        var type=$('#type').val();
        var msg = "";
        $.post("save_actual_data",
        {
            commit:commit,
            direct:direct,
            indirect:indirect,
            id:id,
            type:type
        },function(data){
        if(data=='commit')
        {
            msg = "Commitment Should Not Empty";
        }
        else if(data=='direct')
        {
            msg = "Direct Cost Should Not Empty";
        }
        else if(data=='indirect')
        {
            msg = "InDirect Cost Should Not Empty";
        }
        else if(data=='id')
        {
            msg = "Server Error. Please Try Again After Some Time";
        }
        else if(data=='OS')
        {
            msg = "Commitment Not Lesst Than OutStanding";
        }
        else if(data=='directBas')
        {
            msg = "Direct Cost Not Less Than Salary Made";
        }
        else if(data=='IndirectBas')
        {
            msg = "InDirect Cost Not Less Than GRN Made";
        }
        else if(data=='NotUpdated')
        {
            msg = "Server Error. Please Try Again After Some Time";
        }
        else if(data=='NotSaved')
        {
            msg = "Server Error. Please Try Again After Some Time";
        }
        else if(data=='Updated')
        {
            msg = "Record Updated Successfully";
            alert(msg);
            //location.reload();
        }
        else if(data=='Saved')
        {
            msg = "Record Saved Successfully";
            alert(msg);
            //location.reload();
        }
        
        $('#msg').html(msg);
        
        
        //alert(data);
    });
        
    }
    
    function save_actual_data1()
    {
        var commit = $('#commit').val();
        var direct = $('#direct_cost').val();
        var indirect = $('#indirect_cost').val();
        var id = $('#id').val();
        var type=$('#type').val();
        var finyear11 = $('#finyear11').val();
        var finmonth11 = $('#finmonth11').val();
        var cost_id11=$('#cost_id11').val();
        
        var msg = "";
        $.post("save_actual_data1",
        {
            commit:commit,
            direct:direct,
            indirect:indirect,
            id:id,
            type:type,
            cost_id:cost_id11,
            finmonth11:finmonth11,
            finyear11:finyear11
            
            
        },function(data){
        if(data=='commit')
        {
            msg = "Commitment Should Not Empty";
        }
        else if(data=='direct')
        {
            msg = "Direct Cost Should Not Empty";
        }
        else if(data=='indirect')
        {
            msg = "InDirect Cost Should Not Empty";
        }
        else if(data=='id')
        {
            msg = "Server Error. Please Try Again After Some Time";
        }
        else if(data=='OS')
        {
            msg = "Commitment Not Lesst Than OutStanding";
        }
        else if(data=='directBas')
        {
            msg = "Direct Cost Not Less Than Salary Made";
        }
        else if(data=='IndirectBas')
        {
            msg = "InDirect Cost Not Less Than GRN Made";
        }
        else if(data=='NotUpdated')
        {
            msg = "Server Error. Please Try Again After Some Time";
        }
        else if(data=='NotSaved')
        {
            msg = "Server Error. Please Try Again After Some Time";
        }
        else if(data=='Updated')
        {
            msg = "Record Updated Successfully";
            alert(msg);
            //location.reload();
        }
        else if(data=='Saved')
        {
            msg = "Record Saved Successfully";
            alert(msg);
            //location.reload();
        }
        
        $('#msg').html(msg);
        
        
        //alert(data);
    });
        
    }
</script>    


<style>
    table td{text-align: center!important;}
    table th{text-align: center!important;text-transform: capitalize!important;}
    
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border: 2px solid #bbb;
}

</style>


<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-right">
			
		</div>
	</div>
</div>

<div class="box-content">
    <h4 class="page-header">Freeze Request</h4>
	<?php echo $this->Form->create('Targets',array('class'=>'form-horizontal', 'url'=>'save_freeze_data1','style'=>"overflow:auto")); ?>			
    <?php echo $this->Session->flash(); ?>
					
    <table border="2" class = "table table-striped table-hover  responstable" >
        <thead style="text-align:center">
        <tr style="text-align:center">
            <th colspan="2" style="text-align:center"><b>Process</b></th>
            <th colspan="4" style="text-align:center"><b>Revenue</b></th>
            <th colspan="4" style="text-align:center"><b>Direct Cost</b></th>
            <th colspan="4" style="text-align:center"><b>Indirect Cost</b></th>
            <th colspan="4" style="text-align:center"><b>OP</b></th>
            <th colspan="4" style="text-align:center"><b>OP%</b></th>
<!--            <th colspan="2" rowspan="2"><b>Status</b></th>-->
        </tr>
        <tr  style="text-align:center;">
            <th><b>Process Name</b></th>
            <th><b>Cost Center</b></th>

            <th><b>Aspi</b></th>
            <th><b>Basic</b></th>
            <th><b>Commit</b></th>
            <th><b>Actual</b></th>
<!--                                        <th><b>Processed</b></th>-->

            <th><b>Aspi</b></th>
            <th><b>Basic</b></th>
            <th><b>Commit</b></th>
            <th><b>Actual</b></th>
<!--                                        <th><b>Processed</b></th>-->

            <th><b>Aspirational</b></th>
            <th><b>Basic</b></th>
            <th><b>Commit</b></th>
            <th><b>Actual</b></th>
<!--                                        <th><b>Processed</b></th>-->
            <th><b>Aspirational</b></th>
            <th><b>Basic</b></th>
            <th><b>Commit</b></th>
            <th><b>Actual</b></th>
<!--                                        <th><b>Processed</b></th>-->
            <th><b>Aspira</b></th>
            <th><b>Basic</b></th>
            <th><b>Commit</b></th>
            <th><b>Actual</b></th>
<!--                                        <th><b>Processed</b></th>-->


        </tr>
        </thead>
<?php
    foreach($CostCenter as $cost_id=>$cost_master)
    {
        echo '<tr style="text-align:center">';
            // CostCenter Process Name and Cost Center
            echo '<td style="text-align:center">';
                echo ''.$cost_master['PrcoessName'].'</a>';
            echo '</td>';
            echo '<td>';
                echo $cost_master['CostCenter'];
            echo '</td>';

            // Revenue Details are here
            echo '<td>';
                $Rev_asp = round($Data[$cost_id]['Asp']['revenue'],2);
                $totalArray['Asp']['revenue'] +=$Rev_asp;
                echo $Rev_asp;
              echo  $this->Form->input($cost_id.'.Rev_Asp',array('type'=>'hidden','value'=>$Rev_asp));
            echo '</td>';
            
            
              //Revenue Details are here  
            echo '<td>';
                $Rev_bas = round($Data[$cost_id]['Basic']['revenue'],2);
                $totalArray['Basic']['revenue'] +=$Rev_bas;
                echo $Rev_bas;
            echo    $this->Form->input($cost_id.'.Rev_Bas',array('type'=>'hidden','value'=>$Rev_bas));
            echo '</td>';
            
            echo '<td>';
                $Rev_com = round($Data[$cost_id]['Commit']['revenue'],2);
                $totalArray['Commit']['revenue'] +=$Rev_com;
                echo $Rev_com;
              echo  $this->Form->input($cost_id.'.Rev_com',array('type'=>'hidden','value'=>$Rev_com));
            echo '</td>';
            
            echo '<td>';
                $Rev_act = round($Data[$cost_id]['Actual']['revenue'],2);
                $totalArray['Actual']['revenue'] +=$Rev_act;
                echo '<a herf="#" onclick="show_details('."'$Rev_act',"."'Revenue','$cost_id','$finYear','$finMonth','revenue'".')">'.$Rev_act.'</a>';
              echo  $this->Form->input($cost_id.'.Rev_Act',array('type'=>'hidden','value'=>$Rev_act));
            echo '</td>';
            
            
            // Direct Cost Details are here
            echo '<td>';
                $Dir_asp = round($Data[$cost_id]['Asp']['dc'],2);
                $totalArray['Asp']['dc'] +=$Dir_asp;
                echo $Dir_asp;
              echo  $this->Form->input($cost_id.'.Dir_Asp',array('type'=>'hidden','value'=>$Dir_asp));
            echo '</td>';

            echo '<td>';
                $Dir_bas = round($Data[$cost_id]['Basic']['dc'],2);
                $totalArray['Basic']['dc'] +=$Dir_bas;
                echo $Dir_bas;
              echo  $this->Form->input($cost_id.'.Dir_Bas',array('type'=>'hidden','value'=>$Dir_bas));
            echo '</td>';
            
            echo '<td>';
                $Dir_com = round($Data[$cost_id]['Actual']['dc'],2);
                $totalArray['Commit']['dc'] +=$Dir_com;
                echo $Dir_com;
               echo $this->Form->input($cost_id.'.Dir_com',array('type'=>'hidden','value'=>$Dir_com));
            echo '</td>';
            
            
            echo '<td>';
                $Dir_act = round($Data[$cost_id]['Actual']['dc'],2);
                $totalArray['Actual']['dc'] +=$Dir_act;
                echo '<a herf="#" onclick="show_details('."'$Dir_act',"."'DirectActual','$cost_id','$finYear','$finMonth','basic'".')">'.$Dir_act.'</a>';
               echo $this->Form->input($cost_id.'.Dir_Act',array('type'=>'hidden','value'=>$Dir_act));
            echo '</td>';

            // InDirect Cost Details are here
            echo '<td>';
                $InDir_asp = round($Data[$cost_id]['Asp']['idc'],2);
                $totalArray['Asp']['idc'] +=$InDir_asp;
                echo $InDir_asp;
               echo $this->Form->input($cost_id.'.InDir_Asp',array('type'=>'hidden','value'=>$InDir_asp));
            echo '</td>';

            

            echo '<td>';
            $InDir_bas = round($Data[$cost_id]['Basic']['idc'],2);
            $totalArray['Basic']['idc'] +=$InDir_bas;
                echo $InDir_bas;
               echo $this->Form->input($cost_id.'.InDir_Bas',array('type'=>'hidden','value'=>$InDir_bas));
            echo '</td>';
            
            echo '<td>';
            $InDir_com = round($Data[$cost_id]['Basic']['idc'],2);
            $totalArray['Commit']['idc'] +=$InDir_com;
                echo $InDir_com;
               echo $this->Form->input($cost_id.'.InDir_com',array('type'=>'hidden','value'=>$InDir_com));
            echo '</td>';
            
            echo '<td>';
                $InDir_act = round($Data[$cost_id]['Actual']['idc'],2);
                $totalArray['Actual']['idc'] +=$InDir_act;
                echo '<a herf="#" onclick="show_details('."'$InDir_act',"."'InDirectBasic','$cost_id','$finYear','$finMonth','basic'".')">'.$InDir_act.'</a>';
               echo $this->Form->input($cost_id.'.InDir_Act',array('type'=>'hidden','value'=>$InDir_act));
            echo '</td>';

//                                                    echo '<td>';
//                                                        $InDir_proc = round($Data[$cost_id]['Processed']['idc'],2);
//                                                        echo $InDir_proc;
//                                                    echo '</td>';

            echo '<td>'.round($Rev_asp-$Dir_asp-$InDir_asp,2).'</td>';
            echo '<td>'.round($Rev_bas-$Dir_bas-$InDir_bas,2).'</td>';
            echo '<td>'.round($Rev_com-$Dir_com-$InDir_com,2).'</td>';
            echo '<td>'.round($Rev_act-$Dir_act-$InDir_act,2).'</td>';
            
            
            echo '<td>'.round(($Rev_asp-$Dir_asp-$InDir_asp)*100/$Rev_asp,2).'%</td>';
            echo '<td>'.round(($Rev_bas-$Dir_bas-$InDir_bas)*100/$Rev_bas,2).'%</td>';
            echo '<td>'.round(($Rev_com-$Dir_com-$InDir_com)*100/$Rev_com,2).'%</td>';
            echo '<td>'.round(($Rev_act-$Dir_act-$InDir_act)*100/$Rev_act,2).'%</td>';

//            echo '<td colspan="2">';
//                echo '<span href="#" class="btn btn-danger"  style=" margin-top: 10px;margin-left: 10px;margin-right: 10px;">Freeze</span>';
//            echo '</td>';

        echo '</tr>';
        
        
        
    }
    
    echo '<tr>';
    echo '<td colspan="2">Total</td>';
    
    echo '<td>'.$totalArray['Asp']['revenue'].'</td>';
    echo '<td>'.$totalArray['Basic']['revenue'].'</td>';
    echo '<td>'.$totalArray['Commit']['revenue'].'</td>';
    echo '<td>'.$totalArray['Actual']['revenue'].'</td>';
    
    echo '<td>'.$totalArray['Asp']['dc'].'</td>';
    echo '<td>'.$totalArray['Basic']['dc'].'</td>';
    echo '<td>'.$totalArray['Commit']['dc'].'</td>';
    echo '<td>'.$totalArray['Actual']['dc'].'</td>';
    
    echo '<td>'.$totalArray['Asp']['idc'].'</td>';
    echo '<td>'.$totalArray['Basic']['idc'].'</td>';
    echo '<td>'.$totalArray['Commit']['idc'].'</td>';
    echo '<td>'.$totalArray['Actual']['idc'].'</td>';
    
    echo '<td>'.round($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Commit']['revenue']-$totalArray['Commit']['dc']-$totalArray['Commit']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'],2).'</td>';


    echo '<td>'.round(($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'])*100/$totalArray['Asp']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'])*100/$totalArray['Basic']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Commit']['revenue']-$totalArray['Commit']['dc']-$totalArray['Commit']['idc'])*100/$totalArray['Commit']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'])*100/$totalArray['Actual']['revenue'],2).'%</td>';
    
    echo '</tr>';
?>
    </table>                            
                                   
    <div class="clearfix"></div>
    <div class="form-group">
        <label class="col-sm-5 control-label"></label>
            <div class="col-sm-2">
                     <a href="<?php echo $this->webroot.'Targets/view_freeze_request_for_approval' ?>" class="btn btn-primary" >Back</a>
            </div>
    </div>		
    <?php 
    echo $this->Form->input('FinanceYear',array('type'=>'hidden','value'=>$finYear));
    echo $this->Form->input('FinanceMonth',array('type'=>'hidden','value'=>$finMonth));
    echo $this->Form->input('Branch',array('type'=>'hidden','value'=>$Branch));
    echo $this->Form->end(); ?>     
    <div class="form-horizontal">
        <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="pop_up_close()">&times;</span>
            <div id="show_table_data"></div>
        </div>
        </div>
    </div>
    
</div>

<script>
function show_details(amt,type,cost_id,finyear,finmonth,type1)
{
    document.getElementById('show_table_data').innerHTML = '';
    if(type=='Revenue')
    {
        var bas=0;var act=0;var asp=0;
        asp = $('#Targets'+cost_id+'RevAsp').val();
        bas = $('#Targets'+cost_id+'RevBas').val();
        act = $('#Targets'+cost_id+'RevAct').val();
        
        $.post("get_actual_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             type:type1,
             bas:bas,
             act:act,
             asp:asp,
             amt:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if( type=='ActualDirect')
    {
        var bas=0;var act=0;var asp=0;
        asp = $('#Targets'+cost_id+'DirAsp').val();
        bas = $('#Targets'+cost_id+'DirBas').val();
        act = $('#Targets'+cost_id+'DirAct').val();
        
        $.post("get_actual_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             type:type1,
             bas:bas,
             act:act,
             asp:asp,
             ActualAmount:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if( type=='ActualInDirect')
    {
        var bas=0;var act=0;var asp=0;
        asp = $('#Targets'+cost_id+'InDirAsp').val();
        bas = $('#Targets'+cost_id+'InDirBas').val();
        act = $('#Targets'+cost_id+'InDirAct').val();
        
        $.post("get_actual_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             type:type1,
             bas:bas,
             act:act,
             asp:asp,
             ActualAmount:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if(type=='DirectActual')
    {
        $.post("get_basic_direct_data1",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             Branch:'<?php echo $Branch;?>',
             ActualAmount:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if(type=='InDirectBasic')
    {
        //alert(finmonth);
        //var Branch=$()
        $.post("get_basic_indirect_data1",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             Branch:'<?php echo $Branch;?>',
             ActualAmount:amt
             
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    var modal = document.getElementById('myModal');
    modal.style.display = "block";
}
    
    
function pop_up_close()
{
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
    location.reload();
}

function get_sum_new_basic()
    {
        var all = $('#allIds').val();
        var strArr = all.split(",");
        var newBasicSum = 0; var newbasval = 0;
        for(var i=0; i<strArr.length; i++)
        {
            newbasval = $('#Targets'+strArr[i]+'Amount').val();
            if(newbasval>=0)
            {
                newBasicSum =parseInt(newBasicSum)+parseInt(newbasval);
            }
        }
        //alert(newBasicSum);
        $('#totalNewBasic').html(newBasicSum);
    }

</script>