 <?php 
 //print_r($packageam);die;
 
 echo $this->Form->input('Jclr.CTC', array('label'=>false,'options'=>$packageam,'onchange'=>'getpackageData(this.value);','class'=>'form-control','empty'=>'select','required'=>true)); ?>