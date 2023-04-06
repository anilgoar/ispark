<?php ?>
<script>
function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
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
                    <span>CANCEL CHEQUE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('ChequePrints',array('action'=>'chequecancel','class'=>'form-horizontal')); ?>
                
                <div class="form-group" style="border:2px solid #436e90;margin:0px;background-color: #c0d6e4;line-height:25px;">
                    <div class="col-sm-12" style="text-align: right;">
                        <select name="PrintSalaryMonth" id="PrintSalaryMonth"  readonly style="width:100px;" >
                            <option value="<?php echo date('Y-m',strtotime('last month'));?>"><?php echo date('M-Y',strtotime('last month'));?></option>
                        </select> 
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3"><input type="radio" checked name="PrintBankName" value="SBI"  > SBI </div>
                            <div class="col-sm-3"><input type="radio" name="PrintBankName"  value="SBIIDC" > SBI IDC</div>
                            <div class="col-sm-3"><input type="radio" name="PrintBankName" value="ICICI" > ICICI </div>
                            <div class="col-sm-3"><input type="radio" name="PrintBankName" value="HDFC" > HDFC</div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">             
                            <div class="col-sm-3">ChequeFrom</div>
                            <div class="col-sm-3"> <input type="text" name="PrintCheckFrom" id="PrintCheckFrom" onkeypress="return isNumberKey(event,this)" maxlength="6"   required="" style="height:16px;width:100px;" ></div>
                            <div class="col-sm-3">ChequeTo</div>
                            <div class="col-sm-3"> <input type="text" name="PrintCheckTo" id="PrintCheckTo" onkeypress="return isNumberKey(event,this)" maxlength="6"  required="" style="height:16px;width:100px;margin-left:-7px;" ></div>
                        </div>  
                    </div>
                    
                    <div class="col-sm-12">
                         <textarea name="Reason" id="Reason" style="width:400px;" placeholder="Reason" required=""></textarea>
                    </div>
                    
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <button type="submit" class="btn btn-primary btn-new pull-right" >Submit</button>
                        
                    </div>
                    
                    <div class="col-sm-12">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                    
                    
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



