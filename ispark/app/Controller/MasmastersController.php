<?php
class MasmastersController extends AppController 
{
    public $uses = array('Addbranch','User','maspackage','masband','Masjclrentry','MasJclrMaster','BandNameMaster','CostCenterMaster');
        
    
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

            $this->Auth->allow('index','get_design','editpackage','uploadattend','exportsalary','get_design','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','salaryprocess','Savefile','showfile','discardsalary','discardincentive','salarybreakup','get_salary','updatesalary','getcostcenter');
            //else{$this->Auth->deny('index','get_report11','export1','save_status','exportsalary','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','salaryprocess');}
        }
    }
    public function index()
    {
        $this->layout = "home";
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        
        $BandList=array();
        $BandListArr= $this->BandNameMaster->find('all',array('fields'=>array('Band','SlabFrom','SlabTo'),'conditions'=>array('Status'=>1)));
        
        foreach($BandListArr as $list){
            $BandList[$list['BandNameMaster']['Band']]=$list['BandNameMaster']['Band']." (".$list['BandNameMaster']['SlabFrom']."-".$list['BandNameMaster']['SlabTo'].")";
        }
         
        $this->set('BandList',$BandList);
        
        //$Band= $this->masband->query("SELECT BandName, CONCAT(BandName,'(',SlabFrom,'-',SlabTo,')') ran FROM `mas_band`");
        $this->masband->virtualFields = array(
    'slab'=>'CONCAT(masband.BandName,"(",masband.SlabFrom,"-",masband.SlabTo,")")'
);
       $Band= $this->masband->find('list',array('fields'=>array('BandName','slab')));
       $checkBand= $this->masband->find('list',array('fields'=>array('SlabFrom','SlabTo')));
//           print_r($Band);  
//        die;
                  $this->set('band',$Band);
                   if ($this->request->is('post')) 
			{
                        //print_r($this->request->is('post')); exit;
				//$this->Jclr->create();
				$data=$this->request->data;
                                
                               
                                
                           
                                /*
                                $checkBand= $this->masband->find('first',array('fields'=>array('SlabFrom','SlabTo'),'conditions'=>array('Band'=>$data['maspackage']['Band'])));
                                
                                foreach ($checkBand as $k=>$v)
                                {
                                    $start=$k;
                                    $end = $v;
                                }
                                */
                                
                                $checkBand  = $this->BandNameMaster->find('first',array('fields'=>array('SlabFrom','SlabTo'),'conditions'=>array('Band'=>$data['maspackage']['Band'],'Status'=>1)));                              
                                $start      =   $checkBand['BandNameMaster']['SlabFrom'];
                                $end        =   $checkBand['BandNameMaster']['SlabTo'];



if($data['maspackage']['PackageAmount']==$data['maspackage']['CTC']){
if($data['maspackage']['PackageAmount']>=$start && $data['maspackage']['PackageAmount']<=$end){
                                if ($this->maspackage->saveall($data))
				{
                                    $this->Session->setFlash("Package save successfully"); 
                                    return $this->redirect(array('action'=>'index'));
                                }
 else {
    $this->Session->setFlash("Package is not Save");  
 }
                        }
                        else{
                            $this->Session->setFlash("Package Amount is not under the band Slabs"); 
                        }
}
 else {
    $this->Session->setFlash("Package Amount And CTC Is Not Match."); 
}
                        }
       
    }
    
    
    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $conditoin=array('active'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['branch']=$_REQUEST['BranchName'];}else{unset($conditoin['branch']);}
            $data = $this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>$conditoin,'group' =>array('cost_center')));
        
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                foreach ($data as $val){
                    echo "<option  value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            } 
        }  
    }
    
    
    public function editpackage(){
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        
         $id = $this->request->query['id'];
 $data= $this->maspackage->find('all',array('conditions'=>array('id'=>$id)));
 $this->set('Jclr',$data);
//echo $id;die;
        $this->layout = "home";
 
                       
        //$Band= $this->masband->query("SELECT BandName, CONCAT(BandName,'(',SlabFrom,'-',SlabTo,')') ran FROM `mas_band`");
        $this->masband->virtualFields = array(
    'slab'=>'CONCAT(masband.BandName,"(",masband.SlabFrom,"-",masband.SlabTo,")")'
);
       $Band= $this->masband->find('list',array('fields'=>array('BandName','slab')));
       $checkBand= $this->masband->find('list',array('fields'=>array('SlabFrom','SlabTo')));
//           print_r($Band);  
//        die;
                  $this->set('band',$Band);
                   if ($this->request->is('post')) 
			{
                        // print_r($this->request->is('post')); exit;
				//$this->Jclr->create();
				$data=$this->request->data;
                                
                                

                                /*
                                $checkBand= $this->masband->find('first',array('fields'=>array('SlabFrom','SlabTo'),'conditions'=>array('Band'=>$data['maspackage']['Band'])));
                                
                                foreach ($checkBand as $k=>$v)
                                {
                                    $start=$k;
                                    $end = $v;
                                }
                                */
                                
                                $checkBand  = $this->BandNameMaster->find('first',array('fields'=>array('SlabFrom','SlabTo'),'conditions'=>array('Band'=>$data['maspackage']['Band'],'Status'=>1)));                              
                                $start      =   $checkBand['BandNameMaster']['SlabFrom'];
                                $end        =   $checkBand['BandNameMaster']['SlabTo'];

//print_r($data);die;
if($data['maspackage']['PackageAmount']==$data['maspackage']['CTC']){
if($data['maspackage']['PackageAmount']>=$start && $data['maspackage']['PackageAmount']<=$end){
                                if ($this->maspackage->updateAll(array('BranchName'=>"'".$data['maspackage']['BranchName']."'",'Band'=>"'".$data['maspackage']['Band']."'",'PackageAmount'=>"'".$data['maspackage']['PackageAmount']."'",'Basic'=>"'".$data['maspackage']['Basic']."'",'Conveyance'=>"'".$data['maspackage']['Conveyance']."'",'Portfolio'=>"'".$data['maspackage']['Portfolio']."'",'Medical'=>"'".$data['maspackage']['Medical']."'",'Special'=>"'".$data['maspackage']['Special']."'",'OtherAllow'=>"'".$data['maspackage']['OtherAllow']."'",'HRA'=>"'".$data['maspackage']['HRA']."'",'Bonus'=>"'".$data['maspackage']['Bonus']."'",'PLI'=>"'".$data['maspackage']['PLI']."'",'Gross'=>"'".$data['maspackage']['Gross']."'",'EPF'=>"'".$data['maspackage']['EPF']."'",'ESIC'=>"'".$data['maspackage']['ESIC']."'",'Professional'=>"'".$data['maspackage']['Professional']."'",'NetInHand'=>"'".$data['maspackage']['NetInHand']."'",'EPFCO'=>"'".$data['maspackage']['EPFCO']."'",'ESICCO'=>"'".$data['maspackage']['ESICCO']."'",'Admin'=>"'".$data['maspackage']['Admin']."'",'CTC'=>"'".$data['maspackage']['CTC']."'"),array('id'=>$id)))
				{
                                    $this->Session->setFlash("Package Update successfully"); 
                                    return $this->redirect(array('action'=>'index'));
                                }
 else {
    $this->Session->setFlash("Package is not Updaet");  
 }
                        }
                        else{
                            $this->Session->setFlash("Package Amount is not under the band Slabs"); 
                        }
}
else
{
  $this->Session->setFlash("Package Amount And CTC Is Not Match.");  
}
                        }
       
               
    } 
    
    public function get_design(){
        
        
        
        $this->layout = "ajax";
        
        
      $result       = $this->request->data['val']; 
      $BranchName   = $this->request->data['BranchName']; 
         
          
       
             
//           $data = $this->BooksManager->query("SELECT date_format(`date`,'%d/%m/%Y') date,`Particulars`,`VchType`,`Debit`,`Credit`,`Status`,date_format(`ImportDate`,'%d/%m/%Y')ImportDate FROM `tbl_book` WHERE DATE(`date`) BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y')"
//                   );
        
        $data= $this->maspackage->find('all',array('conditions'=>array('Band'=>$result,'BranchName'=>$BranchName)));
          // print_r($data); exit;
           $this->set('Data',$data);
               
    }
    
    public function salarybreakup(){
         $this->layout = "home";
         if ($this->request->is('post')) 
			{
                        // print_r($this->request->is('post')); exit;
				//$this->Jclr->create();
				$data=$this->request->data;
                                
                                 $Empdata= $this->Masjclrentry->find('all',array('conditions'=>array($data['Masmasters']['Serach'].' like'=>'%'.$data['Masmasters']['searchvalue'].'%')));
                                 //print_r($Empdata);die;
                                 $this->set('masJclr',$Empdata);
                        }
                       
                    
    }
     public function get_salary(){
         $this->layout = "ajax";
          if ($this->request->is('post')) 
			{
              
              $offerNo=$this->request->data['offerNo'];
              $this->masband->virtualFields = array(
    'slab'=>'CONCAT(masband.BandName,"(",masband.SlabFrom,"-",masband.SlabTo,")")'
);
       $Band= $this->masband->find('list',array('fields'=>array('BandName','slab')));
       $checkBand= $this->masband->find('list',array('fields'=>array('SlabFrom','SlabTo')));
//           print_r($Band);  
//        die;
                  $this->set('band',$Band);
              //echo $offerNo;die;
            $data=  $this->Masjclrentry->query("select * from masjclrentry mj inner join mas_jclr m on mj.OfferNo=m.Id where m.Id= '$offerNo'");
            $this->set('Jclr',$data);
          }
     }
     public function updatesalary(){
         $this->layout = "ajax";
          if ($this->request->is('post')) 
			{
              $data=$this->request->data;
             // print_r($data);die;
              
              $checkBand= $this->masband->find('list',array('fields'=>array('SlabFrom','SlabTo'),'conditions'=>array('BandName'=>$data['Band'])));
                                //print_r($checkBand);die;
foreach ($checkBand as $k=>$v)
{
    $start=$k;
    $end = $v;
}
//print_r($data);die;
if($data['Package']==$data['CTC']){
if($data['Package']>=$start && $data['Package']<=$end){
                                if ($this->MasJclrMaster->updateAll(array('Band'=>"'".$data['Band']."'",'Package'=>"'".$data['Package']."'",'Basic'=>"'".$data['Basic']."'",'Conveyance'=>"'".$data['Conveyance']."'",'Portfolio'=>"'".$data['Portfolio']."'",'Medical'=>"'".$data['Medical']."'",'Special'=>"'".$data['Special']."'",'OtherAllow'=>"'".$data['OtherAllow']."'",'HRA'=>"'".$data['HRA']."'",'Bonus'=>"'".$data['Bonus']."'",'PLI'=>"'".$data['PLI']."'",'Gross'=>"'".$data['Gross']."'",'EPF'=>"'".$data['EPF']."'",'ESIC'=>"'".$data['ESIC']."'",'Professional'=>"'".$data['Professional']."'",'Inhand'=>"'".$data['Inhand']."'",'EPFCO'=>"'".$data['EPFCO']."'",'ESICCO'=>"'".$data['ESICCO']."'",'Admin'=>"'".$data['Admin']."'",'CTC'=>"'".$data['CTC']."'"),array('Id'=>$data['Id'])))
				{
                                    $this->Session->setFlash("Package Update successfully"); 
                                    return $this->redirect(array('action'=>'salarybreakup'));
                                }
 else {
    $this->Session->setFlash("Package is not Updaet"); 
    return $this->redirect(array('action'=>'salarybreakup'));
 }
                        }
                        else{
                            $this->Session->setFlash("Package Amount is not under the band Slabs");
                            return $this->redirect(array('action'=>'salarybreakup'));
                        }
}
else
{
  $this->Session->setFlash("Package Amount And CTC Is Not Match.");  
  return $this->redirect(array('action'=>'salarybreakup'));
}
     return $this->redirect(array('action'=>'salarybreakup'));
             // print_r($data);die;
          }
     }
    
}

?>