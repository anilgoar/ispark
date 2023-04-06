<?php
class BandNameMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','BandNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','deletesource');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');

        $DataArr=$this->BandNameMaster->find('all');
        $this->set('DataArr',$DataArr);
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->BandNameMaster->find('first',array('conditions'=>array('Id'=>base64_decode($_REQUEST['id']))));
            $this->set('row',$row['BandNameMaster']);  
        }
        
        if($this->request->is('Post')){
            
            $Band     =   addslashes(trim($this->request->data['Band']));
            $submit   =   trim($this->request->data['submit']);
            $BandId   =   trim($this->request->data['BandId']);
            $SlabFrom =   trim($this->request->data['SlabFrom']);
            $SlabTo   =   trim($this->request->data['SlabTo']);
            $Status   =   trim($this->request->data['Status']);
            
            if($submit =="Update"){
                $updArr=array(
                    'Band'=>"'".$Band."'",
                    'SlabFrom'=>"'".$SlabFrom."'",
                    'SlabTo'=>"'".$SlabTo."'",
                    'Status'=>"'".$Status."'",
                    'UpdateDate'=>"'".date('Y-m-d H:i:s')."'",
                    );
                
                if($this->BandNameMaster->updateAll($updArr,array('Id'=>$BandId))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This band update successfully.</span>');
                    $this->redirect(array('controller'=>'BandNameMasters')); 
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This band not update please try again later.</span>');
                    $this->redirect(array('controller'=>'BandNameMasters')); 
                }
            }
            else{
                $data=array(
                    'Band'=>$Band,
                    'SlabFrom'=>$SlabFrom,
                    'SlabTo'=>$SlabTo,
                );
                
                $row=$this->BandNameMaster->find('count',array('conditions'=>$data));
                if($row > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This band already exist in database.</span>');
                    $this->redirect(array('controller'=>'BandNameMasters')); 
                }
                else{
                    $this->BandNameMaster->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This department create successfully.</span>');
                    $this->redirect(array('controller'=>'BandNameMasters'));   
                }
                     
            }  
        }  
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $this->BandNameMaster->query("DELETE FROM `incentive_name_master` WHERE Id='{$_REQUEST['id']}'");
            $this->redirect(array('action'=>'index')); 
        }
     
    }
    
    
}
?>