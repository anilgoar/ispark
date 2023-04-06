<?php
class DeleteBiocodeReportsController extends AppController {
    public $uses = array('InactiveBioCode','Masjclrentry');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','getEmpName');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';      
        if(isset($_REQUEST['StartDate']) && $_REQUEST['StartDate'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=DeletedBioCodeReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            $data   =   $this->InactiveBioCode->find('all',array('conditions'=>"date(DeactiveDate)>'".date('Y-m-d',strtotime($_REQUEST['StartDate']))."'"));
            ?>
            <table border="1"  >    
                <thead>
                    <tr>
                        <th>BioCode</th>
                        <th>EmpName</th>
                        <th>Remarks</th>
                        <th>SaveBy</th>
                        <th>SaveDate</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php foreach ($data as $val){?>
                    <tr>
                        <td><?php echo $val['InactiveBioCode']['BioCode'];?></td>
                        <td><?php echo $this->getEmpName($val['InactiveBioCode']['BioCode']);?></td>
                        <td><?php echo $val['InactiveBioCode']['Reason'];?></td>
                        <td><?php echo $val['InactiveBioCode']['SaveByEmail'];?></td>
                        <td><?php echo date('d-M-Y H:i:s',strtotime($val['InactiveBioCode']['DeactiveDate']));?></td>
                    </tr>
                    <?php }?>
                </tbody>   
            </table>
            <?php   
            die;
        }
    }
    
    public function getEmpName($biocode){
        $data   =   $this->Masjclrentry->find('first',array('fields'=>array('EmpName'),'conditions'=>array('BioCode'=>$biocode,'BioCode !='=>'')));
        return $data['Masjclrentry']['EmpName'];
    }
    
      
}
?>