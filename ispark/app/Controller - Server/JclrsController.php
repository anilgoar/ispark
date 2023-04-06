<?php
class JclrsController extends AppController 
{
    public $uses = array('Jclr','User','Docfile','Package');
        
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

           if(in_array('77',$roles)){$this->Auth->allow('index','view','editjclr','update_cost','save_doc','get_status_data','deletefile','viewdoc','save_status','showpack','detailsjclr','exportjclr','uploadjclr','account','appointment_letter');}
            else{$this->Auth->deny('index');}
            $this->Auth->allow('index','view','editjclr','update_cost','save_doc','get_status_data','deletefile','viewdoc','save_status','showpack','detailsjclr','exportjclr','uploadjclr','account','appointment_letter');
        }
    }
    
    public function index()
    {
        $this->layout = "home";
         $this->set('package',$this->Package->find('list',array('fields'=>array('Department','Department'))));
		$data1 = $this->Jclr->query('select MAX(EmpCode) as EmpCode from qual_employee');
               $old= str_replace("MAS","",$data1[0][0]['EmpCode']);
                $new=$old+1;
               $mas= str_replace($old,$new,$data1[0][0]['EmpCode']);
		// print_r($mas); exit;
		    if ($this->request->is('post')) 
			{
				//$this->Jclr->create();
				$data=$this->request->data;
                                
                               $data['Jclr']['EmpCode']=$mas;
                                $data['Jclr']['DOFJ']=  date_format( date_create($data['Jclr']['DOFJ']),'Y-m-d');
                                 $data['Jclr']['DOB']=  date_format( date_create($data['Jclr']['DOB']),'Y-m-d');
                                 $data['Jclr']['NomneeDOB']=  date_format( date_create($data['Jclr']['NomneeDOB']),'Y-m-d');
                                // print_r($data);die;
                                // print_r($this->Jclr->saveall($data));die;
            	if ($this->Jclr->saveall($data))
				{
                   // $id= $this->Jclr->getLastInsertId();
                 // $upd= $this->Jclr->query("update qual_employee set EmpCode = 'MAS80$id' where Id='$id' ");
                  
                	$this->Session->setFlash(__('The Details has been saved and Empcode is '.$mas.'.'));
                	return $this->redirect(array('action' => 'index'));
                  
            	}
            	$this->Session->setFlash(__('The Details could not be saved. Please, try again.'));
			}

    }
    
    public function appointment_letter(){
        $this->layout='home';
        if($this->request->is('POST')){
            $empid=$this->request->data['empid'];
            $empArr = $this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$empid))); 
            $this->set('data',$empArr);
        }
    }
    
    
     public function view()
    {
       $this->set('Jclr', $this->Jclr->query("select * from qual_employee "));
        $this->layout='home';
    }
     public function viewdoc()
    {
       $this->set('Jclr', $this->Jclr->query("select * from qual_employee "));
        $this->layout='home';
    }
    public function editjclr()
    {			
        $id = $this->request->query['id'];
        $this->set('Jclr', $this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$id))));
        		
        $this->layout='home';		
    }

    public function update_cost()
    {
        if ($this->request->is('post')) 
        {
            $data = $this->request->data;
            $data['Jclr']['DOFJ']=  date_format( date_create($data['Jclr']['DOFJ']),'Y-m-d');
            $data['Jclr']['DOB']=  date_format( date_create($data['Jclr']['DOB']),'Y-m-d');
            $id = $data['Jclr']['EmpCode'];
            $data = Hash::Remove($data['Jclr'],'EmpCode');
            //print_r($data);
            //$data['CostCenterMaster'][''] = "'".$data['CostCenterMaster']['']."'";
            $userid = $this->Session->read('userid');
            
           
            
            
            
            $key = array_keys($data);$i =0;
            foreach($data as $post)
            {
                    $dataX[$key[$i++]] = "'".$post."'";
            }
            //print_r($dataX);die;
            if($this->Jclr->updateAll($dataX,array('EmpCode'=>$id)))
            {

                unset($data);unset($key);
                $this->Session->setFlash(__("<h4 class=bg-success>".'The Jclr Details has been updated for'.$id."</h4>"));
                return $this->redirect(array('action'=>'view'));
            }
            else
            {
                    $this->Session->setFlash(__("<h4 class=bg-success>".'The Jclr Details could not be updated. Please Try Againg!'."</h4>"));
                    return $this->redirect(array('action'=>'view'));					
            }
            $this->set('data',$dataX);
        }
    }
  public function save_doc()
    {
            
            $EmpID = $this->request->query['id'];//print_r($EmpID);die;
            $this->set('empid',$EmpID);
            $this->set('Jclr', $this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$EmpID))));
        $this->layout = "home";
        $data1 = $this->Docfile->query("select Doctype from doc_option where `Docstatus` = '1' AND `parentid` IS NULL");
        $data12 = $this->Docfile->query("SELECT SUM(IF(DocType='Code Of Conduct',1,0)) AS coc, SUM(IF(DocType='Epf Declaration Form',1,0)) AS edf, SUM(IF(DocType='Contrat Form',1,0) )AS CF FROM`qual_docoments` WHERE EmpCode ='$EmpID'");
      // print_r($data12);die;
       $finish='';
       if($data12[0][0]['coc']>0 && $data12[0][0]['coc']<7 )
       {
           $finish="disabled ";
       }
       if($data12[0][0]['edf']>0 && $data12[0][0]['edf']<3 )
       {
           $finish="disabled";
       }
        if($data12[0][0]['CF']>0 && $data12[0][0]['CF']<2 )
       {
           $finish="disabled";
       }
        $this->set('finish',$finish);
        $this->set('Data1',$data1);
         if($this->request->is('POST'))
        {   
         // print_r($this->request->data);die;
        $user = $this->Session->read('userid');
        
       $bankdetails= $this->request->data['bankdetails'];
        $submit= $this->request->data['submit']; 
         $AcNo= $this->request->data['Jclr']['AcNo'];
         $bank= $this->request->data['Jclr']['Bank'];
         $bankIFSC= $this->request->data['Jclr']['IFSC'];
         $ACType= $this->request->data['Jclr']['ACType'];
         $BankBranch= $this->request->data['Jclr']['BankBranch'];
         $bankFileTye = $this->request->data['Jclr']['bankfile']['type'];
            $bankfileinfo = explode(".",$this->request->data['Jclr']['bankfile']['name']);
        
        if($bankdetails=='bankdet'){
            
          
             // $EmpID= $this->request->data['Jclr']['EmpID'];
              // print_r($EmpID);die;
               $check=$this->Docfile->query("select * from qual_docoments where `EmpCode`='$EmpID' and DocType='PassBook' and `DocName`='Bank Details'");
            
            
             
            
           $newfilename = 'PassBook.' . $bankfileinfo['1'];
              
           // $filename=$this->request->data['Save']['file']['name'];
           
       // print_r($filename);die;
         if(!file_exists("Doc_File/".$EmpID)) 
                      { 
                        mkdir("Doc_File/".$EmpID); 
                      }                        
                       $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                       fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                       fclose( $fp );
					   //$ins = mysql_query("Insert Into updqry Values ('File Name=$FileName And UserID=$UserId',now())", $this->db);
						$temp = explode(".", $_FILES["file"]["name"]);


        // print_r($info);die;
$target_file = "Doc_File/".$EmpID."/".basename($newfilename);
 $FilePath = $this->request->data['Jclr']['bankfile']['tmp_name']; 
if($bankfileinfo['1']=='jpg'){
    $image = imagecreatefromjpeg($newfilename);

imagejpeg($image, null, 10);
imagejpeg($image, $this->request->data['Jclr']['bankfile']['tmp_name'], 10);
       if (move_uploaded_file($FilePath, $target_file)) {
          
           if(empty($check)){
        $data2 = $this->Docfile->query("insert into qual_docoments set `EmpCode`='$EmpID',DocType='PassBook',`DocName`='Bank Details',`userid`='$user',filename='$newfilename',`saveDate`= now()");
          $data3 =  $this->Docfile->query("update `qual_employee` set `AcNo`='$AcNo',`Bank`='$bank',`IFSC`='$bankIFSC',BankBranch='$BankBranch',`ACType`='$ACType' where `EmpCode`='$EmpID'");
     $this->Session->setFlash($styp. "Bank Details Save Successfully.");

           return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
           }
           else
           {
             $this->Session->setFlash(" File already exiest.");  
              return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
           }
        }
        else{
           $this->Session->setFlash("File  not Save");
            return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
       }
}
       else{
           $this->Session->setFlash("Balnk File Type is not jpg"); 
            return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
       }
       
            
               
      
           
           
       }
        $type = $this->request->data['type'];
              $styp = $this->request->data['styp'];
               $BoxNo = $this->request->data['BoxNo'];
              $FileTye = $this->request->data['Save']['file']['type'];
            $info = explode(".",$this->request->data['Save']['file']['name']);
             $pageno = $this->request->data['pageno'];
               $fileno = $this->request->data['fileno'];
              
             $check=$this->Docfile->query("select * from qual_docoments where `EmpCode`='$EmpID' and DocType='$type' and `DocName`='$styp' and fileno='$pageno'");
            
            
            if($pageno <=$fileno)
            {
              if($fileno!=0){  
            
           $newfilename = $styp.'_'.$pageno. '.' . $info['1'];
              }
              else
              {
                  $newfilename = $styp.'.' . $info['1'];
              }
           // $filename=$this->request->data['Save']['file']['name'];
           
       // print_r($filename);die;
         if(!file_exists("Doc_File/".$EmpID)) 
                      { 
                        mkdir("Doc_File/".$EmpID); 
                      }                        
                       $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                       fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                       fclose( $fp );
					   //$ins = mysql_query("Insert Into updqry Values ('File Name=$FileName And UserID=$UserId',now())", $this->db);
						$temp = explode(".", $_FILES["file"]["name"]);


        // print_r($info);die;
$target_file = "Doc_File/".$EmpID."/".basename($newfilename);
$FilePath = $this->request->data['Save']['file']['tmp_name'];
if($info['1']=='jpg'){
    $image = imagecreatefromjpeg($newfilename);

imagejpeg($image, null, 10);
imagejpeg($image, $this->request->data['Save']['file']['tmp_name'], 10);
       if (move_uploaded_file($FilePath, $target_file)) {
          
           if(empty($check)){
        $data2 = $this->Docfile->query("insert into qual_docoments set `EmpCode`='$EmpID',DocType='$type',`DocName`='$styp',`BoxNo`='$BoxNo',`userid`='$user',filename='$newfilename',fileno='$pageno',`saveDate`= now()");
            if($pageno != '')
            {
           $this->Session->setFlash($styp. " Page No ".$pageno ." Save Successfully  Out of ".$fileno);
            }
 else {
     $this->Session->setFlash($styp. " Save Successfully.");
 }
           return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
           }
           else
           {
             $this->Session->setFlash(" File already exiest.");  
           }
        }
        else{
           $this->Session->setFlash("File  not Save"); 
       }
}
       else{
           $this->Session->setFlash("File Type is not jpg"); 
       }
       
            }
 else {
     $this->Session->setFlash("Wrong page select."); 
 }
          
    }
        $find= $this->Docfile->find('all',array('conditions'=>array('EmpCode'=>$EmpID)));
      //  print_r($find);die;
         $this->set('find',$find);
         $this->set('show',"Doc_File/".$EmpID."/"); 
     
    }
      public function deletefile() {
	$this->layout = "ajax";
        //print_r($this->request->query);die;
       $path = $this->request->query['path'];
       $EmpID = $this->request->query['EmpCode'];
       $fileno = $this->request->query['fileno'];
        $filename = $this->request->query['filename'];
	$this->Docfile->query("delete from qual_docoments where EmpCode= '$EmpID' and filename ='$filename'");
        
        unlink($path);
         return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
	}
    
    
   public function get_status_data()
    {
       
         $this->layout = "ajax";
        if($this->request->is('POST'))
        {
         $data=  $this->request->data;
          $fileno = $this->request->data['fileno'];
           $selectchek12 = $this->Docfile->query("select Id from doc_option where `Docstatus` = '1' AND `Doctype` ='{$data['types']}'");
        //print_r($selectchek12);die;
         $selectchek1 = $this->Docfile->query("select Doctype from doc_option where `Docstatus` = '1' AND `parentid` ='{$selectchek12['0']['doc_option']['Id']}'");
         //$this->set('status',$selectchek12);
         ?>
<select name="styp" class="form-control">
        <option value="">DocName</option>
        <?php foreach($selectchek1 as $sek){ ?>
        <option value="<?php echo $sek['doc_option']['Doctype']; ?>"><?php echo $sek['doc_option']['Doctype']; ?></option>
        <?php } ?>
    </select>
<input type="hidden" name="fileno" value="<?php echo$fileno; ?>">
<?php
        
        }die;
    } 
    public function save_status(){
        $this->layout = "ajax";
       if($this->request->is('POST'))
        {
            $data=  $this->request->data;
         
           
           $this->set('packageam',$this->Package->find('list',array('fields'=>array('Package','Package'),'conditions'=>array('Department'=>$data['desgn']))));
        }
    }
    public function showpack(){
        $this->layout = "ajax";
       if($this->request->is('POST'))
        {
            $data=  $this->request->data;
         //print_r($data);die;
           
           $this->set('packageData',$this->Package->find('all',array('conditions'=>array('Department'=>$data['dept'],'Package'=>$data['pack']))));
        }
    }
    
    
      public function detailsjclr()
    {			
        $EmpID = $this->request->query['id'];
        $this->set('Jclr', $this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$EmpID))));
        $user = $this->Session->read('userid');		
        $this->layout='home';
        if($this->request->is('POST'))
        {
            $data=  $this->request->data;
            $data['Jclr']['Resignation']=  date_format( date_create($data['Jclr']['Resignation']),'Y-m-d');
            $pass =   $this->User->find('first',array('fields'=>array('password','password'),'conditions'=>array('id'=>$user)));
           // print_r($data);die;
            if($pass['User']['password']==$data['Jclr']['Authentication']){
                $this->Jclr->query("Update qual_employee set Resignation='{$data['Jclr']['Resignation']}',Reason='{$data['Jclr']['Reason']}',Status=0 where EmpCode='$EmpID' ");
               $check= $this->Jclr->find('first',array('fields'=>array('Status','Status'),'conditions'=>array('EmpCode'=>$EmpID,'Status'=>0)));
               if($check){
                  $this->Session->setFlash("Employe Left Now.");  
              return $this->redirect(array('action'=>'viewdoc?id='.$EmpID)); 
               }
               else
               {
                 $this->Session->setFlash("Employe Not Left. Please Try Again.");  
              return $this->redirect(array('action'=>'detailsjclr?id='.$EmpID));
               }
            }
            else{
                $this->Session->setFlash("Authentication Code is not valid.");  
              return $this->redirect(array('action'=>'detailsjclr?id='.$EmpID));
            }
        }
        
        
        
    }
    
    
     public function exportjclr() {
	$this->layout = "home";
        $data1 = $this->Jclr->find('list',array('fields'=>array('Dept','Dept'),'group'=>array('Dept'),'order' =>'Dept'));
        $data1 = (array('All'=>'All')+$data1);
        $this->set('Data1',$data1);
	if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         //print_r($result); exit;
          if($result['Dept']=='All'){
              $data1 ="1=1";
          }
          else
          {
            $data1 = "t1.Dept= '{$result['Dept']}'";  
          }
       if($result['Status']=='All'){
          $stat = '' ;
       }
       else{
          $stat=" and Status = '{$result['Status']}'" ;
       }
//print_r($pred); die;
        date_format($date,"Y/m/d H:i:s");
           //echo ; exit;
           $data = $this->Jclr->query("select `EmpCode`,`EmapName`,`password`,`FatherName`,`Gender`,`Blood`,`Qualification`,`PerAdrress`,`PresAdrress`,`perCity`,`presCity`,`perState`,`presState`,`perPincode`,`prespincode`,`perMobile`,`Dept`,`Desg`,`DOFJ`,`Basic`,`HRA`,`Conv`,`OthAllw`,`Gross`,`PF`,`ESI`,`TotalDed`,`Netpay`,`EmplrPF`,`EmplrESI`,`EmplrIns`,`CTC`,`ESIS`,`PFS`,`ESINo`,`UAN`,`PFNo`,`AcNo`,`Bank`,`DOB`,`IFSC`,`ACType`,`BankBranch`,`Resignation`,`Status`,`Reason`,`panno`,`adharno`,`NomineeName`,`RelationWithNomnee`,`NomneeDOB` from qual_employee t1 where $data1 $stat");
          // print_r($data);die;
           			$fileName = "ManPower_JCLR";
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$fileName.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");

		   echo "<table border=\"1\">";
		   echo "<tr>";
		   		
		   		echo "<td>EmpCode</td>"; echo "<td>EmapName</td>";echo "<td>Password</td>"; echo "<td>FatherName</td>"; echo "<td>Gender</td>"; echo "<td>Blood</td>"; 
                                echo "<td>Qualification</td>"; echo "<td>permanent Adrress</td>"; echo "<td>present Adrress</td>"; echo "<td>permanent City</td>"; echo "<td>present City</td>";
                                echo "<td>permanent State</td>"; echo "<td>present State</td>"; echo "<td>permanent Pincode</td>"; echo "<td>pincode</td>"; echo "<td>Mobile</td>";
                                echo "<td>Dept</td>"; echo "<td>Desg</td>"; echo "<td>DOJ</td>"; echo "<td>Basic</td>"; echo "<td>HRA</td>"; echo "<td>Conv</td>"; 
                                echo "<td>OthAllw</td>"; echo "<td>Gross</td>"; echo "<td>PF</td>"; echo "<td>ESI</td>"; echo "<td>TotalDed</td>"; echo "<td>Netpay</td>";
                                echo "<td>EmplrPF</td>"; echo "<td>EmplrESI</td>"; echo "<td>EmplrIns</td>"; echo "<td>CTC</td>"; echo "<td>ESIS</td>"; echo "<td>PFS</td>"; 
                                echo "<td>ESINo</td>"; echo "<td>UAN</td>"; echo "<td>PFNo</td>"; echo "<td>AcNo</td>"; echo "<td>Bank</td>"; echo "<td>DOB</td>"; 
                                echo "<td>IFSC</td>"; echo "<td>ACType</td>"; echo "<td>BankBranch</td>"; echo "<td>Resignation date</td>"; echo "<td>Status</td>"; 
                                echo "<td> Resignation Reason</td>"; echo "<td> PAN NO.</td>"; echo "<td>ADHAR NO.</td>"; echo "<td> Nominee Name</td>"; echo "<td> Relation With Nominee</td>"; echo "<td> Nominee DOB</td>";
			echo "</tr>";	
			

		   foreach($data as $d)
		   {
		   	
			  echo "<tr>";	
		    
                         echo "<td>".$d['t1']['EmpCode']."</td>"; echo "<td>".$d['t1']['EmapName']."</td>"; echo "<td>".$d['t1']['password']."</td>";echo "<td>".$d['t1']['FatherName']."</td>"; echo "<td>".$d['t1']['Gender']."</td>"; echo "<td>".$d['t1']['Blood']."</td>"; echo "<td>".$d['t1']['Qualification']."</td>"; echo "<td>".$d['t1']['PerAdrress']."</td>"; echo "<td>".$d['t1']['PresAdrress']."</td>"; echo "<td>".$d['t1']['perCity']."</td>"; echo "<td>".$d['t1']['presCity']."</td>"; echo "<td>".$d['t1']['perState']."</td>"; echo "<td>".$d['t1']['presState']."</td>"; echo "<td>".$d['t1']['perPincode']."</td>"; echo "<td>".$d['t1']['prespincode']."</td>"; echo "<td>".$d['t1']['perMobile']."</td>"; echo "<td>".$d['t1']['Dept']."</td>"; echo "<td>".$d['t1']['Desg']."</td>"; echo "<td>".$d['t1']['DOFJ']."</td>"; echo "<td>".$d['t1']['Basic']."</td>"; echo "<td>".$d['t1']['HRA']."</td>"; echo "<td>".$d['t1']['Conv']."</td>"; echo "<td>".$d['t1']['OthAllw']."</td>"; echo "<td>".$d['t1']['Gross']."</td>"; echo "<td>".$d['t1']['PF']."</td>"; echo "<td>".$d['t1']['ESI']."</td>"; echo "<td>".$d['t1']['TotalDed']."</td>"; echo "<td>".$d['t1']['Netpay']."</td>"; echo "<td>".$d['t1']['EmplrPF']."</td>"; echo "<td>".$d['t1']['EmplrESI']."</td>"; echo "<td>".$d['t1']['EmplrIns']."</td>"; echo "<td>".$d['t1']['CTC']."</td>"; echo "<td>".$d['t1']['ESIS']."</td>"; echo "<td>".$d['t1']['PFS']."</td>"; echo "<td>".$d['t1']['ESINo']."</td>"; echo "<td>".$d['t1']['UAN']."</td>"; echo "<td>".$d['t1']['PFNo']."</td>"; echo "<td>".$d['t1']['AcNo']."</td>"; echo "<td>".$d['t1']['Bank']."</td>"; echo "<td>".$d['t1']['DOB']."</td>"; echo "<td>".$d['t1']['IFSC']."</td>"; echo "<td>".$d['t1']['ACType']."</td>"; echo "<td>".$d['t1']['BankBranch']."</td>"; echo "<td>".$d['t1']['Resignation']."</td>"; if($d['t1']['Status']==1){echo "<td>Active</td>"; } else{echo "<td>Left</td>";} echo "<td>".$d['t1']['Reason'];
                         echo "<td>".$d['t1']['panno'];echo "<td>".$d['t1']['adharno'];echo "<td>".$d['t1']['NomineeName'];echo "<td>".$d['t1']['RelationWithNomnee'];
                         echo "<td>".$d['t1']['NomneeDOB'];
			  echo "</tr>"; 
		   }
		  //t1.,t1.,t1.PFS
		   echo "</table>";

		   exit;
		   }

	}
       
        
         public function account()
    {
            
            //$EmpID = $this->request->query['id'];//print_r($EmpID);die;
            $this->set('empid',$EmpID);
            $this->set('Jclr', $this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$EmpID,))));
        $this->layout = "home";
      
      // print_r($data12);die;
       if($this->request->is('POST'))
        {   
          
        $user = $this->Session->read('userid');
        
       if(!empty($this->request->data['Save'])){
         $EmpID= $this->request->data['Save']['EmapCode'];
       }
        if(!empty($this->request->data['Jclr'])){
          $EmpID= $this->request->data['Jclr']['EmapCode']; 
       }
         $this->set('emp',$EmpID);
          $find= $this->Docfile->find('all',array('conditions'=>array('EmpCode'=>$EmpID,'DocName'=>'Bank Details')));
           $this->set('Jclr', $this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$EmpID))));
      if(!empty($this->request->data['Jclr'])){
         $AcNo= $this->request->data['Jclr']['AcNo'];
         $bank= $this->request->data['Jclr']['Bank'];
         $bankIFSC= $this->request->data['Jclr']['IFSC'];
         $ACType= $this->request->data['Jclr']['ACType'];
         $BankBranch= $this->request->data['Jclr']['BankBranch'];
         $Remark= $this->request->data['Jclr']['Remark'];
        $action=$this->request->data['action'];
   //  print_r($this->request->data['Jclr']);die;
     
 if(empty($action)){
     $this->Session->setFlash($styp. "Please Click A Button Approve Or DissApprove.");
           return $this->redirect(array('action'=>'account'));
           }
           else
           {  $data3 =  $this->Docfile->query("update `qual_employee` set `AcNo`='$AcNo',`Bank`='$bank',`IFSC`='$bankIFSC',BankBranch='$BankBranch',`ACType`='$ACType' ,`AccountApprove`='$action',`ApprovalStatusRemark`='$Remark' where `EmpCode`='$EmpID'");
           $statusUpdate =$this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$EmpID)));
           if($statusUpdate['Jclr']['AccountApprove']!=0){
             $this->Session->setFlash("Account Validate Successfully.");  
              return $this->redirect(array('action'=>'account'));
           }
 else {
      $this->Session->setFlash("Account Not Validate. Please Try Again.");  
              return $this->redirect(array('action'=>'account'));
 }
           }
      } //print_r($find);die;
      $this->set('find',$find);
         $this->set('show',"Doc_File/".$EmpID."/"); 
       
}
       
            
               
      
           
           
    
               
    
        
     
    
        
        
    }     
    
}

?>