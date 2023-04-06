<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#ResignationDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function showdiv(id){
    $("#"+id).toggle();
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
                    <span>DOCUMENT VALIDATE EMPLOYEE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Session->flash();?>
                <?php echo $this->Form->create('DocumentValidations',array('action'=>'viewdetails','class'=>'form-horizontal')); ?>
                <input type="hidden" name="EJEID" value="<?php echo $data['Masjclrentry']['id'];?>" >
                <input type="hidden" name="OffNo" value="<?php echo $data['Masjclrentry']['OfferNo'];?>" >
                <div class="form-group">
                    <div class="col-sm-8">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:30px;" >SNo</th>
                                    <th style="text-align: center;width:30px;" >&#10004;</th>
                                    <th style="text-align: center;width:100px;">DocumentType</th>
                                    <th style="text-align: center;width:100px;">DocumentImage</th>
                                    <th style="text-align: center;width:50px;">Status</th>
                                    <th style="text-align: center;width:50px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($DocArr as $val){?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td><center><input <?php if($val['Masdocfile']['DocStatus'] =="Reject" || $val['Masdocfile']['DocStatus'] =="Yes"){?>disabled<?php }?> class="checkbox" type="checkbox" value="<?php echo $val['Masdocfile']['Id'];?>" name="check[]"></center></td>
                                    <td style="text-align: center;"><?php echo $val['Masdocfile']['DocType'];?></td>
                                    <td style="text-align: center;">
                                        <img src="<?php echo $this->webroot.'Doc_File/'.$val['Masdocfile']['OfferNo'].'/'.$val['Masdocfile']['filename'];?>" style="width:50px;" >
                                    </td>
                                    <td style="text-align: center;">
                                        <?php 
                                        if($val['Masdocfile']['DocStatus'] ==""){echo "No";}
                                        else if($val['Masdocfile']['DocStatus'] =="Yes"){echo "Yes";}
                                        else if($val['Masdocfile']['DocStatus'] =="Reject"){echo "Reject";}
                                        ?>  
                                    </td>
                                    <td style="text-align: center;">
                                        <a target="_blank" href="<?php echo $this->webroot.'Doc_File/'.$val['Masdocfile']['OfferNo'].'/'.$val['Masdocfile']['filename'];?>">View</a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                    </div>
                </div>
                
                <div class="form-group" >
                    <label class="col-sm-1 control-label" >Remarks</label>
                    <div class="col-sm-7">
                        <textarea id="DocStatusRemark" name="DocStatusRemark" class="form-control"></textarea>
                    </div>
                </div>
                
                <div class="form-group" >
                    <div class="col-sm-8">
                        <input onclick='return window.location="<?php echo $this->webroot;?>DocumentValidations"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit"  value="Reject" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit"  value="Validate" class="btn pull-right btn-primary btn-new" >
                    </div>
                </div>
                    
               
               
                <?php echo $this->Form->end(); ?>
                
                
            </div>
        </div>
    </div>	
</div>



