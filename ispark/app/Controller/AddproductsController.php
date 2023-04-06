<?php
class AddproductsController extends AppController 
{
    public $uses=array('Addproduct','SalesClient');
    public $components = array('RequestHandler');
		public $helpers = array('Js','Html');
	public function beforeFilter()
	{
        	parent::beforeFilter();
		$this->layout='home';
                if(!$this->Session->check("username"))
                {
                    return $this->redirect(array('controller'=>'users','action' => 'login'));
                }
                else
                {
                    
                    
                    $role=$this->Session->read("role");
                    $roles=explode(',',$this->Session->read("page_access"));

                    if(in_array('101',$roles)){$this->Auth->allow('index','save_sales','view_sales','create_cover','view_approve_sales','approve_sales','view_pdf');$this->Auth->allow('add');$this->Auth->allow('edit');}
                    else{$this->Auth->deny('index');$this->Auth->deny('add');$this->Auth->deny('edit');}
                }	
    	}
		
    	public function index() 
	{
            $this->set('product_master', $this->Addproduct->find('all',array('conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
            $this->layout='home';
        }
	   
       public function add() 
       {
            if ($this->request->is('post')) 
            {
                //print_r($this->request->data); exit;
                $product= addslashes($this->request->data['Addproduct']['ProductName']);
                $Product['ProductName'] = $product;
                $Product['CreateDate'] = date('Y-m-d H:i:s');
                $Product['CreateBy'] = $this->Session->read("userid");
                
                
                if(!$this->Addproduct->find('first',array('conditions'=>array('ProductName'=>$product))))
                {
                    if ($this->Addproduct->save($Product))
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
        if ($this->request->is('post')) 
        {
            $data = $this->request->data['Addproduct'];
            
            if(!$this->Addproduct->find('first',array('conditions'=>array('ProductName'=>$data['ProductName'],'not'=>array('Id'=>$data['Id'])))))
            {
                $product = $data['ProductName'];
                $Id = $data['Id'];
                $active = $data['active'];
                $data = array();
                
                $data['ProductName'] = "'".addslashes($product)."'" ;
                $data['active'] = $active;
                $data['ModifyBy'] = $this->Session->read("userid");
                $data['ModifyDate'] = "'".date('Y-m-d H:i:s')."'";

                if ($this->Addproduct->updateAll($data,array('id'=>$Id)))
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
            $this->set('product_master',$this->Addproduct->find('first',array('conditions'=>array('id'=>$id))));
        }
        
    }
    
    public function save_sales() 
    {
        $this->set('product_master', $this->Addproduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        if($this->request->is('POST'))
        {
          //print_r($this->request->data); exit; 
          $request = $this->request->data['Addproduct'];
          //$data['ProductId'] = implode(",",$request['ProductId']);
          $data['ProductId'] = $request['ProductId'];
          $data['Introduction'] = addslashes($request['Introduction']);
          $data['ClientName'] = addslashes($request['ClientName']);
          $data['ContactNo'] = addslashes($request['ContactNo']);
          $data['Email'] = addslashes($request['Email']);
          $data['Address'] = addslashes($request['Address']);
          $data['Remarks'] = addslashes($request['Remarks']);
          $data['CreateBy'] = $this->Session->read("userid");
          $data['CreateDate'] = date("Y-m-d H:i:s");
          //$data['Product'] = date("Y-m-d H:i:s");
          
          if($this->SalesClient->save($data))
          {
              $this->Session->setFlash(__("<h4 class=bg-success>".'The Record has been Saved successfully'."</h4>"
                      . "<script>alertify.success('The Record has been Saved successfully');</script>"));
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
        $this->set('product_master', $this->Addproduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        
        if($this->request->is("post"))
        {
            $search = $this->request->data['Addproduct'];
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
        
        $this->set('sales_master', $this->SalesClient->query("SELECT * FROM `sales_client` sc INNER JOIN `sales_product` sp ON sc.ProductId = sp.Id WHERE  1=1 $qry "));
    }
    public function create_cover() 
    {
        $Id = $this->params->query['Id'];
        $this->set('SC', $this->SalesClient->find('first',array('conditions'=>array("Id"=>$Id))));
        
        if($this->request->is("post"))
        {
            $Id = $this->request->data['Addproduct']['Id'];
            
            $data['EmailTo']    = "'".addslashes($this->request->data['Addproduct']['EmailTo'])."'";
            $data['EmailCC']    = "'".addslashes($this->request->data['Addproduct']['EmailCC'])."'";
            $data['EmailSub']   = "'".addslashes($this->request->data['Addproduct']['EmailSub'])."'";
            $data['Cover']      = "'".addslashes($this->request->data['Addproduct']['Cover'])."'";
            $data['SendBy'] = $this->Session->read("userid");
            $data['SendDate'] = "'".date("Y-m-d H:i:s")."'";
            $data['Active'] = "2";
            
            if($this->SalesClient->updateAll($data,array('Id'=>$Id)))
            {
                if($this->request->data['Addproduct']['EmailTo']=='EOI')
                {
                    App::uses('sendEmail', 'custom/Email');
                    $to = explode(",",$this->request->data['Addproduct']['EmailTo']);
                    $cc = explode(",",$this->request->data['Addproduct']['EmailCC']);
                    $sub = $this->request->data['Addproduct']['EmailSub'];
                    $body = $this->request->data['Addproduct']['Cover'];
                    $mail = new sendEmail();
                    //$mail-> to($email2,$cc,$body,$sub);	
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
    public function view_approve_sales() 
    {
        $this->set('product_master', $this->Addproduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        
        if($this->request->is("post"))
        {
            if(!empty($this->request->data['Search']))
            {    
                $search = $this->request->data['Addproduct'];
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
                foreach($_POST['check'] as $v)
                {
                    
                    $this->SalesClient->updateAll(array('IntroApprove'=>"1",'ApproveBy'=>$this->Session->read("userid")),array("Id"=>$v));
                }
                $this->Session->setFlash(__("<h4 class=bg-success>".'Record Approved successfully'."</h4>"));
                return $this->redirect(array('action' => 'view_approve_sales'));
            }
            else if(!empty($this->request->data['DisApprove']))
            {
                
                foreach($_POST['check'] as $v)
                {
                    if($this->SalesClient->updateAll(array('IntroApprove'=>"2",'ApproveBy'=>$this->Session->read("userid")),array("Id"=>$v)))
                    {
                        $data = $this->SalesClient->find('first',array('conditions'=>array('Id'=>$v)));
                        App::uses('sendEmail', 'custom/Email');
                        $to = explode(",",$data['SalesClient']['EmailTo']);
                        $cc = explode(",",$data['SalesClient']['EmailCC']);
                        $sub = $data['SalesClient']['EmailSub'];
                        $body = $data['SalesClient']['Cover'];
                        $mail = new sendEmail();
                    }
                }
                $this->Session->setFlash(__("<h4 class=bg-success>".'Record DisApproved successfully'."</h4>"));
                return $this->redirect(array('action' => 'view_approve_sales'));
            }
        }
        
        $this->set('sales_master', $this->SalesClient->query("SELECT * FROM `sales_client` sc INNER JOIN `sales_product` sp ON sc.ProductId = sp.Id WHERE  sc.Active='1' $qry AND
IF(Introduction ='Commercial' and IntroApprove=0,TRUE,FALSE)"));
    }
    public function approve_sales() 
    {        
        $Id = $this->params->query['Id'];
        $this->set('product_master', $this->Addproduct->find('list',array('fields'=>array('Id','ProductName'),'conditions'=>array('active'=>1),'order' => array('ProductName' => 'asc'))));
        $this->set('SC', $this->SalesClient->query("SELECT * FROM `sales_client` sc 
INNER JOIN `sales_product` sd ON sc.ProductId = sd.Id
LEFT JOIN `sales_follow` sf ON sc.LastId = sf.Id Where Id='$Id' limit 1"));
        if($this->request->is("post"))
        {
            $Id = $this->request->data['Addproduct']['Id'];
            
            $data['EmailTo']    = "'".addslashes($this->request->data['Addproduct']['EmailTo'])."'";
            $data['EmailCC']    = "'".addslashes($this->request->data['Addproduct']['EmailCC'])."'";
            $data['EmailSub']   = "'".addslashes($this->request->data['Addproduct']['EmailSub'])."'";
            $data['Cover']      = "'".addslashes($this->request->data['Addproduct']['Cover'])."'";
            $data['SendBy'] = $this->Session->read("userid");
            $data['SendDate'] = "'".date("Y-m-d H:i:s")."'";
            $data['Active'] = "2";
            
            if($this->SalesClient->updateAll($data,array('Id'=>$Id)))
            {
                if($this->request->data['Addproduct']['EmailTo']=='EOI')
                {
                    App::uses('sendEmail', 'custom/Email');
                    $to = explode(",",$this->request->data['Addproduct']['EmailTo']);
                    $cc = explode(",",$this->request->data['Addproduct']['EmailCC']);
                    $sub = $this->request->data['Addproduct']['EmailSub'];
                    $body = $this->request->data['Addproduct']['Cover'];
                    $mail = new sendEmail();
                    //$mail-> to($email2,$cc,$body,$sub);	
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
    }
}

?>