 
             <?php //print_r($packageData);die;	

               
   foreach($packageData as $post1):
               	echo $this->Form->input('Jclr.Basic', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['Basic'],'placeholder'=>'Basic',)); 
                
	
	    
               	echo $this->Form->input('Jclr.HRA', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['HRA'],'placeholder'=>'HRA',)); 

               
               	echo $this->Form->input('Jclr.Conv', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['Conv'],'placeholder'=>'Conv',)); 
               
               	echo $this->Form->input('Jclr.OthAllw', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['OthAllw'],'placeholder'=>'Oth Allw',)); 

                
               	echo $this->Form->input('Jclr.Gross', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['Gross'],'placeholder'=>'Gross',)); 
               
               	echo $this->Form->input('Jclr.PF', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['PF'],'placeholder'=>'PF',)); 

               
               	echo $this->Form->input('Jclr.ESI', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['ESI'],'placeholder'=>'ESI',)); 
               
               	echo $this->Form->input('Jclr.TotalDed', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['TotalDed'],'placeholder'=>'Total Ded.',)); 

               
               	echo $this->Form->input('Jclr.Netpay', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['Netpay'],'placeholder'=>'Netpay',)); 
              
               	echo $this->Form->input('Jclr.EmplrPF', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['EmplrPF'],'placeholder'=>'EmplrPF.',)); 

               	echo $this->Form->input('Jclr.EmplrESI', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['EmplrESI'],'placeholder'=>'EmplrESI',)); 
               
               	echo $this->Form->input('Jclr.EmplrIns', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['EmplrIns'],'placeholder'=>'EmplrIns.',)); 

              
               	//echo $this->Form->input('CTC', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post1['Package']['CTC'],'placeholder'=>'CTC',)); 
                
               	echo $this->Form->input('Jclr.ESIS', array('type'=>'hidden','label'=>false,'value'=>$post1['Package']['ESIS'],'class'=>'form-control','placeholder'=>'ESIS.',)); 

               
               	echo $this->Form->input('Jclr.PFS', array('type'=>'hidden','label'=>false,'value'=>$post1['Package']['PFS'],'class'=>'form-control','placeholder'=>'PFS',)); 
              
               	 
endforeach;
                ?>
                
<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" style="width: 300px;" >
                <thead>
                	              	
                	 <?php foreach($packageData as $post): ?>
                    <tr>  
<th>Basic</th>
<?php echo "<td>".$post['Package']['Basic']."</td>"; ?>
                        </tr><tr>  
<th>HRA</th>
<?php echo "<td>".$post['Package']['HRA']."</td>"; ?>
</tr><tr>  
<th>Conv</th>
<?php echo "<td>".$post['Package']['Conv']."</td>"; ?>
</tr><tr>  
<th>OthAllw</th>
<?php echo "<td>".$post['Package']['OthAllw']."</td>"; ?>
</tr><tr>  
<th>Gross</th>
<?php echo "<td>".$post['Package']['Gross']."</td>"; ?>
</tr><tr>  
<th>PF</th>
<?php echo "<td>".$post['Package']['PF']."</td>"; ?>
</tr><tr>  
<th>ESI</th>
<?php echo "<td>".$post['Package']['ESI']."</td>"; ?>
</tr><tr>  
<th>TotalDed</th>
<?php echo "<td>".$post['Package']['TotalDed']."</td>"; ?>
</tr><tr> 
<th>Netpay</th>
<?php echo "<td>".$post['Package']['Netpay']."</td>"; ?>
</tr><tr>  
<th>EmplrPF</th>
<?php echo "<td>".$post['Package']['EmplrPF']."</td>"; ?>
</tr><tr>  
<th>EmplrESI</th>
<?php echo "<td>".$post['Package']['EmplrESI']."</td>"; ?>
</tr><tr>  
<th>EmplrIns</th>
<?php echo "<td>".$post['Package']['EmplrIns']."</td>"; ?>
</tr><tr>  
<th>CTC</th>
<?php echo "<td>".$post['Package']['CTC']."</td>"; ?>
</tr><tr>  
<th>ESIS</th>
<?php echo "<td>".$post['Package']['ESIS']."</td>"; ?>
</tr><tr>  
<th>PFS</th>
<?php echo "<td>".$post['Package']['PFS']."</td>"; ?>
</tr>
<?php  endforeach; ?>
                        
                	</tr>
				</thead>
               
				</table>
			