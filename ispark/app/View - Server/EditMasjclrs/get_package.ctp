 
             <?php //print_r($packageData);die;	

               
   foreach($packageData as $post1):
               	echo $this->Form->input('MasJclrMaster.Basic', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['Basic'],'placeholder'=>'Basic',)); 
                
	
	   
               	echo $this->Form->input('MasJclrMaster.Conveyance', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['Conveyance'],'placeholder'=>'HRA',)); 

               
               	echo $this->Form->input('MasJclrMaster.Portfolio', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['Portfolio'],'placeholder'=>'Conv',)); 
               
               	echo $this->Form->input('MasJclrMaster.Medical', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['Medical'],'placeholder'=>'Oth Allw',)); 

                
               	echo $this->Form->input('MasJclrMaster.HRA', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['HRA'],'placeholder'=>'Gross',)); 
               
               	echo $this->Form->input('MasJclrMaster.Bonus', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['Bonus'],'placeholder'=>'PF',)); 

               
               	echo $this->Form->input('MasJclrMaster.PLI', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['PLI'],'placeholder'=>'ESI',)); 
               
               	echo $this->Form->input('MasJclrMaster.Gross', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['Gross'],'placeholder'=>'Total Ded.',)); 

               
               	echo $this->Form->input('MasJclrMaster.EPF', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['EPF'],'placeholder'=>'Netpay',)); 
              
               	echo $this->Form->input('MasJclrMaster.ESIC', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['ESIC'],'placeholder'=>'EmplrPF.',)); 

               	echo $this->Form->input('MasJclrMaster.Professional', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['Professional'],'placeholder'=>'EmplrESI',)); 
               
               	echo $this->Form->input('MasJclrMaster.NetInHand', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['NetInHand'],'placeholder'=>'EmplrIns.',)); 

              echo $this->Form->input('MasJclrMaster.EPFCO', array('type'=>'hidden','label'=>false,'value'=>$post1['maspackage']['EPFCO'],'class'=>'form-control','placeholder'=>'ESIS.',)); 

               
               	echo $this->Form->input('MasJclrMaster.ESICCO', array('type'=>'hidden','label'=>false,'value'=>$post1['maspackage']['ESICCO'],'class'=>'form-control','placeholder'=>'PFS',));
                echo $this->Form->input('MasJclrMaster.Admin', array('type'=>'hidden','label'=>false,'value'=>$post1['maspackage']['Admin'],'class'=>'form-control','placeholder'=>'PFS',)); 
              
               //	echo $this->Form->input('MasJclrMaster.CTC', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['maspackage']['CTC'],'placeholder'=>'CTC',)); 
                
               	
               	 
endforeach;
                ?>
                
<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" style="width: 300px;" >
                <thead>
                	              	
                	 <?php foreach($packageData as $post): ?>
                    <tr>  
<th>Basic</th>
<?php echo "<td>".$post['maspackage']['Basic']."</td>"; ?>
                        </tr><tr>  
<th>Conveyance</th>
<?php echo "<td>".$post['maspackage']['Conveyance']."</td>"; ?>
</tr><tr>  
<th>Portfolio</th>
<?php echo "<td>".$post['maspackage']['Portfolio']."</td>"; ?>
</tr><tr>  
<th>Medical</th>
<?php echo "<td>".$post['maspackage']['Medical']."</td>"; ?>
</tr><tr>  
<th>Special</th>
<?php echo "<td>".$post['maspackage']['Special']."</td>"; ?>
</tr><tr>  
<th>OtherAllowence</th>
<?php echo "<td>".$post['maspackage']['OtherAllow']."</td>"; ?>
</tr><tr>  
<th>HRA</th>
<?php echo "<td>".$post['maspackage']['HRA']."</td>"; ?>
</tr><tr>  
<th>Bonus</th>
<?php echo "<td>".$post['maspackage']['Bonus']."</td>"; ?>
</tr><tr> 
<th>PLI</th>
<?php echo "<td>".$post['maspackage']['PLI']."</td>"; ?>
</tr><tr>  
<th>Gross</th>
<?php echo "<td>".$post['maspackage']['Gross']."</td>"; ?>
</tr><tr>  
<th>EPF</th>
<?php echo "<td>".$post['maspackage']['EPF']."</td>"; ?>
</tr><tr>  
<th>ESIC</th>
<?php echo "<td>".$post['maspackage']['ESIC']."</td>"; ?>
</tr><tr>  
<th>Professional</th>
<?php echo "<td>".$post['maspackage']['Professional']."</td>"; ?>
</tr><tr>  
<th>NetInHand</th>
<?php echo "<td>".$post['maspackage']['NetInHand']."</td>"; ?>
</tr><tr>  
<th>EPFCO</th>
<?php echo "<td>".$post['maspackage']['EPFCO']."</td>"; ?>
</tr>
<tr>  
<th>ESICCO</th>
<?php echo "<td>".$post['maspackage']['ESICCO']."</td>"; ?>
</tr>
<tr>  
<th>Admin Charge</th>
<?php echo "<td>".$post['maspackage']['Admin']."</td>"; ?>
</tr>
<tr>  
<th>CTC</th>
<?php echo "<td>".$post['maspackage']['CTC']."</td>"; ?>
</tr>
<?php  endforeach; ?>
                        
                	</tr>
				</thead>
               
				</table>
			