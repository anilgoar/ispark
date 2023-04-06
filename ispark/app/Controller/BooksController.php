<?php
class BooksController extends AppController 
{
    public $uses = array('Addbranch','User','BooksManager','Bookstatus','FundFlow');
        
    
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

            $this->Auth->allow('index','get_report11','export1','export11','get_report12','save_status','get_status_data','get_report15','export12','fundflow','add','viewfundflow','get_dash_data');
            
        }
    }
    
    public function index()
    {
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST'))
        {
            
            $user = $this->Session->read('userid');
            $FileTye = $this->request->data['upload']['file']['type'];
            $info = explode(".",$this->request->data['upload']['file']['name']);
            
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
            {
		$FilePath = $this->request->data['upload']['file']['tmp_name'];
                $files = fopen($FilePath, "r");
                //$files = file_get_contents($FilePath);
                //echo $files;
                
               //$Res = $this->TMPProvision->query("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center,finance_year,month,provision,remarks)");
                $dataArr = array();
                $flag = false;
                $date = date('Y-m-d H:i:s');
                while($row = fgetcsv($files,5000,","))
                {
                    
                    if($flag)
                    {
                        $data['Importdate']= $date;
                        $data['user_id']= $user;
                   
                    $data['Particulars'] = trim($row[1]);
                    
                    $data['VchType'] = trim($row[2]);
                    $data['VchNo'] = trim($row[3]);
                    $data['Debit'] = trim($row[4]);
                    $data['Credit'] = trim($row[5]);
                    //$data['date1'] = date_create($row[0]);
                     $data['date']  = trim($row[0]);
                    // print_r($data['month']);
                    $cost = $this->Bookstatus->find('first',array('fields'=>array('Status'),'conditions'=>array('Particulars'=>$data['Particulars']
                                        
                                            )));
                    $data['Status'] = $cost['Bookstatus']['Status'];
                   //$selectchek = $this->BooksManager->query("select str_to_date(`date`,'%c/%e/%Y') datem from tbl_book where str_to_date(`date`,'%c/%e/%Y')= str_to_date('{$data['date']}','%c-%e-%Y')");
                   
                  print_r($selectchek['datem']);
                    $dataArr[] = $data;
                    }
                    else {$flag = true;}
                }
                
               $this->BooksManager->saveAll($dataArr);
               $max = $this->BooksManager->query("select max(str_to_date(`date`,'%c/%e/%Y')) max from tbl_book where Importdate='$date'");
               $min = $this->BooksManager->query("select min(str_to_date(`date`,'%c/%e/%Y')) min from tbl_book where Importdate='$date'");
               $max_date =trim($max[0][0]['max'])."<br/>";
               $min_date = trim($min[0][0]['min'])."<br/>";
             //  print_r($min_date);die;
             //echo "delete from tbl_book where str_to_date(`date`,'%c/%e/%Y') between '$min_date' and '$max_date' and Importdate != '$date'";
                $this->BooksManager->query("delete from tbl_book where str_to_date(`date`,'%c/%e/%Y') between '$min_date' and '$max_date' and Importdate != '$date'");
              
                
                $this->Session->setFlash('Data Imported Successfully');
            }
            else{
            $this->Session->setFlash('File Format not Valid');
            }
            
    }
    }
    
    
     public function export1()
    {
        $this->layout = "home";

               
    } 
     public function export11()
    {
        $this->layout = "home";

               
    } 
     public function export12()
    {
        $this->layout = "home";

               
    } 
    
     public function get_report11(){
        $this->layout = "ajax";
        
        
      $result = $this->request->data['Books']; 
         //print_r($result); exit;
          
        $start_date = $result['ToDate'];
        $end_date = $result['FromDate'];
             
           $data = $this->BooksManager->query("SELECT SUM(tb.`Debit`)AS `Debit`, tb.`Credit`, tb.`Status`,ff.Budget FROM `tbl_book` tb LEFT JOIN fundflow ff ON tb.`Status` = ff.`Status` AND DATE_FORMAT(STR_TO_DATE(tb.`date`,'%c/%e/%Y'),'%b-%y') = DATE_FORMAT(ff.month,'%b-%y') WHERE str_to_date(`date`,'%c/%e/%Y') BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y') group by Status"
                   );
           $this->set('stardate',$start_date);
           $this->set('enddate',$end_date);
           $this->set('Data',$data);
               
    } 
    
    public function get_report15(){
        $this->layout = "ajax";
        
        
      $result = $this->request->data['Books']; 
         //print_r($result); exit;
          
        $start_date = $result['ToDate'];
        $end_date = $result['FromDate'];
             
           $data = $this->BooksManager->query("SELECT * FROM `tbl_book` WHERE str_to_date(`date`,'%e/%c/%Y') BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y') Order by Status"
                   );
           $this->set('stardate',$start_date);
           $this->set('enddate',$end_date);
           $this->set('Data',$data);
               
    } 
    
    public function get_report12(){
        $this->layout = "ajax";
        
        
      $result = $this->request->data['Books']; 
         //print_r($result); exit;
          
        $start_date = $result['ToDate'];
        $end_date = $result['FromDate'];
            
           $data = $this->BooksManager->query("SELECT SUM(`Debit`)AS `Debit`,`Credit`, `Status`,date_format(str_to_date(`date`,'%c/%e/%Y'),'%d/%b/%Y') `date` FROM `tbl_book` WHERE str_to_date(`date`,'%c/%e/%Y') BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y') GROUP BY `Status`,str_to_date(`date`,'%c/%e/%Y') ORDER BY str_to_date(`date`,'%c/%e/%Y')"
                   );
           $this->set('stardate',$start_date);
           $this->set('enddate',$end_date);
           $this->set('Data',$data);
               
    }
     public function save_status()
    {
        $this->layout = "home";
         $selectchek = $this->BooksManager->query("SELECT Particulars,`VchType`,id FROM tbl_book WHERE Status IS  NULL or Status ='' GROUP BY Particulars");
        // print_r($selectchek);die;
          $this->set('particular',$selectchek);
         if($this->request->is('POST'))
        {
           $data=  $this->request->data;
           foreach($data['particulars'] as $key=>$val)
           {
               $dataArr =array('Particulars'=>$val,'VchType'=>$data['VchType'][$key],'Status'=>$data['status'][$key]);
               $dataArr1 =array('Status'=>"'".$data['status'][$key]."'");
               $selectchek1 = $this->BooksManager->query("select DISTINCT(`Particulars`) from book_status where Particulars ='$val'");
               if(empty($selectchek1 && !empty($data['status'][$key]))){
               $this->Bookstatus->saveAll($dataArr);
               }
               else
               {
                $selectchek1 = $this->BooksManager->query("update book_status set Status = '{$data['status'][$key]}' where Particulars ='$val'");   
               }
               $this->BooksManager->query("update tbl_book set Status = '{$data['status'][$key]}' where Particulars ='$val' and ((Status is NULL) or (Status = ''))");
           }
           $this->Session->setFlash('Data saved successfully');
           $this->redirect(array('controller'=>'Books','action' => 'save_status'));
           
//           echo '<pre>';
//           print_r($dataArr);
//             echo '</pre>';
           
         }
 
    }
    public function get_status_data()
    {
         $this->layout = "ajax";
        if($this->request->is('POST'))
        {
         $data=  $this->request->data;
         if($data['types']=='Exist'){
           $selectchek12 = $this->BooksManager->query("select DISTINCT(`Status`) `Status` from book_status order by Status");
       // print_r($selectchek12);
         //$this->set('status',$selectchek12);
         ?>
<select name="status[]" class="form-control">
        <option value="">Select Status</option>
        <?php foreach($selectchek12 as $sek){ ?>
        <option value="<?php echo $sek['book_status']['Status']; ?>"><?php echo $sek['book_status']['Status']; ?></option>
        <?php } ?>
    </select>
<?php
         }
        else if($data['types']==''){
             
         }
 else {
     ?>
<input type="text" name="status[]" required="" value="" class="form-control" >
<?php
 }
        }die;
    }
    

    
     public function fundflow() 
    {
       $this->layout="home";
       $data = $this->Bookstatus->find('list',array('fields'=>array('Status','Status'),'group' => array('Status'))
                   );
           $this->set('status',$data);

    }
    
    public function add()
    {
    
          $this->layout="home";
          $data['createdate'] = date('Y-m-d H:i:s');
                $data['user_id'] = $this->Session->read('userid');
              
       if($this->request->is('POST'))
        {
          $request = $this->request->data['Books'];
            
            $data['Status'] = addslashes($request['Status']);
             $data['Budget'] = addslashes($request['Budget']);
              
            $data['month'] = addslashes($request['month']);
            $cs = $data['Status'];
          $b= $data['Budget'];
          
          $m = $data['month'];
           $save = $this->FundFlow->find('first', array(
                        'conditions' => array(
                            'FundFlow.Status'=>$cs,
                            'FundFlow.month' => $m
                        )
                    ));
           if($save)
           {
               $ca = $data['month'];
                    $d = new DateTime($ca);

$timestamp = $d->getTimestamp(); // Unix timestamp
$formatted_date = $d->format('M-y');
                        $this->Session->setFlash(" Budget is already set of this Status  of ". $formatted_date).".";
           }
           else{
             if($this->FundFlow->saveall($data))
                    { $ca = $data['month'];
                    $d = new DateTime($ca);

$timestamp = $d->getTimestamp(); // Unix timestamp
$formatted_date = $d->format('M-y');
                        $this->Session->setFlash(" Budget set of ". $formatted_date);
                    }
 else {
     $ca = $data['month'];
                    $d = new DateTime($ca);

$timestamp = $d->getTimestamp(); // Unix timestamp
$formatted_date = $d->format('M-y');
     $this->Session->setFlash(" Budget not set of". $formatted_date);
     
 }
           }
           
  return $this->redirect(array('action'=>'fundflow'));
       }
        
           }
    
    
           
            public function viewfundflow()
        {
             $this->layout = "home";
             if($this->request->is('POST'))
        {
          $request = $this->request->data['Books'];
     //echo "select * from fundflow where `date` = '{$request['month']}'";die;
      $selectchek12 = $this->BooksManager->query("select * from fundflow where `month` = '{$request['month']}'");
      //print_r($selectchek12);die;
      $this->set('Data',$selectchek12);
        
        }
      }
    
     public function get_dash_data()
    {
       $this->layout = "ajax";
      
    }
    
    
}

?>