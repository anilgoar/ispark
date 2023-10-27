<?php
class ProspectsController extends AppController 
{
    public $uses=array('ProspectProduct','ProspectClient','ProspectFollow','User','Addbranch','Addcompany','ProspectEmail','ProspectLeadSource','ProspectClientHis');
    public $components = array('RequestHandler');
		public $helpers = array('Js','Html');
	public function beforeFilter()
	{
        	parent::beforeFilter();
		//$this->layout='home';
                if(!$this->Session->check("username"))
                {
                    return $this->redirect(array('controller'=>'users','action' => 'login'));
                }
                else
                {
                    $role=$this->Session->read("role");
                    $roles=explode(',',$this->Session->read("page_access"));

                    if(in_array('90',$roles)){$this->Auth->allow('index','add','edit','save_sales','view_sales','create_cover','view_approve_sales',
                            'approve_sales','view_pdf','view_report','view_report_prospect_wise','export_report','view_follow','follow_up','email_config','email_config_edit',
                            'lead_source_master','view_prospect_tracker','view_prospect_tracker_id','view_prospect_tracker_track','edit_sales','disapproved_sales','remove_attachment');}
                    else{$this->Auth->deny('index');$this->Auth->deny('add');$this->Auth->deny('edit');}
                }	
                if ($this->request->is('ajax'))
                {
                    $this->render('contact-ajax-response', 'ajax');
                }
    	}
		
    	public function index() 
	{
            $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),'order' => array('comp_code' => 'asc' ))));
            $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
            
            $this->set('product_master', $this->ProspectProduct->query("SELECT Id,get_company_code(company_id) company,get_branch_name(branch_id) branch,ProductName,active FROM prospect_product as ProspectProduct  ORDER BY company_id,branch_id,ProductName"));
            $this->layout='home';
        }
        
	   
       public function add() 
       {
           $this->layout='home';
            if ($this->request->is('post')) 
            {
                //print_r($this->request->data); exit;
                $product= addslashes($this->request->data['prospects']['ProductName']);
                $Product['company_id'] = $this->request->data['prospects']['Company'];
                $Product['branch_id'] = $this->request->data['prospects']['Branch'];
                $Product['ProductName'] = $product;
                $Product['CreateDate'] = date('Y-m-d H:i:s');
                $Product['CreateBy'] = $this->Session->read("userid");
                
                
                if(!$this->ProspectProduct->find('first',array('conditions'=>array('ProductName'=>$product))))
                {
                    if ($this->ProspectProduct->save($Product))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>The Product has been saved</h4>"
                                . "<script>alertify.success('The Product has been saved');</script>"));
                        
                    }
                    else
                    {
                        $this->Session->setFlash(__("<h4 class=bg-danger>The Product could not be saved. Please, try again.</font>"
                                . "<script>alertify.error('The Product could not be saved. Please, try again.');</h4>"));
                    }
                }
                else
                {
                    $this->Session->setFlash(__("<h4 class=bg-danger>The Product Already Exists.</h4>"
                            . "<script>alertify.error('The Product Already Exists.');</script>"));
                }
                
            }
            
            return $this->redirect(array('action' => 'index'));
        }
    public function edit() 
    {
        $this->layout='home';
        if ($this->request->is('post')) 
        {
            $data = $this->request->data['prospects'];
            
            if(!$this->ProspectProduct->find('first',array('conditions'=>array('ProductName'=>$data['ProductName'],'not'=>array('Id'=>$data['Id'])))))
            {
                $product = $data['ProductName'];
                $Id = $data['Id'];
                $active = $data['active'];
                $data = array();
                
                $data['ProductName'] = "'".addslashes($product)."'" ;
                $data['active'] = $active;
                $data['ModifyBy'] = $this->Session->read("userid");
                $data['ModifyDate'] = "'".date('Y-m-d H:i:s')."'";

                if ($this->ProspectProduct->updateAll($data,array('id'=>$Id)))
                {
                    $this->Session->setFlash(__("<h4 class=bg-success>".'The Product has been updated successfully'."</h4>"
                            . "<script>alertify.success('The Product has been updated successfully');</script>"));
                    
                }
                else
                {
                    $this->Session->setFlash(__("<h4 class=bg-danger>".'The Product could not be updated. Please, try again.'."</h4>"
                            . "<script>alertify.error('The Product could not be updated. Please, try again.');</script>"));
                }
            }
            else
            {
                $this->Session->setFlash(__("<h4 class=bg-danger>".'The product allready exists. Please try again.'."</h4>"
                        . "<script>alertify.error('The product allready exists. Please try again.');</script>"));
            }
          return $this->redirect(array('action' => 'index'));
        }
        else
        {
            $id  = $this->request->query['Id'];
            $this->set('product_master',$this->ProspectProduct->find('first',array('conditions'=>array('id'=>$id))));
            $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),'order' => array('comp_code' => 'asc' ))));
            $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
        }
        
    }
    
    public function save_sales() 
    {
        $this->layout='home';
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        $this->set('email_master', $this->ProspectEmail->find('list',array('fields'=>array('Id','Email_Id'),'conditions'=>array('active'=>1))));
        $this->set('lead_source_master', $this->ProspectLeadSource->find('list',array('fields'=>array('Id','LeadSource'),'conditions'=>array('LS_Active'=>1))));
        $parent_user = $this->Session->read('parentUser'); 
        if($this->request->is('POST'))
        {
          //print_r($this->request->data); exit; 
          $request = $this->request->data['prospects'];
          //print_r($request); exit;
          //$data['ProductId'] = implode(",",$request['ProductId']);
          $ProductId = $data['ProductId'] = $request['ProductId'];
          //$data['Introduction'] = addslashes($request['Introduction']);
          $data['ClientName'] = addslashes($request['ClientName']);
          $data['ContactNo'] = addslashes($request['ContactNo']);
          
          $data['Email'] = addslashes($request['Email']);
          $data['Address1'] = addslashes($request['Address1']);
          $data['Address2'] = addslashes($request['Address2']);
          $data['Address3'] = addslashes($request['Address3']);
          $data['Address4'] = addslashes($request['Address4']);
          $data['Address5'] = addslashes($request['Address5']);
          $data['Remarks'] = addslashes($request['Remarks']);
          $data['CreateBy'] = $this->Session->read("userid");
          $data['CreateDate'] = date("Y-m-d H:i:s");
          $data['LeadSource'] =addslashes($request['LeadSource']); 
          
          $comp_Arr = $this->ProspectProduct->find('first',array('conditons'=>"Id='$ProductId'",'fields'=>array('company_id')));
          $comp_id = $comp_Arr['ProspectProduct']['company_id'];
          $EmailConfig = $this->ProspectEmail->find('first',array('conditions'=>"Email_Company='$comp_id'",'fields'=>array('Id')));
          $data['SenderEmail'] = $EmailConfig['ProspectEmail']['Id'] ;
          
          
          
          if($this->ProspectClient->save($data))
          { 
            $Id = $this->ProspectClient->getLastInsertID();
              
              
              
            $this->Session->setFlash(__("<h4 class=bg-success>".'The Record has been Saved successfully'."</h4>"
                      . "<script>alertify.success('The Record has been Saved successfully');</script>"));
              
            $msgBody = '<table border="2">';
            $msgBody .= '<tr><th>Client Name</th><td>'.$data['ClientName'].'</td></tr>';
            $msgBody .= '<tr><th>Contact No</th><td>'.$data['ContactNo'].'</td></tr>';
            $msgBody .= '<tr><th>Email</th><td>'.$data['Email'].'</td></tr>';
            $msgBody .= '<tr><th>Remarks</th><td>'.$data['Remarks'].'</td></tr>';
            $msgBody .= '</table>';
        
            $sub = "Client Details Saved For - ".$data['ClientName'];
            $UserArr = $this->User->query("SELECT username FROM `tbl_user` tu INNER JOIN `user_type` ut WHERE tu.Id = ut.Id AND page_access LIKE '%112%'");
        
        $to = $this->Session->read("email");
        foreach($UserArr as $usr)
        {
           if(!empty($usr['tu']['username']))
           {
               $cc = trim($usr['tu']['username']);
           }
        }
        foreach($parent_user as $usr)
        {
           if(!empty($usr))
           {
               $cc = $usr;
           }
        }
        
        App::uses('sendEmail', 'custom/Email');
        
	$mail = new sendEmail();
        try{
                if(!empty($to))
                { 
                   if(!empty($cc))
                   {
                       $mail-> multiple($to,$cc,$msgBody,$sub); 
                   }
                   else
                   { 
                   
                       $mail-> to($to,$msgBody,$sub); 
                   }
                }
           }
            catch(SocketException $e)
            {
                $error = "Email Not Send";
            }
              
              
              return $this->redirect(array('action' => 'save_sales'));
          }
          else
          {
              $this->Session->setFlash(__("<h4 class=bg-success>".'The Record has been Not Saved'."</h4>"
                      . "<script>alertify.error('The Record has been Not Saved');</script>"));
          }
          
        
        }
    }
    public function view_sales() 
    {
        $this->layout='home';
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        if($this->Session->read('userid')=='19')
        {
            $childUser="";
        }
        else
        {$childUser = " and tu.username in ('".implode("','",$this->Session->read("childUser"))."')";}
        
        if($this->request->is("post"))
        {
            $search = $this->request->data['prospects'];
            $qry = "";
            if(!empty($search['ProductId'])&& $search['ProductId']!='All')
            {
                $qry .=" and sc.ProductId='".$search['ProductId']."'";
            }
            if(!empty($search['Introduction'])&& $search['Introduction']!='All')
            {
                $qry .=" and sc.Introduction='".$search['Introduction']."'";
            }
            if(!empty($search['ClientName'])&& $search['ClientName']!='All')
            {
                $qry .=" and sc.ClientName like '%".$search['ClientName']."'%";
            }
            
            if(!empty($search['ToDate'])&& !empty($search['FromDate']))
            {
                $qry .=" and sc.CreateDate between '".$search['ToDate']."' and '".$search['FromDate']."'";
            }
            
        }
        $data = $this->ProspectClient->query("SELECT * FROM `prospect_client` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id
WHERE  (sc.IntroApprove=0 || sc.IntroApprove=1)  $qry $childUser");
        
        //print_r($data); exit;
        
        $this->set('sales_master', $data);
    }
    
    public function disapproved_sales() 
    {
        $this->layout='home';
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        if($this->Session->read('userid')=='19')
        {
            $childUser="";
        }
        else
        {$childUser = " and tu.username in ('".implode("','",$this->Session->read("childUser"))."')";}
        
        if($this->request->is("post"))
        {
            $search = $this->request->data['prospects'];
            $qry = "";
            if(!empty($search['ProductId'])&& $search['ProductId']!='All')
            {
                $qry .=" and sc.ProductId='".$search['ProductId']."'";
            }
            if(!empty($search['Introduction'])&& $search['Introduction']!='All')
            {
                $qry .=" and sc.Introduction='".$search['Introduction']."'";
            }
            if(!empty($search['ClientName'])&& $search['ClientName']!='All')
            {
                $qry .=" and sc.ClientName like '%".$search['ClientName']."'%";
            }
            
            if(!empty($search['ToDate'])&& !empty($search['FromDate']))
            {
                $qry .=" and sc.CreateDate between '".$search['ToDate']."' and '".$search['FromDate']."'";
            }
            
        }
        $data = $this->ProspectClient->query("SELECT * FROM `prospect_client` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id
WHERE  sc.IntroApprove=2  $qry $childUser");
        
        //print_r($data); exit;
        
        $this->set('sales_master', $data);
    }
    
    public function edit_sales() 
    {
        $this->layout='home';
        $Id = $this->params->query['Id'];
        //$this->set('ProspectClient',$this->ProspectClientHis->find('first',array('conditions'=>array('DataId'=>$Id))));
        $this->set('ProspectClient',$this->ProspectClient->find('first',array('conditions'=>array('Id'=>$Id))));
        
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        $this->set('email_master', $this->ProspectEmail->find('list',array('fields'=>array('Id','Email_Id'),'conditions'=>array('active'=>1))));
        $this->set('lead_source_master', $this->ProspectLeadSource->find('list',array('fields'=>array('Id','LeadSource'),'conditions'=>array('LS_Active'=>1))));
        $parent_user = $this->Session->read('parentUser'); 
        if($this->request->is('POST'))
        {
          //print_r($this->request->data); exit; 
          $request = $this->request->data['prospects'];
          $Id =$request['Id']; 
          //print_r($request); exit;
          //$data['ProductId'] = implode(",",$request['ProductId']);
          $data['ProductId'] = "'".$request['ProductId']."'";
          //$data['Introduction'] = addslashes($request['Introduction']);
          $data['ClientName'] = "'".addslashes($request['ClientName'])."'";
          $data['ContactNo'] = "'".addslashes($request['ContactNo'])."'";
          
          $data['Email'] = "'".addslashes($request['Email'])."'";
          $data['Address1'] = "'".addslashes($request['Address1'])."'";
          $data['Address2'] = "'".addslashes($request['Address2'])."'";
          $data['Address3'] = "'".addslashes($request['Address3'])."'";
          $data['Address4'] = "'".addslashes($request['Address4'])."'";
          $data['Address5'] = "'".addslashes($request['Address5'])."'";
          $data['Remarks'] = "'".addslashes($request['Remarks'])."'";
          $data['CreateBy'] = "'".$this->Session->read("userid")."'";
          $data['CreateDate'] = "'".date("Y-m-d H:i:s")."'";
          $data['LeadSource'] ="'".addslashes($request['LeadSource'])."'"; 
          $data['SenderEmail'] = "'".addslashes($request['SenderEmail'])."'" ;
          
          if($this->ProspectClient->updateAll($data,array('Id'=>$Id)))
          { 
              
              
              if(!empty($request['logo_file']))
                {
                     $files=$request['logo_file'];
                     $logo_name = str_replace(" ", "", $request['logo_file']['name']);
                     $logo_name = str_replace("/", "", $logo_name);
                     $logo_name = str_replace("\\", "", $logo_name);
                     $logo_name = str_replace("'", "", $logo_name);
                     $logo_name = str_replace('"', "", $logo_name);
                     
                     move_uploaded_file($files['tmp_name'],WWW_ROOT."prospect_file/$Id/".$logo_name);
                     $this->ProspectClient->updateAll(array('logo_file'=>"'$logo_name'"),array('Id'=>$Id));
                }
                if(!empty($request['attachment']))
                {
                     $files1=$request['attachment'];
                     $attachment = str_replace(" ", "", $request['attachment']['name']);
                     $attachment = str_replace("/", "", $attachment);
                     $attachment = str_replace("\\", "", $attachment);
                     $attachment = str_replace("'", "", $attachment);
                     $attachment = str_replace('"', "", $attachment);
                     move_uploaded_file($files1['tmp_name'],WWW_ROOT."prospect_file/$Id/".$attachment);
                     $this->ProspectClient->updateAll(array('attachment'=>"'$attachment'"),array('Id'=>$Id));
                }
                
              
              
              
              $this->Session->setFlash(__("<h4 class=bg-success>".'The Record has been Saved successfully'."</h4>"
                      . "<script>alertify.success('The Record has been Saved successfully');</script>"));
              
              $msgBody = '<table border="2">';
        $msgBody .= '<tr><th>Client Name</th><td>'.$data['ClientName'].'</td></tr>';
        $msgBody .= '<tr><th>Contact No</th><td>'.$data['ContactNo'].'</td></tr>';
        $msgBody .= '<tr><th>Email</th><td>'.$data['Email'].'</td></tr>';
        $msgBody .= '<tr><th>Remarks</th><td>'.$data['Remarks'].'</td></tr>';
        $msgBody .= '</table>';
        
        $sub = "Client Details Saved For - ".$data['ClientName'];
        $UserArr = $this->User->query("SELECT username FROM `tbl_user` tu INNER JOIN `user_type` ut WHERE tu.Id = ut.Id AND page_access LIKE '%112%'");
        
        $to = $this->Session->read("email");
        foreach($UserArr as $usr)
        {
           if(!empty($usr['tu']['username']))
           {
               $cc = trim($usr['tu']['username']);
           }
        }
        foreach($parent_user as $usr)
        {
           if(!empty($usr))
           {
               $cc = $usr;
           }
        }
        
        App::uses('sendEmail', 'custom/Email');
        
	$mail = new sendEmail();
        try{
                if(!empty($to))
                { 
                   if(!empty($cc))
                   {
                       $mail-> multiple($to,$cc,$msgBody,$sub); 
                   }
                   else
                   { 
                   
                       $mail-> to($to,$msgBody,$sub); 
                   }
                }
           }
            catch(SocketException $e)
            {
                $error = "Email Not Send";
            }
              
              
              return $this->redirect(array('action' => 'save_sales'));
          }
          else
          {
              $this->Session->setFlash(__("<h4 class=bg-success>".'The Record has been Not Saved'."</h4>"
                      . "<script>alertify.error('The Record has been Not Saved');</script>"));
          }
          
        
        }
    }
    
    public function create_cover() 
    {
        $this->layout='home';
        $Id = $this->params->query['Id'];
        $from = $this->params->query['from'];
        $disapprove = $this->params->query['disapprove'];
        $this->set('from',$from);
        $this->set("send_mail",$this->params->query['send_mail']);
        $data3 = $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$Id)));
        $this->set('SC', $data3);
        $data4 = $this->ProspectClientHis->find('first',array('conditions'=>array("Id"=>$data3['ProspectClient']['HisLastId'])));
        //print_r($data4); exit;
        $this->set('SC2', $data4);
        $parent_user = $this->Session->read('parentUser'); 
        if($this->request->is("post"))
        {
            if($this->request->data['submit'])
            {
                
                $Id = $this->request->data['prospects']['Id'];
                if($from=='fromApprover')
                {
                    $data2 =  $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$Id)));
                    $DataId = $data2['ProspectClient']['Id'];
                    $data2 = Hash::Remove($data2['ProspectClient'],'Id');
                    $data2['DataId'] = $DataId;
                   
                    $this->ProspectClientHis->save(array('ProspectClientHis'=>$data2));
                    $hisMax = $this->ProspectClientHis->query("Select max(Id)max from prospect_client_history where DataId='$Id'");
                    $HisId = $hisMax['0']['0']['max'];
                    if(!file_exists(WWW_ROOT."prospect_file_his/$HisId"))
                    {
                        mkdir(WWW_ROOT."prospect_file_his/$HisId");
                    }
                    
                    copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment']);
                    copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment1'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment1']);
                    copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment2'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment2']);
                    copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment3'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment3']);
                    $this->ProspectClient->updateAll(array('HisLastId'=>$HisId,'counter'=>'counter+1'),array("Id"=>$Id));
                }
                $Lead['to'] =addslashes($this->request->data['prospects']['EmailTo']);
                $Lead['cc'] = addslashes($this->request->data['prospects']['EmailCC']);
                $Lead['subject'] = addslashes($this->request->data['prospects']['EmailSub']);
                $Lead['Cover'] = addslashes($this->request->data['prospects']['Cover']);
                $Lead['MailBody'] = addslashes($this->request->data['prospects']['MailBody']);
                $data['EmailTo']    = "'".addslashes($this->request->data['prospects']['EmailTo'])."'";
                $data['EmailCC']    = "'".addslashes($this->request->data['prospects']['EmailCC'])."'";
                $data['EmailSub']   = "'".addslashes($this->request->data['prospects']['EmailSub'])."'";
                $data['Introduction'] = "'".addslashes($this->request->data['prospects']['Introduction'])."'";
                $data['Cover']      = "'".addslashes($this->request->data['prospects']['Cover'])."'";
                $data['MailBody']      = "'".addslashes($this->request->data['prospects']['MailBody'])."'";
                $data['SendBy'] = $this->Session->read("userid");
                $data['SendDate'] = "'".date("Y-m-d H:i:s")."'";
                $data['Active'] = "2";
                if($from=='fromApprover')
                {
                    $data['IntroApprove'] = "3";
                }
                elseif ($disapprove=='disapprove') {
                $data['IntroApprove'] = "2";
                }
                else
                {
                    $data['IntroApprove'] = "0";
                }
                $Lead['ProspectId'] = $Id;
                $Lead['LeadStatus'] = addslashes($this->request->data['prospects']['LeadStatus']);
                $Lead['FollowDate'] = addslashes($this->request->data['prospects']['FollowDate']);
                $Lead['Remarks'] = addslashes($this->request->data['prospects']['Remarks']);
                $Lead['CreateDate'] = date('Y-m-d');
                $Lead['CreateBy'] = $this->Session->read("userid");
                $this->ProspectFollow->save($Lead);
                $LeadId = $this->ProspectFollow->getLastInsertID();
                $data['LastId'] = $LeadId;
                
                
                
                if($this->ProspectClient->updateAll($data,array('Id'=>$Id)))
                {
                    if(!file_exists(WWW_ROOT."prospect_file/$Id"))
                    {
                        mkdir(WWW_ROOT."prospect_file/$Id");
                    }
                    
                    $request = $this->request->data['prospects'];
                    
                    if(!empty($request['logo_file']))
                    {
                        $files=$request['logo_file'];
                        $logo_name = str_replace(" ", "", $request['logo_file']['name']);
                        $logo_name = str_replace("/", "", $logo_name);
                        $logo_name = str_replace("\\", "", $logo_name);
                        $logo_name = str_replace("'", "", $logo_name);
                        $logo_name = str_replace('"', "", $logo_name);
                        
                        if(move_uploaded_file($files['tmp_name'],WWW_ROOT."prospect_file/$Id/".$logo_name))
                        {
                            $this->ProspectClient->updateAll(array('logo_file'=>"'$logo_name'"),array('Id'=>$Id));
                        }
                    }
                    if(!empty($request['attachment1']))
                    {
                         $files1=$request['attachment1'];
                         $attachment1 = str_replace(" ", "", $request['attachment1']['name']);
                         $attachment1 = str_replace("/", "", $attachment1);
                         $attachment1 = str_replace("\\", "", $attachment1);
                         $attachment1 = str_replace("'", "", $attachment1);
                         $attachment1 = str_replace('"', "", $attachment1);
                         
                         if(move_uploaded_file($files1['tmp_name'],WWW_ROOT."prospect_file/$Id/".$attachment1))
                         {
                             $this->ProspectClient->updateAll(array('attachment1'=>"'$attachment1'"),array('Id'=>$Id));
                         }
                    }
                    if(!empty($request['attachment2']))
                    {
                         $files2=$request['attachment2'];
                         $attachment2 = str_replace(" ", "", $request['attachment2']['name']);
                         $attachment2 = str_replace("/", "", $attachment2);
                         $attachment2 = str_replace("\\", "", $attachment2);
                         $attachment2 = str_replace("'", "", $attachment2);
                         $attachment2 = str_replace('"', "", $attachment2);
                         if(move_uploaded_file($files2['tmp_name'],WWW_ROOT."prospect_file/$Id/".$attachment2))
                         {
                            $this->ProspectClient->updateAll(array('attachment2'=>"'$attachment2'"),array('Id'=>$Id));
                         }
                        
                    }
                    if(!empty($request['attachment3']))
                    {
                         $files3=$request['attachment3'];
                         $attachment3 = str_replace(" ", "", $request['attachment3']['name']);
                         $attachment3 = str_replace("/", "", $attachment3);
                         $attachment3 = str_replace("\\", "", $attachment3);
                         $attachment3 = str_replace("'", "", $attachment3);
                         $attachment3 = str_replace('"', "", $attachment3);
                         if(move_uploaded_file($files3['tmp_name'],WWW_ROOT."prospect_file/$Id/".$attachment3))
                         {
                            $this->ProspectClient->updateAll(array('attachment3'=>"'$attachment3'"),array('Id'=>$Id));
                         }
                    }
                
              
                    
                    $this->Session->setFlash(__("<h4 class=bg-success>".'Record has been Saved successfully'."</h4>"));
                    if(!empty($from))
                    {
                    return $this->redirect(array('action' => 'create_cover','?'=>array("Id"=>$Id,"send_mail"=>"1",'from'=>$from)));
                    }
                    else
                    {
                        return $this->redirect(array('action' => 'create_cover','?'=>array("Id"=>$Id,"send_mail"=>"1")));
                    }
                }
                else
                {
                  $this->Session->setFlash(__("<h4 class=bg-success>".'Record has Not been Saved'."</h4>"));
                }
            }
            else if($this->request->data['Send']=='Send')
            {
                $Id = $this->request->data['prospects']['Id'];
                
                $data =  $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$Id)));
                $ProductId = $data['ProspectClient']['ProductId'];
                $ProductLogo = $data['ProspectClient']['logo_file'];
                $ProductAdd = $data['ProspectClient']['Address1'].$data['ProspectClient']['Address2'].$data['ProspectClient']['Address3'].$data['ProspectClient']['Address4'].$data['ProspectClient']['Address5'];
                
                $ClientName = $data['ProspectClient']['ClientName'];
                $Intro = $data['ProspectClient']['Introduction'];
                $ProductName = $this->ProspectProduct->find('first',array('conditions'=>array('Id'=>$ProductId)));
                $string=$ProductName['ProspectProduct']['ProductName'];
                $ProspectEmail = $this->ProspectEmail->find('first',array('conditions'=>array("Id"=>$data['ProspectClient']['SenderEmail'])));
                
                $num=40;
                    $length = strlen($string);
                    $output[0] = substr($string, 0, $num);
                    $output[1] = substr($string, $num, $length );
                    $webroot= $this->webroot."app/webroot/"; 
                    //echo exit;
                $html =  '<html>
<head>
<style>
.pic1{
    background-image: url("http://mascallnetnorth.in'.$webroot.'img/pdfimg/P1.jpg"); 
    background-repeat: no-repeat;
    width:713px;
    height:900px;
    color:white;
    margin-left:7px; 
}
</style>
</head>
<body>
    <div class="pic1" >
    <div style="margin-left:65%;margin-top:132px;">
            <p style="width:240px;">'.$ProductName['ProspectProduct']['ProductName'].'</p>
            <p><strong>To</strong></p>
            <p style="width:240px;">'.$ClientName.'</p>
            <p><img src="http://www.mascallnetnorth.in'.$webroot.'prospect_file/'."$Id/".$ProductLogo.'" style="width:150px;" height="100px;" ></p>
            <p style="width:240px;">'.$ProductAdd.'</p>
        </div>
    </div>
    <div style="margin-left:7px;" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P2.jpg" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P3.jpg" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P4.jpg" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P5.jpg" >
        <div>'.$data['ProspectClient']['Cover'].'</div>
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P8.jpg" style=" width:713px;height:900px;" >
    </div>
</body>
</html>'; 
                    require_once(APP . 'Vendor' . DS . 'dompdf' . DS . 'dompdf_config.inc.php');
                    $dompdf = new DOMPDF();
                    $dompdf->load_html($html);
                    $dompdf->render();
                    $output = $dompdf->output();
                    $attachFile = 'attachment_'.date('Ymd_His').".pdf";
                    $filename="/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$Id/$attachFile";
                    file_put_contents($filename, $html);
                    
                
                
                if($Intro=='EOI' || $Intro=='others')
                {
                    App::uses('sendEmailDialdesk', 'custom/Email');
                    $to = explode(",",$data['ProspectClient']['EmailTo']);
                    $cc = explode(",",$data['ProspectClient']['EmailCC']);
                    $sub = $data['ProspectClient']['EmailSub'];
                    $body = addslashes($data['ProspectClient']['MailBody']);
                    $attachment = array();
                    
                    $attachment[] =  $filename;
                    
                    if(!empty($data['ProspectClient']['attachment1']))
                    {
                        $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$Id/".$data['ProspectClient']['attachment1']; 
                    }
                    if(!empty($data['ProspectClient']['attachment2']))
                    {
                        $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$Id/".$data['ProspectClient']['attachment2']; 
                    }
                    if(!empty($data['ProspectClient']['attachment3']))
                    {
                        $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$Id/".$data['ProspectClient']['attachment3']; 
                    }
                    
                    if(empty($to))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email To is not Defined'."</h4>"));
                    }
                    else if(empty($cc))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email CC Not Defined'."</h4>"));
                    }
                    else if(empty($sub))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email Subject Not Defined'."</h4>"));
                    }
                    else
                    {   
                        
                        //print_r($ProspectEmail['ProspectEmail']); exit;
                        $mail = new sendEmailDialdesk();
                        //print_r($ProspectEmail['ProspectEmail']['Email_Password']); exit;
                        $mail-> send_mail($to,$cc,$attachment,$body,$sub,$ProspectEmail['ProspectEmail']['Email_Host'],$ProspectEmail['ProspectEmail']['Email_Port'],$ProspectEmail['ProspectEmail']['Email_Id'],$ProspectEmail['ProspectEmail']['Email_Password']);
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Mail has been send successfully'."</h4>"));
                        
                        $msgBody = '<table border="2">';
                        $msgBody .= '<tr><th>Client Name</th><td>'.$ClientName.'</td></tr>';
                        $msgBody .= "<tr><th>Product</th><td>$string</td></tr>";
                        $msgBody .= '</table>';
                        $sub = "$Intro Send To - $ClientName";
                        $UserArr = $this->User->query("SELECT username FROM tbl_user tu INNER JOIN user_type ut WHERE tu.Id = ut.Id AND page_access LIKE '%111%'");
                        $to = trim($this->Session->read("email"));
                        foreach($UserArr as $usr)
                        {
                           if(!empty($usr['tu']['username']))
                           {
                               $cc = trim($usr['tu']['username']);
                           }
                        }
                        foreach($parent_user as $usr)
                        {
                           if(!empty($usr))
                           {
                               $cc = $usr;
                           }
                        }
                        
                        
                        App::uses('sendEmail', 'custom/Email');
                           
                        $mail = new sendEmail();
                        try{
                                if(!empty($to))
                                {
                                    if(!empty($cc))
                                    {
                                        $mail-> multiple($to,$cc,$msgBody,$sub);
                                    }
                                    else
                                    {
                                        $mail-> to($to,$msgBody,$sub);
                                    }
                                }
                           }
                            catch(SocketException $e)
                            {
                                $error = "Email Not Send";
                            }
                      //  exit;
                        return $this->redirect(array('action' => 'view_sales'));
                    }
                }
                else
                {
                    $this->ProspectClient->updateAll(array('attachment'=>"'$attachFile'"),array('Id'=>$Id));
                    $this->ProspectClient->updateAll(array('IntroApprove'=>"3"),array('Id'=>$Id));
                    $this->Session->setFlash(__("<h4 class=bg-success>".'Mail has been Moved For Approval'."</h4>"));
//                    $msgBody = '<table border="2">';
//                    $msgBody .= '<tr><th>Client Name</th><td>'.$ClientName.'</td></tr>';
//                    $msgBody .= "<tr><th>Product</th><td>$string</td></tr>";
//                    $msgBody .= '</table>';
//                    $sub = "$Intro For - $ClientName Moved To Approval Window";
//                    $UserArr = $this->User->query("SELECT username FROM tbl_user tu INNER JOIN user_type ut WHERE tu.Id = ut.Id AND '111' IN (page_access)");
//                    $to = trim($this->Session->read("username"));
//                    foreach($UserArr as $usr)
//                    {
//                       if(!empty($usr['tu']['username']))
//                       {
//                           $cc = trim($usr['tu']['username']);
//                       }
//                    }

//                    App::uses('sendEmailDialdesk', 'custom/Email');

//                    $mail = new sendEmailDialdesk();
//                    try{
//                        if(!empty($to))
//                        {
//                           if(!empty($cc))
//                           {
//                               $mail-> send_mail($to,$cc,$filename,$body,$sub,$ProspectEmail['ProspectEmail']['Email_Host'],$ProspectEmail['ProspectEmail']['Email_Port'],$ProspectEmail['ProspectEmail']['Email_Id'],$ProspectEmail['ProspectEmail']['Email_Password']);
//                           }
//                           else
//                           {
//                               $mail-> send_mail($to,$filename,$body,$sub,$ProspectEmail['ProspectEmail']['Email_Host'],$ProspectEmail['ProspectEmail']['Email_Port'],$ProspectEmail['ProspectEmail']['Email_Id'],$ProspectEmail['ProspectEmail']['Email_Password']);
//                           }
//                        }
//                       }
//                        catch(SocketException $e)
//                        {
//                            $error = "Email Not Send";
//                        }
                }
            }
            else if($this->request->data['Send']=='SendToCustomer')
            {
                $v = $Id;
                    if(1)
                    {
                        $data2 =  $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$v)));
                        $DataId = $data2['ProspectClient']['Id'];
                        $data2 = Hash::Remove($data2['ProspectClient'],'Id');
                        $data2['DataId'] = $DataId;

                        $this->ProspectClientHis->save(array('ProspectClientHis'=>$data2));
                        $hisMax = $this->ProspectClientHis->query("Select max(Id)max from prospect_client_history where DataId='$v'");
                        $HisId = $hisMax['0']['0']['max'];
                        if(!file_exists(WWW_ROOT."prospect_file_his/$HisId"))
                        {
                            mkdir(WWW_ROOT."prospect_file_his/$HisId");
                        }

                        
                        copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment1'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment1']);
                        copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment2'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment2']);
                        copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment3'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment3']);
                        $this->ProspectClient->updateAll(array('HisLastId'=>$HisId,'counter'=>'counter+1'),array("Id"=>$Id));
                    }
                    
                    $data =  $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$v)));
                    $ProductId = $data['ProspectClient']['ProductId'];
                    $ProductName = $this->ProspectProduct->find('first',array('conditions'=>array('Id'=>$ProductId)));
                    $ProductLogo = $data['ProspectClient']['logo_file'];
                    $ProductAdd = $data['ProspectClient']['Address1'].$data['ProspectClient']['Address2'].$data['ProspectClient']['Address3'].$data['ProspectClient']['Address4'].$data['ProspectClient']['Address5'];
                    $ClientName = $data['ProspectClient']['ClientName'];
                    $Intro = $data['ProspectClient']['Introduction'];
                    //$string=$ProductName['ProspectProduct']['ProductName'];
                    $data = $data['ProspectClient'];
                    $ProspectEmail = $this->ProspectEmail->find('first',array('conditions'=>array("Id"=>$data['SenderEmail'])));   
                    
                    App::uses('sendEmailDialdesk', 'custom/Email');
                    $to = explode(",",$data['EmailTo']);
                    $cc = explode(",",$data['EmailCC']);
                    $sub = $data['EmailSub'];
                    $body = addslashes($data['MailBody']);
                    
                    
                    if(empty($to))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email To is not Defined'."</h4>"));
                    }
                    else if(empty($cc))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email CC Not Defined'."</h4>"));
                    }
                    else if(empty($sub))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email Subject Not Defined'."</h4>"));
                    }
                    else
                    {
                        $attachment =  array();
                        
                        if(!empty($data['attachment1']))
                        {
                            $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$v/".$data['attachment1']; 
                        }
                        if(!empty($data['attachment2']))
                        {
                            $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$v/".$data['attachment2']; 
                        }
                        if(!empty($data['attachment3']))
                        {
                            $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$v/".$data['attachment3']; 
                        }
                        
                        $mail = new sendEmailDialdesk();
                        $mail-> send_mail($to,$cc,$attachment,$body,$sub,$ProspectEmail['ProspectEmail']['Email_Host'],$ProspectEmail['ProspectEmail']['Email_Port'],$ProspectEmail['ProspectEmail']['Email_Id'],$ProspectEmail['ProspectEmail']['Email_Password']);
                        //$mail-> to($email2,$cc,$body,$sub);
                        
                        $msgBody = '<table border="2">';
                        $msgBody .= '<tr><th>Client Name</th><td>'.$ClientName.'</td></tr>';
                        $msgBody .= '</table>';

                        $sub = "Client Prospect Approved For - ".$ClientName;
                        $UserArr = $this->User->query("SELECT username FROM `tbl_user` tu INNER JOIN `user_type` ut WHERE tu.Id = ut.Id AND '113' IN (page_access)");
                        $to = trim($this->Session->read("username"));
                        foreach($UserArr as $usr)
                        {
                           if(!empty($usr['tu']['username']))
                           {
                               $cc = trim($usr['tu']['username']);
                           }
                        }

                        App::uses('sendEmail', 'custom/Email');

                        $mail = new sendEmail();
                        try{
                            if(!empty($to))
                            {
                               if(!empty($cc))
                               {
                                   $mail-> multiple($to,$cc,$msgBody,$sub);
                               }
                               else
                               {
                                   $mail-> to($to,$msgBody,$sub);
                               }
                            }
                           }
                            catch(SocketException $e)
                            {
                                $error = "Email Not Send";
                            }
                    }
                    $this->ProspectClient->updateAll(array('IntroApprove'=>"1",'Active'=>'3','ApproveBy'=>$this->Session->read("userid"),'ProspectUniqueNo'=>"'ProposalNo-".date('YmdHis')."'"),array("Id"=>$v));
                  $this->Session->setFlash(__("<h4 class=bg-success>".'Record Approved successfully'."</h4>"));
                return $this->redirect(array('action' => 'view_approve_sales'));  
                }
        }
        
    }
    
    public function view_follow()
    {
        $this->layout='home';
        if($this->Session->read('userid')=='19')
        {
            $childUser="";
        }
        else
        {
            $childUser = " and tu.username in ('".implode("','",$this->Session->read("childUser"))."')";
        }
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>
            array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        
        if($this->request->is("post"))
        {
            $search = $this->request->data['prospects'];
            $qry = "";
            if(!empty($search['ProductId'])&& $search['ProductId']!='All')
            {
                $qry .=" and sc.ProductId='".$search['ProductId']."'";
            }
            if(!empty($search['Introduction'])&& $search['Introduction']!='All')
            {
                $qry .=" and sc.Introduction='".$search['Introduction']."'";
            }
            if(!empty($search['ClientName'])&& $search['ClientName']!='All')
            {
                $qry .=" and sc.ClientName like '%".$search['ClientName']."'%";
            }
            
            if(!empty($search['ToDate'])&& !empty($search['FromDate']))
            {
                $qry .=" and sc.CreateDate between '".$search['ToDate']."' and '".$search['FromDate']."'";
            }
        }
        
        $this->set('sales_master', $this->ProspectClient->query("SELECT sc.*,sp.* FROM `prospect_client` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id  INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id WHERE  (EmailTo is not null and sc.Email!='') $qry $childUser"));
    }
    
    public function follow_up() 
    {
        $this->layout='home';
        $Id = $this->params->query['Id'];
        $this->set("send_mail",$this->params->query['send_mail']);
        $this->set('SC', $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$Id))));
        
        if($this->request->is("post"))
        {
                //print_r($this->request->data);
                $Id = $this->request->data['prospects']['Id'];
                $DataClient = $this->ProspectClient->find('first',array('conditions'=>array('Id'=>$Id)));
                $Lead['to'] =addslashes($DataClient['ProspectClient']['EmailTo']);
                $Lead['cc'] = addslashes($DataClient['ProspectClient']['EmailTo']);
                $Lead['subject'] = addslashes($DataClient['ProspectClient']['EmailSub']);
                $Lead['Cover'] = addslashes($DataClient['ProspectClient']['Cover']);
                
                $Lead['ProspectId'] = $Id;
                $Lead['LeadStatus'] = addslashes($this->request->data['prospects']['LeadStatus']);
                $DateChange = explode("-",addslashes($this->request->data['prospects']['FollowDate']));
                $DateChange1[0] = $DateChange[2];
                $DateChange1[1] = $DateChange[1];
                $DateChange1[2] = $DateChange[0];
                $Lead['FollowDate'] = implode("-",$DateChange1);
                
                $Lead['Remarks'] = addslashes($this->request->data['prospects']['Remarks']);
                $Lead['CreateDate'] = date('Y-m-d H:i:s');
                $Lead['CreateBy'] = $this->Session->read("userid");
                
                if($this->ProspectFollow->save($Lead))
                {  
                    $LeadId = $this->ProspectFollow->getLastInsertID();
                    $data['LastId'] = $LeadId;

                    if($this->ProspectClient->updateAll($data,array('Id'=>$Id)))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Record has been Saved successfully'."</h4>"));
                        return $this->redirect(array('action' => 'view_follow','?'=>array("Id"=>$Id,"send_mail"=>"1")));
                    }
                    else
                    {
                      $this->Session->setFlash(__("<h4 class=bg-success>".'Record has Not been Saved'."</h4>"));
                    }
                }
                else
                {
                  $this->Session->setFlash(__("<h4 class=bg-success>".'Record has Not been Saved'."</h4>"));
                }
            
            
        }
        
    }
    
    public function view_prospect_tracker()
    {
        $this->layout='home';
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        if($this->request->is("post"))
        {
            $search = $this->request->data['prospects'];
            $qry = "";
            if(!empty($search['ProductId'])&& $search['ProductId']!='All')
            {
                $qry .=" and sc.ProductId='".$search['ProductId']."'";
            }
            if(!empty($search['Introduction'])&& $search['Introduction']!='All')
            {
                $qry .=" and sc.Introduction='".$search['Introduction']."'";
            }
            if(!empty($search['ClientName'])&& $search['ClientName']!='All')
            {
                $qry .=" and sc.ClientName like '%".$search['ClientName']."'%";
            }
            
            if(!empty($search['ToDate'])&& !empty($search['FromDate']))
            {
                $qry .=" and sc.CreateDate between '".$search['ToDate']."' and '".$search['FromDate']."'";
            }
            
        }
        $data = $this->ProspectClient->query("SELECT * FROM `prospect_client` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id
WHERE  1=1  $qry ");
        $this->set('sales_master', $data);
    }
    
    public function view_prospect_tracker_id()
    {
        $this->layout='home';
        $Id = $this->params->query['id'];
        $data = $this->ProspectClient->query("SELECT * FROM `prospect_client_history` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id
WHERE  sc.DataId = '$Id' ");
        $this->set('sales_master', $data);
    }
    public function view_prospect_tracker_track()
    {
        $this->layout='home';
        $Id = $this->params->query['id'];
        $data = $this->ProspectClient->query("SELECT * FROM `prospect_client` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id
WHERE  sc.Id = '$Id' ");
        $this->set('sales_master', $data);
        $data2 = $this->ProspectClient->query("SELECT * FROM `prospect_client_history` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id
WHERE  sc.Id = '{$data['0']['sc']['HisLastId']}' ");
//print_r($data); exit;
        $this->set('sales_master_his', $data2);
    }
    public function view_approve_sales() 
    {
        $this->layout='home';
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        
        if($this->request->is("post"))
        {
            if(!empty($this->request->data['Search']))
            {    
                $search = $this->request->data['prospects'];
                $qry = "";
                if(!empty($search['ProductId'])&& $search['ProductId']!='All')
                {
                    $qry .=" and sc.ProductId='".$search['ProductId']."'";
                }
                if(!empty($search['Introduction'])&& $search['Introduction']!='All')
                {
                    $qry .=" and sc.Introduction='".$search['Introduction']."'";
                }
                if(!empty($search['ClientName'])&& $search['ClientName']!='All')
                {
                    $qry .=" and sc.ClientName like '%".$search['ClientName']."'%";
                }

                if(!empty($search['ToDate'])&& !empty($search['FromDate']))
                {
                    $qry .=" and sc.CreateDate between '".$search['ToDate']."' and '".$search['FromDate']."'";
                }
            }
            else if(!empty($this->request->data['Approve']))
            {
                if(empty($_POST['check']))
                {
                    $this->Session->setFlash(__("<h4 class=bg-success>".'Please Select Record'."</h4>"));
                    return $this->redirect(array('action' => 'view_approve_sales'));
                }
                foreach($_POST['check'] as $v)
                {
                    if(1)
                    {
                        $data2 =  $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$v)));
                        $DataId = $data2['ProspectClient']['Id'];
                        $data2 = Hash::Remove($data2['ProspectClient'],'Id');
                        $data2['DataId'] = $DataId;

                        $this->ProspectClientHis->save(array('ProspectClientHis'=>$data2));
                        $hisMax = $this->ProspectClientHis->query("Select max(Id)max from prospect_client_history where DataId='$v'");
                        $HisId = $hisMax['0']['0']['max'];
                        if(!file_exists(WWW_ROOT."prospect_file_his/$HisId"))
                        {
                            mkdir(WWW_ROOT."prospect_file_his/$HisId");
                        }

                        copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment']);
                        copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment1'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment1']);
                        copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment2'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment2']);
                        copy("/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/$DataId/".$data2['attachment3'],"/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file_his/$HisId/".$data2['attachment3']);
                        $this->ProspectClient->updateAll(array('HisLastId'=>$HisId,'counter'=>'counter+1'),array("Id"=>$Id));
                    }
                    
                    $data =  $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$v)));
                    $ProductId = $data['ProspectClient']['ProductId'];
                    $ProductName = $this->ProspectProduct->find('first',array('conditions'=>array('Id'=>$ProductId)));
                    $ProductLogo = $data['ProspectClient']['logo_file'];
                    $ProductAdd = $data['ProspectClient']['Address1'].$data['ProspectClient']['Address2'].$data['ProspectClient']['Address3'].$data['ProspectClient']['Address4'].$data['ProspectClient']['Address5'];
                    $ClientName = $data['ProspectClient']['ClientName'];
                    $Intro = $data['ProspectClient']['Introduction'];
                    //$string=$ProductName['ProspectProduct']['ProductName'];
                    $data = $data['ProspectClient'];
                    $ProspectEmail = $this->ProspectEmail->find('first',array('conditions'=>array("Id"=>$data['SenderEmail'])));   
                    
                    App::uses('sendEmailDialdesk', 'custom/Email');
                    $to = explode(",",$data['EmailTo']);
                    $cc = explode(",",$data['EmailCC']);
                    $sub = $data['EmailSub'].'-'.$data['ProspectUniqueNo'];
                    $body = addslashes($data['MailBody']); 
                    
                    
                    if(empty($to))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email To is not Defined'."</h4>"));
                    }
                    else if(empty($cc))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email CC Not Defined'."</h4>"));
                    }
                    else if(empty($sub))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email Subject Not Defined'."</h4>"));
                    }
                    else
                    {
                        $attachment =  array();
                        
                        if(!empty($data['attachment1']))
                        {
                            $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$v/".$data['attachment1']; 
                        }
                        if(!empty($data['attachment2']))
                        {
                            $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$v/".$data['attachment2']; 
                        }
                        if(!empty($data['attachment3']))
                        {
                            $attachment[] = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect_file/'."$v/".$data['attachment3']; 
                        }
                        
                        $mail = new sendEmailDialdesk();
                        $mail-> send_mail($to,$cc,$attachment,$body,$sub,$ProspectEmail['ProspectEmail']['Email_Host'],$ProspectEmail['ProspectEmail']['Email_Port'],$ProspectEmail['ProspectEmail']['Email_Id'],$ProspectEmail['ProspectEmail']['Email_Password']);
                        //$mail-> to($email2,$cc,$body,$sub);
                        
                        $msgBody = '<table border="2">';
                        $msgBody .= '<tr><th>Client Name</th><td>'.$ClientName.'</td></tr>';
                        $msgBody .= '</table>';

                        $sub = "Client Prospect Approved For - ".$ClientName;
                        $UserArr = $this->User->query("SELECT username FROM `tbl_user` tu INNER JOIN `user_type` ut WHERE tu.Id = ut.Id AND '113' IN (page_access)");
                        $to = trim($this->Session->read("username"));
                        foreach($UserArr as $usr)
                        {
                           if(!empty($usr['tu']['username']))
                           {
                               $cc = trim($usr['tu']['username']);
                           }
                        }

                        App::uses('sendEmail', 'custom/Email');

                        $mail = new sendEmail();
                        try{
                            if(!empty($to))
                            {
                               if(!empty($cc))
                               {
                                   $mail-> multiple($to,$cc,$msgBody,$sub);
                               }
                               else
                               {
                                   $mail-> to($to,$msgBody,$sub);
                               }
                            }
                           }
                            catch(SocketException $e)
                            {
                                $error = "Email Not Send";
                            }
                    }
                    $this->ProspectClient->updateAll(array('IntroApprove'=>"1",'Active'=>'3','ApproveBy'=>$this->Session->read("userid"),'ProspectUniqueNo'=>"'ProposalNo-".date('YmdHis')."'"),array("Id"=>$v));
                    
                }
                $this->Session->setFlash(__("<h4 class=bg-success>".'Record Approved successfully'."</h4>"));
                return $this->redirect(array('action' => 'view_approve_sales'));
            }
            else if(!empty($this->request->data['DisApprove']))
            {
                if(empty($_POST['check'])){
                $this->Session->setFlash(__("<h4 class=bg-success>".'Please Select Record'."</h4>"));
                return $this->redirect(array('action' => 'view_approve_sales'));
                }
                foreach($_POST['check'] as $v)
                {
                    
                    
                    if($this->ProspectClient->updateAll(array('IntroApprove'=>"2",'ApproveBy'=>$this->Session->read("userid")),array("Id"=>$v)))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Record has been Saved Successfully'."</h4>"));
                    }
                }
                $this->Session->setFlash(__("<h4 class=bg-success>".'Record DisApproved Successfully'."</h4>"));
                return $this->redirect(array('action' => 'view_approve_sales'));
            }
        }
        if($this->Session->read('userid')=='19')
        {
            $childUser="";
        }
        else
        {
            $childUser = " and tu.username in ('".implode("','",$this->Session->read("childUser"))."')";
        }
        $this->set('sales_master', $this->ProspectClient->query("SELECT * FROM `prospect_client` sc INNER JOIN `prospect_product` sp ON sc.ProductId = sp.Id  left JOIN tbl_user tu ON sc.CreateBy=tu.Id WHERE  sc.IntroApprove=3 $qry AND
IF(Introduction ='Commercial' || Introduction ='Revised proposal',TRUE,FALSE)  "));
    }
    
    
    public function approve_sales() 
    {       
        $this->layout='home';
        $Id = $this->params->query['Id'];
        if($this->Session->read('userid')=='19')
        {
            $childUser="";
        }
        else
        {
            $childUser = " and tu.username in ('".implode("','",$this->Session->read("childUser"))."')";
        }
        $this->set('product_master', $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        $this->set('SC', $this->ProspectClient->query("SELECT * FROM `prospect_client` sc 
INNER JOIN `prospect_product` sd ON sc.ProductId = sd.Id
LEFT JOIN `prospect_follow` sf ON sc.LastId = sf.Id   INNER JOIN tbl_user tu ON sc.CreateBy=tu.Id Where Id='$Id' $childUser limit 1"));
        if($this->request->is("post"))
        {
            $Id = $this->request->data['prospects']['Id'];
            
            $data['EmailTo']    = "'".addslashes($this->request->data['prospects']['EmailTo'])."'";
            $data['EmailCC']    = "'".addslashes($this->request->data['prospects']['EmailCC'])."'";
            $data['EmailSub']   = "'".addslashes($this->request->data['prospects']['EmailSub'])."'";
            $data['Cover']      = "'".addslashes($this->request->data['prospects']['Cover'])."'";
            $data['SendBy'] = $this->Session->read("userid");
            $data['SendDate'] = "'".date("Y-m-d H:i:s")."'";
            $data['Active'] = "2";
            
            if($this->ProspectClient->updateAll($data,array('Id'=>$Id)))
            {
                $data =  $this->ProspectClient->find('first',array('conditions'=>array("Id"=>$Id)));
                $this->ProspectClientHis->save(array('ProspectClientHis'=>$data['ProspectClient']));
                if($data['ProspectClient']['Counter']!='0')
                {
                    $hisMax = $this->ProspectClientHis->query("Select max(Id)max from prospect_client where DataId='$Id'");
                    $HisId = $hisMax['0']['0']['max'];
                    $this->ProspectClient->updateAll(array('HisLastId'=>$HisId),array("Id"=>$Id));
                }
                
                $this->ProspectClient->updateAll(array('counter'=>'counter+1'),array('Id'=>$Id));
                
                if($this->ProspectClient->data['prospects']['EmailTo']=='EOI')
                {
                    
                    $ProductId = $data['ProspectClient']['ProductId'];
                    $ProductName = $this->ProspectProduct->find('first',array('conditions'=>array('Id'=>$ProductId)));
                    $ProductLogo = $data['ProspectClient']['logo_file'];
                    $ProductAdd = $data['ProspectClient']['Address1'].$data['ProspectClient']['Address2'].$data['ProspectClient']['Address3'].$data['ProspectClient']['Address4'].$data['ProspectClient']['Address5'];
                    $ClientName = $data['ProspectClient']['ClientName'];
                    $Intro = $data['ProspectClient']['Introduction'];
                    $string=$ProductName['ProspectProduct']['ProductName'];
                    $num=40;
                    $length = strlen($string);
                    $output[0] = substr($string, 0, $num);
                    $output[1] = substr($string, $num, $length );
                    $webroot= $this->webroot."app/webroot/"; 
                    //echo exit;
                $html =  '<html>
<head>
<style>
.pic1{
    background-image: url("http://mascallnetnorth.in'. $webroot.'img/pdfimg/P1.jpg");   
    background-repeat: no-repeat;
    width:713px;
    height:900px;
    color:white;
    margin-left:7px; 
}
</style>
</head>
<body>
     <div class="pic1">
        <div style="margin-left:50%;margin-top:132px;">
            <p style="width:340px;">'.$ProductName['ProspectProduct']['ProductName'].'</p>
            <p style="margin-left: 130px;"><strong>To</strong></p>
            <p style="width:340px;">'.$ClientName.'</p>
            <p style="margin-left: 80px;"><img src="http://www.mascallnetnorth.in'.$webroot.'prospect_file/'."$Id/".$ProductLogo.'" style="width:150px;" height="100px;" ></p>
            <p style="width:340px;">'.$ProductAdd.'</p>
        </div>
    </div>
    <div style="margin-left:7px;" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P2.jpg" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P3.jpg" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P4.jpg" >
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P5.jpg" >
        <div>'.$data['ProspectClient']['Cover'].'</div>
        <img src="http://www.mascallnetnorth.in'.$webroot.'img/pdfimg/P8.jpg" style=" width:713px;height:900px;" >
    </div>
</body>
</html>'; 
                    require_once(APP . 'Vendor' . DS . 'dompdf' . DS . 'dompdf_config.inc.php');
                    $dompdf = new DOMPDF();
                    $dompdf->load_html($html);
                    $dompdf->render();
                    $output = $dompdf->output();
                    
                    $filename="/var/www/html/mascallnetnorth.in/ispark/app/webroot/prospect/".date('Ymd_His').".pdf";
                    file_put_contents($filename, $output);
                    //exit;
                    App::uses('sendEmailDialdesk', 'custom/Email');
                    $to = explode(",",$data['ProspectClient']['EmailTo']);
                    $cc = explode(",",$data['ProspectClient']['EmailCC']);
                    $sub = $data['ProspectClient']['EmailSub'];
                    $body = $this->request->data['ProspectClient']['Cover'];
                    
                    if(empty($to)) 
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email To is not Defined'."</h4>"));
                    }
                    else if(empty($cc))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email CC Not Defined'."</h4>"));
                    }
                    else if(empty($sub))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>".'Email Subject Not Defined'."</h4>"));
                    }
                    else
                    {
                        $mail = new sendEmailDialdesk();
                        $mail-> send_mail($to,$cc,$filename,$body,$sub);
                    //$mail-> to($email2,$cc,$body,$sub);	
                    }
                    $this->Session->setFlash(__("<h4 class=bg-success>".'Mail has been Send successfully'."</h4>"));
                }
                else
                {
                    $this->Session->setFlash(__("<h4 class=bg-success>".'Mail has been Moved For Approval'."</h4>"));
                }
              
              return $this->redirect(array('action' => 'view_sales'));
            }
            else
            {
              $this->Session->setFlash(__("<h4 class=bg-success>".'Mail has Not been Send'."</h4>"));
            }
            
        }
    }
    
    public function view_pdf()
    {
        ini_set('memory_limit', '512M');
        $Id = $this->params->query['Id'];
        $this->set('Id',$Id);
        $seArr=$this->ProspectClient->find("first",array('conditions'=>array('Id'=>$Id)));
        $pdArr=$this->ProspectProduct->find("first",array('conditions'=>array('Id'=>$seArr['ProspectClient']['ProductId'])));
        
        $this->set('ProductAdd',$seArr['ProspectClient']['Address1'].$seArr['ProspectClient']['Address2'].$seArr['ProspectClient']['Address3'].$seArr['ProspectClient']['Address4'].$seArr['ProspectClient']['Address5']);
        
        $this->set('Cover',$seArr['ProspectClient']['Cover']);
        $this->set('ClientName',$seArr['ProspectClient']['ClientName']);
        $this->set('ProductName',$pdArr['ProspectProduct']['ProductName']);
        $this->set('ProductLogo',$seArr['ProspectClient']['logo_file']);
    }
    
    public function view_report()
    {
        $this->layout="home";
        if($this->Session->read('userid')=='19')
        {
            $childUser="";
        }
        else
        {
            $childUser = " and tu.username in ('".implode("','",$this->Session->read("childUser"))."')";
        }
        $this->set('product_master',array("All"=>"All") + $this->ProspectProduct->find('list',array('fields'=>array('Id','ProductName'),
            'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        
        //print_r($this->request->data); exit;
        if($this->request->is('POST'))
        {
            $qry = "";
            $data = $this->request->data['prospects'];
            if($data['ProductId']!='All')
            {
                $qry .= " and pc.ProductId ='".$data['ProductId']."'";
            }
            if(!empty($data['DateFrom']) && !empty($data['DateTo']))
            {
                $start_date = explode("-",$data['DateFrom']);
                $start_date1[0] = $start_date[2];
                $start_date1[1] = $start_date[1];
                $start_date1[2] = $start_date[0];
                $start_date = implode("-",$start_date1);
                
                $end_date = explode("-",$data['DateTo']);
                $end_date1[0] = $end_date[2];
                $end_date1[1] = $end_date[1];
                $end_date1[2] = $end_date[0];
                $end_date = implode("-",$end_date1);
                $qry .= " and DATE(pc.CreateDate) BETWEEN '$start_date' AND '$end_date'";
            }
        }
        
        
        $this->set("data",$this->ProspectClient->query("SELECT *,get_company_code(pp.company_id)company,get_branch_name(pp.branch_id)branch FROM prospect_client pc INNER JOIN prospect_product pp ON pc.ProductId = pp.Id
        LEFT JOIN tbl_user tu ON pc.CreateBy=tu.Id 
        LEFT JOIN prospect_email_config pec ON pc.SenderEmail = pec.Id
        LEFT JOIN prospect_lead_source pls ON pc.LeadSource = pls.Id
        WHERE 1=1  $qry "));
    }

    public function view_report_prospect_wise()
    {
        $this->layout="home";
        $Id = $this->params->query['Id'];
        
        $this->set("data1",$this->ProspectClient->query("SELECT *,get_company_code(pp.company_id)company,get_branch_name(pp.branch_id)branch FROM prospect_client pc INNER JOIN prospect_product pp ON pc.ProductId = pp.Id
        LEFT JOIN tbl_user tu ON pc.CreateBy=tu.Id 
        LEFT JOIN prospect_email_config pec ON pc.SenderEmail = pec.Id
        LEFT JOIN prospect_lead_source pls ON pc.LeadSource = pls.Id
        WHERE ProspectUniqueNo is not null and ProspectUniqueNo!=''  and pc.Id='$Id' "));
        
        $this->set("data2",$this->ProspectClient->query("SELECT *,get_company_code(pp.company_id)company,get_branch_name(pp.branch_id)branch FROM prospect_client_history pc INNER JOIN prospect_product pp ON pc.ProductId = pp.Id
        LEFT JOIN tbl_user tu ON pc.CreateBy=tu.Id 
        LEFT JOIN prospect_email_config pec ON pc.SenderEmail = pec.Id
        LEFT JOIN prospect_lead_source pls ON pc.LeadSource = pls.Id
        WHERE ProspectUniqueNo is not null and ProspectUniqueNo!=''  and pc.DataId='$Id' group by pc.ProspectUniqueNo order by pc.Id desc"));
    }
    public function export_report()
    {
        $this->layout="ajax";
        if($this->Session->read('userid')=='19')
        {
            $childUser="";
        }
        else
        {
            $childUser = " and tu.username in ('".implode("','",$this->Session->read("childUser"))."')";
        }
            $qry = "";
            $data = $this->params->query;
            if($data['ProductId']!='All')
            {
                $qry .= " and pc.ProductId ='".$data['ProductId']."'";
            }
            if(!empty($data['DateFrom']) && !empty($data['DateTo']))
            {
                $start_date = explode("-",$data['DateFrom']);
                $start_date1[0] = $start_date[2];
                $start_date1[1] = $start_date[1];
                $start_date1[2] = $start_date[0];
                $start_date = implode("-",$start_date1);
                
                $end_date = explode("-",$data['DateTo']);
                $end_date1[0] = $end_date[2];
                $end_date1[1] = $end_date[1];
                $end_date1[2] = $end_date[0];
                $end_date = implode("-",$end_date1);
                $qry .= " and DATE(pc.CreateDate) BETWEEN '$start_date' AND '$end_date'";
            }
        
        $this->set('data',$this->ProspectClient->query("SELECT *,get_company_code(pp.company_id)company,get_branch_name(pp.branch_id)branch FROM prospect_client pc INNER JOIN prospect_product pp ON pc.ProductId = pp.Id
        LEFT JOIN prospect_follow pf ON pc.Id = pf.Id  LEFT JOIN tbl_user tu ON pc.CreateBy=tu.Id 
        LEFT JOIN prospect_email_config pec ON pc.SenderEmail = pec.Id
        LEFT JOIN prospect_lead_source pls ON pc.LeadSource = pls.Id
        WHERE 1=1  $qry $childUser"));
    }
    public function email_config() 
	{
            $this->set('email_master', $this->ProspectEmail->query("SELECT Id,Email_Host,Email_Port,Email_Id,active FROM `prospect_email_config` as ProspectEmail"));
            $this->layout='home';
            if ($this->request->is('post')) 
            {
                //print_r($this->request->data); exit;
                $email_ID= addslashes($this->request->data['prospects']['Email_Id']);
                $Email['Email_Password'] = $this->request->data['prospects']['Email_Password'];
                $Email['Email_Port'] = $this->request->data['prospects']['Email_Port'];
                $Email['Email_Host'] = $this->request->data['prospects']['Email_Host'];
                $Email['Email_Id'] = $email_ID;
                $Email['CreateDate'] = date('Y-m-d H:i:s');
                $Email['CreateBy'] = $this->Session->read("userid");
                
                
                if(!$this->ProspectEmail->find('first',array('conditions'=>array('Email_Id'=>$email_ID))))
                {
                    if ($this->ProspectEmail->save($Email))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>The Email has been saved</h4>"
                                . "<script>alertify.success('The Email has been saved');</script>"));
                        
                    }
                    else
                    {
                        $this->Session->setFlash(__("<h4 class=bg-danger>The Email could not be saved. Please, try again.</font>"
                                . "<script>alertify.error('The Email could not be saved. Please, try again.');</h4>"));
                    }
                }
                else
                {
                    $this->Session->setFlash(__("<h4 class=bg-danger>The Email Already Exists.</h4>"
                            . "<script>alertify.error('The Email Already Exists.');</script>"));
                }
                return $this->redirect(array('action' => 'email_config'));
            }
            
        }
         
    public function email_config_edit() 
    {
        $this->layout='home';
        $id  = $this->request->query['Id'];
        if ($this->request->is('post')) 
        {
            $data = $this->request->data['prospects'];
            
            if(!$this->ProspectEmail->find('first',array('conditions'=>array('Email_Id'=>$data['Email_Id'],'not'=>array('Id'=>$id)))))
            {
                $Email_Id = $data['Email_Id'];
                $Email_Password = $data['Email_Password'];
                $Email_Port = $data['Email_Port'];
                $Email_Host = $data['Email_Host'];
                
                //$active = $data['active'];
                $data = array();
                
                $dataR['Email_Id'] = "'".addslashes($Email_Id)."'" ;
                $dataR['Email_Password'] = "'".$Email_Password."'";
                $dataR['Email_Port'] = "'".$Email_Port."'";
                $dataR['Email_Host'] = "'".$Email_Host."'";
                $dataR['ModifyBy'] = $this->Session->read("userid");
                $dataR['ModifyDate'] = "'".date('Y-m-d H:i:s')."'";

                if ($this->ProspectEmail->updateAll($dataR,array('id'=>$id)))
                {
                    $this->Session->setFlash(__("<h4 class=bg-success>".'The Email has been updated successfully'."</h4>"
                            . "<script>alertify.success('The Email has been updated successfully');</script>"));
                    
                }
                else
                {
                    $this->Session->setFlash(__("<h4 class=bg-danger>".'The Email could not be updated. Please, try again.'."</h4>"
                            . "<script>alertify.error('The Email could not be updated. Please, try again.');</script>"));
                }
            }
            else
            {
                $this->Session->setFlash(__("<h4 class=bg-danger>".'The Email allready exists. Please try again.'."</h4>"
                        . "<script>alertify.error('The Email allready exists. Please try again.');</script>"));
            }
          return $this->redirect(array('action' => 'email_config'));
        }
        else
        {
            
            $this->set('ProspectEmail',$this->ProspectEmail->find('first',array('conditions'=>array('id'=>$id))));
        }
        
    }
    
    public function lead_source_master() 
	{
            $this->set('lead_source_master', $this->ProspectLeadSource->query("SELECT * FROM prospect_lead_source ProspectLeadSource"));
            $this->layout='home';
            if ($this->request->is('post')) 
            {
                //print_r($this->request->data); exit;
                $LeadSource= addslashes($this->request->data['prospects']['LeadSource']);
                $ProspectLeadSource['LeadSource'] = $LeadSource;
                $ProspectLeadSource['CreateDate'] = date('Y-m-d H:i:s');
                $ProspectLeadSource['CreateBy'] = $this->Session->read("userid");
                                
                if(!$this->ProspectLeadSource->find('first',array('conditions'=>array('LeadSource'=>$LeadSource))))
                {
                    if ($this->ProspectLeadSource->save($ProspectLeadSource))
                    {
                        $this->Session->setFlash(__("<h4 class=bg-success>The Source has been saved</h4>"
                                . "<script>alertify.success('The Source has been saved');</script>"));
                        
                    }
                    else
                    {
                        $this->Session->setFlash(__("<h4 class=bg-danger>The Source could not be saved. Please, try again.</font>"
                                . "<script>alertify.error('The Source could not be saved. Please, try again.');</h4>"));
                    }
                }
                else
                {
                    $this->Session->setFlash(__("<h4 class=bg-danger>The Source Already Exists.</h4>"
                            . "<script>alertify.error('The Source Already Exists.');</script>"));
                }
                return $this->redirect(array('action' => 'lead_source_master'));
            }
            
        }
    public function remove_attachment()
    {
        $this->layout="ajax";
        $Id = $this->request->data['Id'];
        $attachment = $this->request->data['attachmentno'];
        if($this->ProspectClient->updateAll(array($attachment=>"''"),array('Id'=>$Id)))
        {
            echo "1"; exit;
        }
        else
        {
            echo "0"; exit;
        }
        exit;
    }
}

?>