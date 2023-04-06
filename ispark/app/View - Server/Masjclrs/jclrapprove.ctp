<?php ?>
<script>
$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
});

function downloadReport(){
    window.location="<?php echo $this->webroot;?>branch-wise-attendance-issue-approval-report";  
}

function ReloadPage(){
    window.location="<?php echo $this->webroot;?>Masjclrs/jclrapprove";  
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
                    <span>JCLR APPROVAL</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('Masjclrs',array('action'=>'jclrapprove','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Search</label>
                    <div class="col-sm-2">
                        <input type="text" name="SearchName" autocomplete="off" class="form-control" >
                    </div>
                    <div class="col-sm-1">
                        <?php echo $this->Form->submit('Search', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                    <div>
                        <input type="button" class="btn btn-primary btn-new" value="Reload" onclick="ReloadPage();" >
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new " style="margin-left: 5px;" />
                    </div>
                </div>
                
                
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: left;" >SrNo</th>
                           <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="text-align: center;">Employee Name</th>
                            <th style="text-align: center;">Employee Type</th>
                            <th style="text-align: center;">Fathers/Husband</th>
                            <th style="text-align: center;">DOJ</th>
                            <th style="text-align: center;">Designation</th>
                            <th style="text-align: center;">Department</th>
                            <th style="text-align: center;">Band</th>
                            <th style="text-align: center;">Offered CTC</th>
                            <th style="text-align: center;">NetInHand</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($OdArr as $val){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><center><input class="checkbox"  type="checkbox" value="<?php echo $val['NewjclrMaster']['id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['EmpType'];?></td>
                        <?php if($val['NewjclrMaster']['ParentType']=="Father"){?>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Father'];?></td>
                        <?php }else if($val['NewjclrMaster']['ParentType']=="Husband"){?>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Husband'];?></td>
                        <?php }?>
                        <td style="text-align: center;"><?php if($val['NewjclrMaster']['DOJ'] !=""){echo date('d M y',strtotime($val['NewjclrMaster']['DOJ']));}?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Desgination'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Dept'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Band'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['CTC'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['NetInhand'];?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                       
                        <?php 
                        echo $this->Form->submit('Not Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
                        echo $this->Form->submit('Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));
                        
                        ?>
                    </div>
                </div>
                
                <?php }else{?>
                <div class="form-group">
                    <div class="col-sm-10">
                       <span>Record Not Found.</span>
                    </div>
                </div>
                <?php }?>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



