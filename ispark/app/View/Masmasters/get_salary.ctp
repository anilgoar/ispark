
<?php ?>






    
    <div class="form-group has-info has-feedback">
    <label class="col-sm-2 control-label">Band</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ></div>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $Jclr[0]['m']['Band']; ?>
            </div>    
        </div></div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Band</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ></div>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Band', array('label'=>false,'options'=>$band,'empty'=>'select','class'=>'form-control','onchange'=>'Design(this.value)')); ?>
                <?php	echo $this->Form->input('Id', array('type'=>'hidden','label'=>false,'value'=>$Jclr[0]['m']['Id'],'class'=>'form-control')); ?>
            </div>    
        </div>
        
        <label class="col-sm-2 control-label">Package Amount</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ></div>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Package', array('label'=>false,'placeholder'=>'Package','value'=>$Jclr[0]['m']['Package'],'id'=>'PackageAmount','required'=>true,'onKeyPress'=>'return checkNumber(this.value,event)')); ?>
            </div>    
        </div>
         </div>

    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Basic</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ></div>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('Basic',array('label' => false,'id'=>'Basic','value'=>$Jclr[0]['m']['Basic'], 'oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?> 
            </div>    
        </div>
   
        <label class="col-sm-2 control-label">Conveyance</label>
        <div class="col-sm-1" style="position:relative;left:21px;" >
            <input type="radio" name="per" value="AMT" onclick='Test(this.value,"NameShow");' checked="" />AMT
        
            <input type="radio" name="per" value="per" onclick='Test(this.value,"NameShow");' />%
        </div>
        <div class="col-sm-2" >
           <?php echo $this->Form->input('Conveyance',array('label' => false,'id'=>'Conveyance','value'=>$Jclr[0]['m']['Conveyance'],'placeholder'=>'Conveyance' ,'oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?> 
        </div>
        <div class="col-sm-1" id="NameShow" ></div>
         </div>

    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Portfolio</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="portper" value="AMT" onclick='Test(this.value,"NameShow1");' o checked="" />AMT
        <input type="radio" name="portper" value="per" onclick='Test(this.value,"NameShow1");' />%</div>
                
        <div class="col-sm-2" >
            <?php echo $this->Form->input('Portfolio', array('label'=>false,'type'=>'text','value'=>$Jclr[0]['m']['Portfolio'],'placeholder'=>'Portfolio','id'=>'Portfolio','oninput'=>'grossAmount(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>      
        </div>
        <div class="col-sm-1" id="NameShow1" ></div>  
    
        <label class="col-sm-2 control-label">Medical Allowance	</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="Mtper" value="AMT" onclick='Test(this.value,"NameShow2");' checked="" />AMT
        <input type="radio" name="Mtper" value="per" onclick='Test(this.value,"NameShow2");' />%</div>
        <input type="hidden" name="CntGross" id="CntGross" value="" >
                
        <div class="col-sm-2">
            <?php echo $this->Form->input('Medical', array('label'=>false,'value'=>$Jclr[0]['m']['Medical'],'placeholder'=>'Medical Allowance','id'=>'Medical','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow2" ></div> 
        </div>
    
   
    <div class="form-group has-info has-feedback">
        
        <label class="col-sm-2 control-label">Special Allowance</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="SAper" value="AMT" onclick='Test(this.value,"NameShow3");' checked="" />AMT
        <input type="radio" name="SAper" value="per" onclick='Test(this.value,"NameShow3");' />%</div>     
        <div class="col-sm-2">
            <?php echo $this->Form->input('Special', array('label'=>false,'value'=>$Jclr[0]['m']['Special'],'placeholder'=>'Special Allowance','id'=>'Special','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow3" ></div>   
    
        <label class="col-sm-2 control-label">Other Allowance</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="Oper" value="AMT" onclick='Test(this.value,"NameShow4");' checked="" />AMT
        <input type="radio" name="Oper" value="per" onclick='Test(this.value,"NameShow4");' />%</div>
        <div class="col-sm-2">
            <?php echo $this->Form->input('OtherAllow', array('label'=>false,'value'=>$Jclr[0]['m']['OtherAllow'],'placeholder'=>'Other Allowence','id'=>'OtherAllow','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow4" ></div>
        </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">HRA</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="Hper" value="AMT" onclick='Test(this.value,"NameShow5");' checked="" />AMT
        <input type="radio" name="Hper" value="per" onclick='Test(this.value,"NameShow5");' />%</div>  
        <div class="col-sm-2">
            <?php echo $this->Form->input('HRA', array('label'=>false,'value'=>$Jclr[0]['m']['HRA'],'placeholder'=>'HRA','required'=>true,'id'=>'HRA','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
        <div class="col-sm-1" id="NameShow5" ></div>
    
        <label class="col-sm-2 control-label">Bonus</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="Bper" value="AMT" onclick='Test(this.value,"NameShow6");' checked="" />AMT
        <input type="radio" name="Bper" value="per" onclick='Test(this.value,"NameShow6");' />%</div>
        <div class="col-sm-2">
            <?php echo $this->Form->input('Bonus', array('label'=>false,'value'=>$Jclr[0]['m']['Bonus'],'placeholder'=>'Bonus','id'=>'Bonus','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
        <div class="col-sm-1" id="NameShow6" ></div>
        </div>

    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">PLI</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="PLIper" value="AMT" onclick='Test(this.value,"NameShow7");' checked="" /> AMT
        <input type="radio" name="PLIper" value="per" onclick='Test(this.value,"NameShow7");' /> % </div>    
        <div class="col-sm-2">
            <?php echo $this->Form->input('PLI', array('label'=>false,'value'=>$Jclr[0]['m']['PLI'],'placeholder'=>'PLI','id'=>'PLI','oninput'=>'grossAmount();','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow7" ></div>
  
       
        <label class="col-sm-2 control-label">Gross(Rs.)</label>
         <div class="col-sm-1" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2">
            <?php echo $this->Form->input('Gross', array('label'=>false,'value'=>$Jclr[0]['m']['Gross'],'placeholder'=>'Gross','required'=>true,'id'=>'Gross','onKeyPress'=>'return checkNumber(this.value,event)')); ?> 
        </div>
         <div class="col-sm-1"></div>
          </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">EPF</label>
        
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="EPFper" value="AMT" onclick='Test(this.value,"NameShow8");' checked="" />AMT
        <input type="radio" name="EPFper" value="per" onclick='Test(this.value,"NameShow8");' />%</div>  
        <div class="col-sm-2">
            <?php echo $this->Form->input('EPF', array('label'=>false,'value'=>$Jclr[0]['m']['EPF'],'placeholder'=>'EPF','id'=>'EPF','oninput'=>'cntepf(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow8" ></div> 
    
        <label class="col-sm-2 control-label">ESIC</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="ESICper" value="AMT" onclick='Test(this.value,"NameShow9");'  checked="" />AMT
        <input type="radio" name="ESICper" value="per" onclick='Test(this.value,"NameShow9");' />%</div>   
        <div class="col-sm-2">
            <?php echo $this->Form->input('ESIC', array('label'=>false,'value'=>$Jclr[0]['m']['ESIC'],'placeholder'=>'ESIC','required'=>true,'oninput'=>'cntesic(this.value);','id'=>'ESIC','onKeyPress'=>'return checkNumber(this.value,event)')); ?>    
        </div>
        <div class="col-sm-1" id="NameShow9" ></div>
        </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Professional Tax</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="PTIper" value="AMT" onclick='Test(this.value,"NameShow10");' checked="" />AMT
        <input type="radio" name="PTIper" value="per" onclick='Test(this.value,"NameShow10");' />%</div>   
        <div class="col-sm-2">
            <?php echo $this->Form->input('Professional', array('label'=>false,'value'=>$Jclr[0]['m']['Professional'],'placeholder'=>'Professional Tax.','id'=>'Professional','oninput'=>'cntesic(this.value);','oninput'=>'cntProfessional(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow10" ></div>
    
        <label class="col-sm-2 control-label">Net In Hand(Rs.)</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2">
            <?php echo $this->Form->input('Inhand', array('label'=>false,'value'=>$Jclr[0]['m']['Inhand'],'placeholder'=>'Net In Hand','required'=>true,'id'=>'NetInHand','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
        <div class="col-sm-1"></div>
        </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">EPF CO</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="EPFCper" value="AMT" onclick='Test(this.value,"NameShow11");' checked="" />AMT
        <input type="radio" name="EPFCper" value="per" onclick='Test(this.value,"NameShow11");' />%</div>    
        <div class="col-sm-2">
            <?php echo $this->Form->input('EPFCO', array('label'=>false,'value'=>$Jclr[0]['m']['EPFCO'],'placeholder'=>'EPF Co.','id'=>'EPFCO','required'=>true,'oninput'=>'cntEPFCO(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow11" ></div>
     
        <label class="col-sm-2 control-label">ESIC CO</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="ESICper" value="AMT" onclick='Test(this.value,"NameShow12");' checked="" />AMT
        <input type="radio" name="ESICper" value="per" onclick='Test(this.value,"NameShow12");' />%</div>     
        <div class="col-sm-2">
            <?php echo $this->Form->input('ESICCO', array('label'=>false,'value'=>$Jclr[0]['m']['ESICCO'],'placeholder'=>'ESIC CO','id'=>'ESICCO','required'=>true,'oninput'=>'cntESICCO(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>      
        </div>
        <div class="col-sm-1" id="NameShow12" ></div>
        </div>
    
    
    <div class="form-group has-info has-feedback">   
        <label class="col-sm-2 control-label">Admin Charges</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ><input type="radio" name="ADper" value="AMT" onclick='Test(this.value,"NameShow13");' checked="" />AMT
        <input type="radio" name="ADper" value="per" onclick='Test(this.value,"NameShow13");' />%</div>      
        <div class="col-sm-2">
            <?php echo $this->Form->input('Admin', array('label'=>false,'value'=>$Jclr[0]['m']['Admin'],'placeholder'=>'Admin Charges.','id'=>'Admin','required'=>true,'oninput'=>'cntAdmin(this.value);','onKeyPress'=>'return checkNumber(this.value,event)')); ?>        
        </div>
        <div class="col-sm-1" id="NameShow13" ></div>
    
        <label class="col-sm-2 control-label">CTC</label>
        <div class="col-sm-1" style="position:relative;left:21px;" ></div>
        <div class="col-sm-2">
            <?php echo $this->Form->input('CTC', array('label'=>false,'value'=>$Jclr[0]['m']['CTC'],'placeholder'=>'CTC','id'=>'CTC','required'=>true,'onKeyPress'=>'return checkNumber(this.value,event)')); ?>
        </div>
    </div>
    
    
    </div>
    
    

