<?php ?>
<script>
function jclraction(action,Id){
    if(action =="Delete"){
        if(confirm("Are you sure you want to delete this record?")){
            window.location="<?php echo $this->webroot;?>Masjclrs/deletejclr?Id="+Id;
        }
    }
    else if(action =="Add"){
        window.location="<?php echo $this->webroot;?>Masjclrs/newjclr?id="+Id;
    }
    else if(action =="Add"){
        window.location="<?php echo $this->webroot;?>Masjclrs/newjclr?id="+Id;
    }
    else if(action =="Print"){
        window.location="<?php echo $this->webroot;?>app/webroot/appointment/examples/masofferletter.php?id="+Id;
    }
    
   
    
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
                    <span>JCLR ENTRY</span>
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
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;">SrNo</th>
                            <th style="text-align: center;">Offer No</th>
                            <th style="text-align: center;">Employee Name</th>
                            <th style="text-align: center;">Employee Type</th>
                            <th style="text-align: center;">Fathers / Husband</th>
                            <th style="text-align: center;">DOJ</th>
                            <th style="text-align: center;">Designation</th>
                            <!--
                            <th style="text-align: center;">Department</th>
                            <th style="text-align: center;">Band</th>
                            -->
                            <th style="text-align: center;">Offered CTC</th>
                            <th style="text-align: center;">NetInHand</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($OdArr as $val){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['OfferNo'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['EmpType'];?></td>
                        <?php if($val['NewjclrMaster']['ParentType']=="Father"){?>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Father'];?></td>
                        <?php }else if($val['NewjclrMaster']['ParentType']=="Husband"){?>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Husband'];?></td>
                        <?php }?>
                        <td style="text-align: center;"><?php if($val['NewjclrMaster']['DOJ'] !=""){echo date('d M y',strtotime($val['NewjclrMaster']['DOJ']));}?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Desgination'];?></td>
                        <!--
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Dept'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['Band'];?></td>
                        -->
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['CTC'];?></td>
                        <td style="text-align: center;"><?php echo $val['NewjclrMaster']['NetInhand'];?></td>
                        
                        
                        <td style="text-align: center;">
                            <i title="Add" onclick="jclraction('Add','<?php echo $val['NewjclrMaster']['id'];?>');" style="font-size:20px;cursor: pointer;" class="material-icons">library_add</i>
                            <i title="Delete" onclick="jclraction('Delete','<?php echo $val['NewjclrMaster']['id'];?>');" style="font-size:20px;cursor: pointer;" class="material-icons">delete_forever</i>
                            <?php if($val['NewjclrMaster']['EmpType'] =="ONROLL"){?>
                            <i title="Print" onclick="jclraction('Print','<?php echo $val['NewjclrMaster']['id'];?>');" style="font-size:20px;cursor: pointer;" class="material-icons">print</i>
                            <?php }else{?>
                            <i title="Print"  style="font-size:20px;cursor: pointer;" class="material-icons">print</i>
                            <?php }?>
                            
                        </td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table> 
                <?php }else{?>
                <div class="form-group">
                    <div class="col-sm-10">
                       <span>Record Not Found.</span>
                    </div>
                </div>
                <?php }?>
                <div class="form-group">
                    <div class="col-sm-12">
                       <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right " style="margin-left: 5px;" />
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



