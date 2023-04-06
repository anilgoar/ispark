<?php
class ActivitysController extends AppController 
{
    public $uses = array('Act','User','Docfile','Addbranch','Actdata');
        
    
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

           if(in_array('77',$roles)){$this->Auth->allow('index','edit','save_doc','view','deletefile','export1','get_report11','exporttotal','get_total');}
            else{$this->Auth->deny('index');}
            $this->Auth->allow('index','edit','save_doc','view','deletefile','export1','get_report11','exporttotal','get_total');
        }
    }
    
    public function index()
    {
        
        $this->layout = "home";
        $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
  $this->set('branch_data', $this->Actdata->find('all'));
         $user = $this->Session->read('userid');
       $name= $this->User->find('all',array('conditions'=>array('id'=>$user)));
       //print_r($name[0]['emp_name']);die;
         $find= $this->Act->find('all',array('conditions'=>array('DataDate'=>date('Y-m-d'),'UserId'=>$user)));
      //  print_r($find);die;
         $this->set('find',$find);
		//$data1 = $this->Jclr->query('select MAX(EmpCode) as EmpCode from qual_employee');
              
		// print_r($mas); exit;
		    if ($this->request->is('post')) 
			{
				//$this->Jclr->create();
				$data1=$this->request->data;
                               // print_r($data1);die;
                                $data['Act']['UserName']=$name[0]['User']['emp_name'];
                                $data['Act']['UserId']=$user;
                                $data['Act']['DataDate']=  date('Y-m-d');
                               $data['Act']['Branch']=$data1['Branch'];
                                $data['Act']['Group']=  $data1['Group'];
                                 $data['Act']['Client']= $data1['Client'];
                                 $data['Act']['Project']=  $data1['Project'];
                                 
                                 $data['Act']['Module']=$data1['Module'];
                                $data['Act']['Activity']=  $data1['Activity'];
                                 $data['Act']['Remarks']= $data1['Remarks'];
                                 $data['Act']['SpentTime']=  $data1['Time'];
                                 $data['Act']['insertdate']=  date('Y-m-d H:i:s');
                                //print_r($data);die;
                                // print_r($this->Jclr->saveall($data));die;
            	if ($this->Act->saveall($data))
				{
                   // $id= $this->Jclr->getLastInsertId();
                 // $upd= $this->Jclr->query("update qual_employee set EmpCode = 'MAS80$id' where Id='$id' ");
                  
                	$this->Session->setFlash(__('The Details has been saved '));
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
       if ($this->request->is('post')) 
        {
            $path = $this->request->data;
        }
        // echo $path;
          $this->set('branch_data', $this->Actdata->find('all',array('conditions'=>array('BranchName'=>$path))));
        $this->layout='ajax';  
    }
     public function viewdoc()
    {
      
        $this->layout='ajax';
    }
    public function edit()
    {		 $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));	
         $id = $this->request->query['Id'];
         $bb=$this->Act->find('all',array('conditions'=>array('id'=>$id)));
       // print_r($bb);die;
         $this->set('branch_data', $this->Actdata->find('all'));
        	 $this->set('ind',$bb);	
        $this->layout='home1';
        
        if ($this->request->is('post')) 
        {
            $data = $this->request->data;
           // print_r($data);die;
            $remarks = addslashes($data['Remarks']);
             $this->Act->query("Update activitydata set Branch='{$data['Branch']}',`Group`='{$data['Group']}',`Client`='{$data['Client']}',`Project`='{$data['Project']}',Module='{$data['Module']}',Activity='{$data['Activity']}',Remarks='$remarks',SpentTime='{$data['Time']}' where   id ='{$data['datid']}'");
             echo '<script language="javascript">window.opener.location.reload();</script>';
	echo '<script language="javascript">self.close();</script>';
        }
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
            if ($this->request->is('post')) 
        {
            $path = $this->request->data;
        }
        // echo $path;
          $this->set('branch_data', $this->Actdata->find('all',array('conditions'=>array('BranchName'=>$path))));
        $this->layout='ajax';  
    }
      public function deletefile() {
	$this->layout = "ajax";
        //print_r($this->request->query);die;
       $path = $this->request->query['path'];
       
	$this->Act->query("delete from activitydata where id= '$path'");
        
       
         return $this->redirect(array('action'=>'index'));
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
    
    
     public function export1() {
	$this->layout = "home";
        
        
	if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         print_r($result); exit;
         
//print_r($pred); die;
       

     }}
       
        
         public function get_report11()
    {
            $this->layout = "ajax";
            if($this->request->is("post"))  
      {
	  	$result = $this->request->data['Activitys'];
                $date=date_create($result['ToDate']);
 $Fdate= date_format($date,"Y-m-d");
 $date1=date_create($result['FromDate']);
 $Sdate= date_format($date1,"Y-m-d");
         $conditions = array('Act.DataDate >=' => $Fdate, 'Act.DataDate <=' => $Sdate);
         $find= $this->Act->find('all',array('conditions'=>$conditions));
//print_r($find); die;
      $this->set('Data',$find); 

    }
    
      }  
     public function exporttotal() {
	$this->layout = "home";
        
        
	if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         print_r($result); exit;
         
//print_r($pred); die;
       

     }}
       
        
         public function get_total()
    {
            $this->layout = "ajax";
            if($this->request->is("post"))  
      {
	  	$result = $this->request->data['Activitys'];
                $date=date_create($result['ToDate']);
 $Fdate= date_format($date,"Y-m-d");
 $M=date_format($date,"m");$Y=date_format($date,"Y");
 $d=cal_days_in_month(CAL_GREGORIAN,$M,$Y);
 $H=$d*9*3600;
 $date1=date_create($result['FromDate']);
 $Sdate= date_format($date1,"Y-m-d");
         //$conditions = array('Act.DataDate >=' => $Fdate, 'Act.DataDate <=' => $Sdate);
         $find= $this->Act->query("SELECT Branch,UserName,SEC_TO_TIME(SUM(TIME_TO_SEC(SpentTime))) AS TotalTime,NetInHand FROM `activitydata` ad LEFT JOIN `Mascode` mc ON ad.UserName=mc.Name LEFT JOIN `masjclrentry` mj ON mj.EmpCode=mc.EmpCode where date(DataDate) between '$Fdate' and '$Sdate' GROUP BY Branch,ad.UserId;");
//print_r($find); die;
          $this->set('H',$H);   
      $this->set('Data',$find); 

    }
    
      }  
}

?>