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
                <?php echo $this->Form->create('Masjclrs',array('class'=>'form-horizontal')); ?>
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: left;" ><input type="checkbox" id="select_all"/> Check All</th>
                            <th>OfferLatterNo.</th>
                            <th>Bio Code</th>
                            <th>Emp Name</th>
                            <th>Branch</th>
                            <th>DOJ</th>
                            <th>Father Name</th>
                            <th>Designation</th>
                            <th>EmpLocation</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($OdArr as $val){?>
                    <tr>
                        <td><input class="checkbox" type="checkbox" value="<?php echo $val['Masjclrentry']['id'];?>" name="check[]"></span></td>
                        <td><?php echo $val['Masjclrentry']['OfferNo'];?></td>
                        <td><?php echo $val['Masjclrentry']['BioCode'];?></td>
                        <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                        <td><?php echo $val['Masjclrentry']['BranchName'];?></td>
                        <td><?php echo date('d M y',strtotime($val['Masjclrentry']['DOJ'])) ;?></td>
                        <td><?php echo $val['Masjclrentry']['FatherName'];?></td>
                        <td><?php echo $val['Masjclrentry']['Designation'];?></td>
                        <td><?php echo $val['Masjclrentry']['EmpLocation'];?></td>
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



