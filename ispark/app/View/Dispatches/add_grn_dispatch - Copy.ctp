<?php //print_r($Dispatch); exit; ?>
<script>
$(document).ready(function(){
     
    $("#sendTo").on('click',function(){
        
        var Ids ='';
        $(".grnAll").each(function ()
        {
            if($(this).prop('checked'))
            {
                Ids +=$(this).val()+',';
            }
        })
       
        var EnvelopeName = $('#EnvelopeName').val();
        var BranchSendFrom = $('#BranchId').val();
        $.post("add_grn_packet",
            {
             Ids: Ids,
             EnvelopeName:EnvelopeName
            },
            function(data,status)
            {
                getDispatchGrn(BranchSendFrom,EnvelopeName);
            });
     });
    $("#backTo").on('click',function(){
        
        var Ids ='';
        $(".dispatchAll").each(function ()
        {
            if($(this).prop('checked'))
            {
                Ids +=$(this).val()+',';
            }
        })
       
        var EnvelopeName = $('#EnvelopeName').val();
        var BranchSendFrom = $('#BranchId').val();
        
        $.post("substract_grn_packet",
            {
             Ids: Ids,
             EnvelopeName:EnvelopeName
            },
            function(data,status)
            {
                getDispatchGrn(BranchSendFrom,EnvelopeName);
            });
     });
     
    $("#search1").on('click',function(){
        
        var BranchSendFrom = $('#BranchId').val();
    var GrnNo = $('#GrnNo').val();
    var FinanceYear = $('#FinanceYear').val();
    var FinanceMonth = $('#FinanceMonth').val();
    var HeadId = $('#head').val();
    var SubHeadId = $('#SubHeadId').val();
    
    $.post("get_grn_for_dispatch",
            {
             BranchSendFrom:BranchSendFrom,
             GrnNo:GrnNo,
             FinanceYear:FinanceYear,
             FinanceMonth:FinanceMonth,
             HeadId:HeadId,
             SubHeadId:SubHeadId
            },
            function(data,status)
            {
                $('#grns').html('');
                $('#grns').html(data);
            });
     });
     
});

function checkAllBox(val)
{
    if($("#"+val).prop('checked'))
    $('.'+val).add().prop('checked','checked');
    else
     $('.'+val).add().prop('checked',false);   
}

function getDispatchGrn(BranchSendFrom,EnvelopeName)
{
    $.post("get_grn_for_dispatch",
            {
             BranchSendFrom:BranchSendFrom
            },
            function(data,status)
            {
                $('#grns').html('');
                $('#grns').html(data);
            });
    
    $.post("get_packet_grn",
            {
             EnvelopeName:EnvelopeName
            },
            function(data,status)
            {
                $('#dispatchgrns').html('');
                $('#dispatchgrns').html(data);
            });
            
}

function search()
{
    var BranchSendFrom = $('#BranchId').val();
    var GrnNo = $('#GrnNo').val();
    var FinanceYear = $('#FinanceYear').val();
    var FinanceMonth = $('#FinanceMonth').val();
    var HeadId = $('#head').val();
    var SubHeadId = $('#SubHeadId').val();
    
    $.post("get_grn_for_dispatch",
            {
             BranchSendFrom:BranchSendFrom,
             GrnNo:GrnNo,
             FinanceYear:FinanceYear,
             FinanceMonth:FinanceMonth,
             HeadId:HeadId,
             SubHeadId:SubHeadId
            },
            function(data,status)
            {
                $('#grns').html('');
                $('#grns').html(data);
            });
    
}
</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
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

<?php echo $this->Form->create('Dispatches',array('class'=>'form-horizontal')); ?>
<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">GRN Packet</h4>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">Envelop No/Name</label>
        <label class="col-sm-2 control-label"><?php echo $Dispatch['0']['dm']['EnvelopeName']; ?></label>
        <div class="col-sm-3">
                <?php echo $this->Form->input('EnvelopeName',array('label' => false,'value'=>$Dispatch['0']['dm']['Id'],'type'=>'hidden','id'=>'EnvelopeName')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Branch</label>
        <label class="col-sm-2 control-label"><?php echo $Dispatch['0']['bm']['branch_name']; ?></label>
        <label class="col-sm-3 control-label">GRN No.</label>
        <div class="col-sm-3">
                <?php echo $this->Form->input('GrnNo',array('label' => false,'value'=>'','class'=>'form-control','id'=>'GrnNo')); ?>
        </div>
        <div class="col-sm-3">
                <?php echo $this->Form->input('BranchId',array('label' => false,'value'=>$Dispatch['0']['dm']['BranchSendFrom'],'type'=>'hidden','id'=>'BranchId')); ?>
        </div>
        
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Finance Year</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('FinanceYear',array('label' => false,'options'=>$financeYearArr,
                   'class'=>'form-control','empty'=>'Select','id'=>'FinanceYear')); ?>
             <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
            </div>   
        </div>
        
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Expense HEAD</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('HeadId',array('label' => false,'options'=>$head,
                   'class'=>'form-control','empty'=>'Select','id'=>'head')); ?>
             <span class="input-group-addon"><i class="fa fa-users"></i></span>  
            </div>   
        </div>
        <label class="col-sm-2 control-label">Expense SubHead</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('SubHeadId',array('label' => false,'options'=>'',
                   'class'=>'form-control','empty'=>'Select','id'=>'SubHeadId')); ?>
             <span class="input-group-addon"><i class="fa fa-users"></i></span>  
            </div>   
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-1">
            <div id="search1" class="btn btn-info">Search</div>
        </div>
        <div class="col-sm-1">
        <?php echo   $this->Html->link('Back',array('controller'=>'Dispatches','action'=>'index','full_base' => true),array('class'=>'btn btn-info')); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    
   
</div>

<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Add GRN</h4>
    <div class="form-group">
        <div class="col-sm-6">
            <div id="grns">
                 <?php  
                        if(!empty($Grns))
                        {
                            $i=1;
                            $html = '<table border="2"><tr><th><input type="checkbox" name="grnAll" onclick="checkAllBox(\'grnAll\')" id="grnAll" />Select All</th><th>GRN</th><th>Expense Head</th><th>Expense SubHead</th><th>Amount</th>'
                                    . '</tr>';
                            foreach($Grns as $ro)
                            {
                               $html .= '<tr>';
                                $html .= '<td>'.'<input type="checkbox" name="grns[]" value="'.$ro['em']['Id'].'" class="grnAll"></td>';
                                $html .= '<td>'.$ro['em']['GrnNo'].'</td>';
                                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                                $html .= '<td>'.$ro['em']['Amount'].'</td>';
                               $html .= '</tr>';
                            }
                            $html .='</table>';

                            echo $html;
                        }
                        else
                        {
                            echo 'No Data Found';
                        }
                ?>                
            </div>
        </div>
        <div class="col-sm-1">
            <div class="btn btn-info" id="sendTo">&gt;&gt;</div>
            <br>
            <div class="btn btn-info" id="backTo">&lt;&lt;</div>
        </div>
        <div class="col-sm-5">
            <div id="dispatchgrns">
                <?php  
                        if(!empty($DispatchGrns))
                        {
                            $i=1;
                            $html = '<table border="2"><tr><th><input type="checkbox" name="dispatchAll" onclick="checkAllBox(\'dispatchAll\')" id="dispatchAll" />Select All</th><th>GRN</th><th>Expense Head</th><th>Expense SubHead</th><th>Amount</th>'
                                    . '</tr>';
                            foreach($DispatchGrns as $ro)
                            {
                               $html .= '<tr>';
                                $html .= '<td>'.'<input type="checkbox" name="dispatchs[]" value="'.$ro['em']['Id'].'" class="dispatchAll"></td>';
                                $html .= '<td>'.$ro['em']['GrnNo'].'</td>';
                                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                                $html .= '<td>'.$ro['em']['Amount'].'</td>';
                               $html .= '</tr>';
                            }
                            $html .='</table>';

                            echo $html;
                        }
                        else
                        {
                            echo 'No Data Found';
                        }
                ?>
            </div>
        </div>
    </div>    
    <div class="form-group">
        <div class="col-sm-5"></div>
        <div class="col-sm-2">
        
        </div>
    </div>    
    <div class="clearfix"></div>
</div>
 <?php echo $this->Form->end(); ?>