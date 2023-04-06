<?php ?>
<script>
function goBack(){
    window.location="<?php echo $_SERVER['HTTP_REFERER'];?>";  
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
                    <span>LOAN APPROVE BM</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
                <?php echo $this->Form->create('LoanMasters',array('class'=>'form-horizontal','action'=>'approvebm','id'=>'showDetails')); ?>
                <?php if(!empty($data)){ ?>
                <table class = "table table-striped table-hover  responstable" >    
                    <thead>
                        <tr>
                           <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="width:30px;text-align: center;">SrNo</th>
                            <th style="width:60px;text-align: center;">EmpCode</th>
                            <th>EmpName</th>
                            <th style="width:60px;text-align: center;">Amount</th>
                            <th style="width:60px;text-align: center;">FromMonth</th>
                            <th style="width:60px;text-align: center;">ToMonth</th>
                            <th style="width:60px;text-align: center;">Installments</th>
                            <th style="width:50px;text-align: center;">EMI</th>
                            <th  style="width:150px;text-align: center;">Guarantor</th>
                            <th>Reason</th> 
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $i=1; foreach ($data as $val){$row=$val['LoanMaster'];?>
                        <tr>
                            <td><center><input class="checkbox" type="checkbox" value="<?php echo $row['Id'];?>" name="check[]"></center></td>
                            <td style="text-align: center;" ><?php echo $i++;?></td>
                            <td style="text-align: center;"><?php echo $row['EmpCode'];?></td>
                            <td><?php echo $row['EmpName'];?></td>
                            <td style="text-align: center;"><?php echo $row['Amount'];?></td>
                            <td style="text-align: center;"><?php echo date('M-Y',strtotime($row['StartDate']));?></td>
                            <td style="text-align: center;"><?php echo date('M-Y',strtotime($row['EndDate']));?></td>
                            
                            <td style="text-align: center;"><?php echo $row['Installments'];?></td>
                            <td style="text-align: center;"><?php echo $row['DeductionPerMonth'];?></td>
                            <td style="text-align: center;" ><?php echo $row['GuarantorName'];?></td>
                            <td><?php echo $row['Reason'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
                
                <div class="form-group">
                    <div class="col-sm-6"><?php echo $this->Session->flash();?></div>
                    <div class="col-sm-6">
                        <?php 
                        echo $this->Form->submit('Back', array('div'=>false,'onclick'=>'goBack();', 'type'=>'button','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;'));
                        echo $this->Form->submit('Not Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
                        echo $this->Form->submit('Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));                   
                        ?>
                    </div> 
                </div>
                <?php }else{?>
                <div class="form-group">
                    <div class="col-sm-6">
                       <?php echo $this->Session->flash();?><br/>
                       <span>No Record for Approval.</span>
                    </div>
                    <div class="col-sm-6">
                        <?php 
                        echo $this->Form->submit('Back', array('div'=>false,'onclick'=>'goBack();', 'type'=>'button','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;'));
                        ?> 
                    </div>
                </div>
                <?php }?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



