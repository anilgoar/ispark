<?php
class LeaveFormatsController extends AppController {
    public $uses = array('LeaveFormat');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        if($this->request->is('Post')){
            
             if($this->request->is("POST")){
            $csv_file   =   $_FILES['UploadLeaveFile']['tmp_name'];
            $FileTye    =   $_FILES['UploadLeaveFile']['type'];
            $info       =   explode(".",$_FILES['UploadLeaveFile']['name']);
            
            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if (($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
   
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $empcode=       $data[0];
                        $empname=       $data[1];
                        $branch=        $data[2];
                        $costcenter=    $data[3];
                        $leaveformdate= $data[4];
                        $leavetodate=   $data[5];
                        $typeofleave=   $data[6];
                        $slot=          $data[7];

                        if($list_value!=''){									
                            $list_value=$list_value.",('".$empcode."','".$empname."','".$branch."','".$costcenter."','".$leaveformdate."','".$leavetodate."','".$typeofleave."',,'".$slot."',NOW())";
                        }
                        else{
                        $list_value=",('".$empcode."','".$empname."','".$branch."','".$costcenter."','".$leaveformdate."','".$leavetodate."','".$typeofleave."',,'".$slot."',NOW())";
                        }   
                    }
                    
                    $this->LeaveFormat->query("INSERT INTO tbl_leaveformat(`empcode`,`empname`,`branch`,`costcenter`,`leaveformdate`,`leavetodate`,`typeofleave`,`slot`) values $list_value"); 
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your csv upload successfully.</span>'); 
                    $this->redirect(array('action' => 'index'));
                }				 
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Upload only csv file.</span>'); 
                $this->redirect(array('action' => 'index'));
            }
          
        }	
    }
		
}


           
            
         
    }
    
        

?>