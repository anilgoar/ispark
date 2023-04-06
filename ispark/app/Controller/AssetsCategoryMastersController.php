<?php
class AssetsCategoryMastersController extends AppController {
    public $uses = array(
        'Addbranch','CostCenterMaster','AssetsCategoryMasters','AssetsFormMaster','AssetsDropdownMaster','AssetsDetailsMaster','AssetsStocksMaster',
        'AssetsProblemMaster','AssetsTicketCreationMaster','Masjclrentry','AssetsProductMaster'
        );
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
                'index','delete_row','delete_sub_row','assets_sub_category','get_category','get_sub_category','add_assets_details',
                'get_form','view_assets_master','assets_dropdown_master','get_field_list','view_option_master','delete_option_master',
                'delete_assets_details_master','upload_assets_stocks','download_assets_stocks','allocate_assets_stocks','view_assets_stocks',
                'ticket_creation','get_serial_no_list','get_assets_problem_list','get_assets_process','ticket_solution','show_ticket_details',
                'not_working_assets','restore_not_working_assets','print_label','download_assets_data','ticket_solution_report'
            );
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $this->set('DataArr',$this->AssetsCategoryMasters->find('all',array('conditions'=>array("Parent_Id IS NULL ORDER BY Category"))));
        
        $AssetsData =   array();
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $AssetsCat  =   $this->AssetsCategoryMasters->find('first',array('conditions'=>array("Id='{$_REQUEST['Id']}'")));
            $AssetsData =   $AssetsCat['AssetsCategoryMasters'];
            $this->set('data',$AssetsData);
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Category       =   trim(addslashes($request['Category']));
            $Submit         =   $request['Submit'];
            
            if($Submit =="Submit"){
                
                $data=array(
                    'Category'=>$Category
                );

                $Exist  =   $this->AssetsCategoryMasters->find('all',array('conditions'=>$data));

                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This category already exist in database.</span>');
                    $this->redirect(array('action'=>'index'));
                }
                else{
                    if($this->AssetsCategoryMasters->save($data)){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Category save successfully.</span>');
                        $this->redirect(array('action'=>'index'));
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Category not save please try again later.</span>');
                        $this->redirect(array('action'=>'index'));
                    }
                }
            }
            else if($Submit =="Update"){
                $Id     =   $request['Id'];
                $Exist  =   $this->AssetsCategoryMasters->find('all',array('conditions'=>array("Id !='$Id' AND Category='$Category'")));
                
                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This category already exist in database.</span>');
                    $this->redirect(array('action'=>'index','?'=>array('Id'=>$Id)));
                }
                else{
                    $this->AssetsCategoryMasters->query("UPDATE Assets_Category_Masters SET Category='$Category' WHERE Id='$Id'");
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Category update successfully.</span>');
                    $this->redirect(array('action'=>'index','?'=>array('Id'=>$Id)));
                }
            }   
        }
    }
    
    public function assets_sub_category(){
        $this->layout='home';
        
        $this->set('CategoryList',$this->AssetsCategoryMasters->find('list',array('fields'=>array('Id','Category'),'conditions'=>array("Parent_Id IS NULL ORDER BY Category ASC"))));
        
        $AssetsArr  =   $this->AssetsCategoryMasters->find('all',array('conditions'=>array("Parent_Id IS NULL ORDER BY Category")));
        
        $DataArr=array();
        foreach($AssetsArr as $row_new){
            $row    =   $row_new['AssetsCategoryMasters'];
            
            $DataArr[$row['Id']]=array('Id'=>$row['Id'],'Category'=>$row['Category'],'Parent_Category'=>array());
            
            $CatArr  =   $this->AssetsCategoryMasters->find('all',array('conditions'=>array("Parent_Id='{$row['Id']}'")));
            
            foreach($CatArr as $val_new){
               $val    =   $val_new['AssetsCategoryMasters'];
               $DataArr[$row['Id']]['Parent_Category'][]=array('Id'=>$val['Id'],'Category'=>$val['Category']);
            } 
        }

        $this->set('DataArr',$DataArr);
        
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $AssetsCat  =   $this->AssetsCategoryMasters->find('first',array('conditions'=>array("Id='{$_REQUEST['Id']}'")));
            $this->set('data',$AssetsCat['AssetsCategoryMasters']);
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Parent_Id      =   $request['Parent_Id'];
            $Category       =   $request['Category'];
            $Submit         =   $request['Submit'];
            
            if($Submit =="Submit"){
                
                $data=array(
                    'Category'=>$Category,
                    'Parent_Id'=>$Parent_Id
                );
                
                $Exist  =   $this->AssetsCategoryMasters->find('all',array('conditions'=>$data));

                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This category already exist in database.</span>');
                    $this->redirect(array('action'=>'assets_sub_category'));
                }
                else{
                    if($this->AssetsCategoryMasters->save($data)){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Category save successfully.</span>');
                        $this->redirect(array('action'=>'assets_sub_category'));
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Category not save please try again later.</span>');
                        $this->redirect(array('action'=>'assets_sub_category'));
                    }
                }
                
            }
            else if($Submit =="Update"){
                $Id     =   $request['Id'];
                $Exist  =   $this->AssetsCategoryMasters->find('all',array('conditions'=>array("Id !='$Id' AND Category='$Category' AND Parent_Id='$Parent_Id'")));
                
                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This category already exist in database.</span>');
                    $this->redirect(array('action'=>'assets_sub_category','?'=>array('Id'=>$Id)));
                }
                else{
                    $this->AssetsCategoryMasters->query("UPDATE Assets_Category_Masters SET Category='$Category',Parent_Id='$Parent_Id' WHERE Id='$Id'");
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Category update successfully.</span>');
                    $this->redirect(array('action'=>'assets_sub_category','?'=>array('Id'=>$Id)));
                }
            }   
        }
    }
    
    public function get_sub_category(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Parent_Id      =   $request['Parent_Id'];
            $Value          =   $request['Value'];            
            $conditoin      =   array("Parent_Id='$Parent_Id' ORDER BY Category ASC");
            $data           =   $this->AssetsCategoryMasters->find('list',array('fields'=>array('Id','Category'),'conditions'=>$conditoin));
            
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    $selected   =   $Value ==$key?"selected='selected'":"";
                    echo "<option $selected  value='$key'>$val</option>";
                } 
            } 
            die;
        }
    }

    public function delete_row(){
        if($_REQUEST['Id']){
            $Id     =   $_REQUEST['Id'];
            
            $this->AssetsCategoryMasters->query("DELETE FROM `Assets_Category_Masters` WHERE Parent_Id='$Id'");
            $this->AssetsCategoryMasters->query("DELETE FROM `Assets_Category_Masters` WHERE Id='$Id'");
            $this->redirect(array('action'=>'index')); 
        }
    }
    
    public function delete_sub_row(){
        if($_REQUEST['Id']){
            $Id     =   $_REQUEST['Id'];
            
            $this->AssetsCategoryMasters->query("DELETE FROM `Assets_Category_Masters` WHERE Parent_Id='$Id'");
            $this->AssetsCategoryMasters->query("DELETE FROM `Assets_Category_Masters` WHERE Id='$Id'");
            $this->redirect(array('action'=>'assets_sub_category')); 
        }
    }
    
    public function add_assets_details(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $this->set('CategoryList',$this->AssetsCategoryMasters->find('list',array('fields'=>array('Id','Category'),'conditions'=>array("Parent_Id IS NULL ORDER BY Category ASC"))));
        
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Branch     =   $request['AssetsCategoryMasters']['branch_name'];
            $Parent_Id  =   $request['Parent_Id'];
            $Category   =   $request['Category'];
            $Create_By  =   $this->Session->read('email');
            $Id         =   isset($request['Id']) && $request['Id'] !=""?$request['Id']:'';
            
            if($Id ==""){
                $data=array(
                    'Branch'=>$Branch,
                    'Category_Id'=>$Parent_Id,
                    'Sub_Category_Id'=>$Category,
                    'Create_By'=>$Create_By
                    );

                $qry        =   "SELECT GROUP_CONCAT(Field_Name) AS Field_Name  FROM `Assets_Form_Master` WHERE Sub_Category_Id='$Category' ORDER BY Field_Order";
                $Fields     =   $this->AssetsFormMaster->query($qry);

                foreach(explode(",", $Fields[0][0]['Field_Name']) as $Field){
                    $data[$Field]=$request[$Field];
                }

                if($this->AssetsDetailsMaster->save($data)){
                    echo "<span id='msgerr' style='color:green;font-weight:bold;margin-left:20px;' >Data save successfully.</span>";die;
                }
                else{
                    echo "<span id='msgerr' style='color:red;font-weight:bold;margin-left:20px;' >Data not save.</span>";die;
                }
            }
            else{
                $data=array(
                    'Update_Date'=>"'".date('Y-m-d H:i:s')."'",
                    'Update_By'=>"'".$Create_By."'", 
                    );
                
                $qry        =   "SELECT GROUP_CONCAT(Field_Name) AS Field_Name  FROM `Assets_Form_Master` WHERE Sub_Category_Id='$Category' ORDER BY Field_Order";
                $Fields     =   $this->AssetsFormMaster->query($qry);

                foreach(explode(",", $Fields[0][0]['Field_Name']) as $Field){
                    $data[$Field]="'".$request[$Field]."'";
                }
                
                if($this->AssetsDetailsMaster->updateAll($data,array('Id'=>$Id))){
                    echo "<span id='msgerr' style='color:green;font-weight:bold;margin-left:20px;' >Data update successfully.</span>";die;
                }
                else{
                    echo "<span id='msgerr' style='color:red;font-weight:bold;margin-left:20px;' >Data not update.</span>";die;
                }
            }
        }
    }
    
    public function get_form(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $BranchName     =   $request['BranchName'];
            $Category_Id    =   $request['Category_Id'];
            $Row_Id         =   $request['Row_Id'];
            
            
            $conditoin      =   array("Sub_Category_Id='$Category_Id' ORDER BY Field_Order ASC");
            $FieldArr       =   $this->AssetsFormMaster->find('all',array('conditions'=>$conditoin));
            //$Field_List     =   $this->AssetsFormMaster->find('list',array('fields'=>array('Field_Label','Field_Name'),'conditions'=>array("Sub_Category_Id='$Category_Id' ORDER BY Field_Order")));
            $DivIdArr       =   $this->AssetsFormMaster->query("SELECT GROUP_CONCAT('#',Field_Name) AS Field_Name  FROM `Assets_Form_Master` WHERE Sub_Category_Id='$Category_Id' ORDER BY Field_Order");
            $DivId          =   $DivIdArr[0][0]['Field_Name'];
            
            if(!empty($FieldArr)){
                $AssetsRow  =   $this->AssetsDetailsMaster->find('first',array('conditions'=>array("Id='$Row_Id' ORDER BY Id ASC")));
                $Data_Row   =   $AssetsRow['AssetsDetailsMaster']; 
                ?>
                <div class="col-sm-12" id="hr"><hr/></div>
                <script language="javascript">
                    $(function () {
                        $(".textdatepicker").datepicker1({
                            changeMonth: true,
                            changeYear: true
                        });
                    });
                    
                    function submitForm(form){
                        $("#msgerr").remove();
                        
                        var formData    =   $(form).serialize();
                        var BranchName  =   $("#BranchName").val();
                        var Parent_Id   =   $("#Parent_Id").val();
                        var Category    =   $("#Category").val();
                        
                        if(BranchName ===""){
                            $("#BranchName").focus();
                            $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
                            return false;
                        }
                        if(Parent_Id ===""){
                            $("#Parent_Id").focus();
                            $("#Parent_Id").after("<span id='msgerr' style='color:red;'>Select category name.</span>");
                            return false;
                        }
                        if(Category ===""){
                            $("#Category").focus();
                            $("#Category").after("<span id='msgerr' style='color:red;'>Enter sub category.</span>");
                            return false;
                        }
                        
                        <?php foreach ($FieldArr as $far){$frow = $far['AssetsFormMaster'];?>
                        <?php if($frow['Field_Required'] =='required'){?>
                        if($("#<?php echo $frow['Field_Name']?>").val() ===""){
                            $("#<?php echo $frow['Field_Name']?>").focus();
                            $("#hr").before("<span id='msgerr' style='color:red;font-weight:bold;margin-left:20px;' ><?php echo $frow['Field_Label']?> Field Is Required</span>");
                            return false;
                        }
                        <?php }?>
                        
                        <?php if($frow['Condition_Required'] =='required'){foreach(explode(",", $frow['Condition_Field']) as $cf){?>
                        if($("#<?php echo $frow['Field_Name']?>").val() ==="<?php echo $frow['Condition_Value']?>" && $("#<?php echo $cf?>").val() ===""){
                            $("#<?php echo $cf?>").focus();
                            $("#hr").before("<span id='msgerr' style='color:red;font-weight:bold;margin-left:20px;' >This Field Is Required.</span>");
                            return false;
                        }
                        <?php }}?>
                            
                        <?php }?>
                        
                        
                        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/add_assets_details",formData).done(function(data){
                            $("<?php echo $DivId;?>").val('');
                            $("#hr").before(data);
                            view_assets_master(Category,BranchName);
                        });
                    }
                    
                </script>
                <?php
                foreach ($FieldArr as $row){
                    $Field              =   $row['AssetsFormMaster'];
                    $Id                 =   $Field['Id'];
                    $Field_Label        =   $Field['Field_Label'];
                    $Field_Type         =   $Field['Field_Type'];
                    $Field_Name         =   $Field['Field_Name'];
                    $Field_Required     =   $Field['Field_Required'];
                    $Col_Span_1         =   $Field['Col_Span_1'];
                    $Col_Span_2         =   $Field['Col_Span_2'];
                    $Validation         =   $Field['Validation'];
                    $Max_Length         =   $Field['Max_Length'];
                              
                    $Number             =   $Validation =="number"?'onkeypress="return isNumberKey(event,this)"':'';
                    $Decimal            =   $Validation =="decimal"?'onkeypress="return isNumberDecimalKey(event,this)"':'';
                    $Date_Picker        =   $Validation =="datepicker"?'textdatepicker':'';
                    $Max_Len            =   $Max_Length !=""?"maxlength='$Max_Length'":'';
                   
                    echo '<label class="col-sm-'.$Col_Span_1.'" control-label">'.$Field_Label.'</label>';
                    echo '<div class="col-sm-'.$Col_Span_2.'">';
                    
                    if($Field_Type =="text"){
                        echo "<input type='text' name='$Field_Name' id='$Field_Name' value='{$Data_Row[$Field_Name]}' $Number $Decimal $Max_Len class='form-control $Date_Picker' >";
                    }
                    
                    if($Field_Type =="select"){
                        $OptionList =   $this->AssetsDropdownMaster->find('list',array('fields'=>array('Id','Option'),'conditions'=>array("Assets_Form_Id='$Id' ORDER BY Id ASC")));
                        
                        echo "<select name='$Field_Name' id='$Field_Name' $Field_Required class='form-control'>";
                        echo "<option value=''>Select</option>";
                        foreach($OptionList as $key=>$val){
                            $selected   =   $Data_Row[$Field_Name] ==$val?"selected='selected'":"";
                            echo "<option $selected value='$val'>$val</option>"; 
                        }
                        echo "</select>";
                    }
                    
                    echo '</div>';
                }
                echo '<div class="col-sm-12">';
                
                if(!empty($Data_Row)){
                    echo '<input type="hidden" name="Id" value="'.$Data_Row['Id'].'" >';  
                }
                
                echo '<input type="button" onclick="submitForm(this.form)" name="Submit" id="Submit" value="Submit"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">';
                
                echo '</div>';
            } 
            die;
        }
    }

    public function view_assets_master(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $BranchName     =   $request['BranchName'];
            $Category_Id    =   $request['Category_Id'];
            
            $Fields =$this->AssetsFormMaster->find('list',array('fields'=>array('Field_Label','Field_Name'),'conditions'=>array("Sub_Category_Id='$Category_Id' ORDER BY Field_Order")));
            $DataArr=$this->AssetsDetailsMaster->find('all',array('conditions'=>array("Branch='$BranchName' AND Sub_Category_Id='$Category_Id' ORDER BY Id ASC")));
            ?>
            <?php if(!empty($DataArr)){?>
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th>SrNo</th>
                        <?php foreach($Fields as $Label=>$Field){?>
                        <th><?php echo $Label;?></th>
                        <?php }?>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($DataArr as $val){$row=$val['AssetsDetailsMaster'];?>
                    <tr>
                        <td><?php echo $i++?></td>
                        <?php foreach($Fields as $Label=>$Field){?>
                        <td><?php echo $row[$Field]?></td>
                        <?php }?>
                        <td>
                            <span class='icon' ><i onclick="edit_assets_details_master('<?php echo $row['Id'];?>','<?php echo $row['Sub_Category_Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >mode_edit</i>
                            <span class='icon' ><i onclick="delete_assets_details_master('<?php echo $row['Id'];?>','<?php echo $row['Branch'];?>','<?php echo $row['Sub_Category_Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php }?>
            <?php
            die;
        }
    }
    
    public function assets_dropdown_master(){
        $this->layout='home';
        
        $this->set('CategoryList',$this->AssetsCategoryMasters->find('list',array('fields'=>array('Id','Category'),'conditions'=>array("Parent_Id IS NULL ORDER BY Category ASC"))));

        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Parent_Id      =   trim(addslashes($request['Parent_Id']));
            $Category       =   trim(addslashes($request['Category']));
            $Assets_Form_Id =   trim(addslashes($request['Field_Option']));
            $Option         =   trim(addslashes($request['Option']));
            $Create_By      =   $this->Session->read('email');
            $Submit         =   $request['Submit'];
            
            $data=array(
                'Assets_Form_Id'=>$Assets_Form_Id,
                'Option'=>$Option
                );
            
            $Exist  =   $this->AssetsDropdownMaster->find('all',array('conditions'=>$data));

            if(!empty($Exist)){
                echo "<span id='msgerr' style='color:red;'>Already exist.</span>";die; 
            }
            else{
                $data['Create_By']=$Create_By;
                if($this->AssetsDropdownMaster->save($data)){
                    echo "<span id='msgerr' style='color:green;'>Option add successfully.</span>";die; 
                }
            }
        }
    }
    
    public function get_field_list(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Category_Id    =   $request['Category_Id'];           
            $data           =   $this->AssetsFormMaster->find('list',array('fields'=>array('Id','Field_Label'),'conditions'=>array("Sub_Category_Id='$Category_Id' AND Field_Type='select' ORDER BY Field_Order")));
          
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    $selected   =   $Value ==$key?"selected='selected'":"";
                    echo "<option $selected  value='$key'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    public function view_option_master(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Assets_Form_Id =   $request['Assets_Form_Id'];
            
            $DataArr=$this->AssetsDropdownMaster->find('all',array('conditions'=>array("Assets_Form_Id='$Assets_Form_Id' ORDER BY Id ASC")));
            ?>
            <?php if(!empty($DataArr)){?>
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th>SrNo</th>
                        <th>Option</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($DataArr as $val){$row=$val['AssetsDropdownMaster'];?>
                    <tr>
                        <td><?php echo $i++?></td>
                        <td><?php echo $row['Option']?></td>
                        <td><span class='icon' ><i onclick="delete_option_master('<?php echo $row['Id'];?>','<?php echo $row['Assets_Form_Id']?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php }?>
            <?php
            die;
        }
    }
    
    public function delete_option_master(){
        if($_REQUEST['Id']){
            $Id     =   $_REQUEST['Id'];
            $this->AssetsDropdownMaster->query("DELETE FROM `Assets_Dropdown_Master` WHERE Id='$Id'");  
        }
        die;
    }
    
    public function delete_assets_details_master(){
        if($_REQUEST['Id']){
            $Id     =   $_REQUEST['Id'];
            $this->AssetsDetailsMaster->query("DELETE FROM `Assets_Details_Master` WHERE Id='$Id'");  
        }
        die;
    }
    
    public function upload_assets_stocks(){
        $this->layout   =   'home';
        $branchName     =   $this->Session->read('branch_name');
        
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $Product_List   =   $this->AssetsProductMaster->find('list',array('fields'=>array('Product_Name','Product_Name'),'conditions'=>array("Product_Status='1' ORDER BY Product_Name"))); 
        $this->set('Product_List',$Product_List);
           
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $BranchName     =   $request['AssetsCategoryMasters']['branch_name'];
            $Product        =   $request['Product'];
            $Create_By      =   $this->Session->read('email');
            
            $Branch_Code    =   $this->AssetsStocksMaster->query("SELECT branch_code FROM `branch_master` WHERE branch_name='$BranchName' AND active='1' AND branch_code !=''");
            $Branch         =   $Branch_Code[0]['branch_master']['branch_code'];          
            $File_Name      =   $Branch."-".$Product."-".date("d-m-Y");
            
            $csv_file       =   $_FILES['UploadFile']['tmp_name'];
            $FileTye        =   $_FILES['UploadFile']['type'];
            $info           =   explode(".",$_FILES['UploadFile']['name']);
            
            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/vnd.ms-exceltest' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE){
                    $filedata       =   fgetcsv($handle, 1000, ",");
                    $totalcolumn    =   count($filedata);
                    
                    if($totalcolumn ==3 && $Product =='TFT'){
                        $Serial_Ar      =   $this->AssetsStocksMaster->query("SELECT MAX(Serial_Id) AS Serial_Id FROM `Assets_Stocks_Master` WHERE Branch='$BranchName' AND Product='$Product'");
                        $Serial_Id      =   $Serial_Ar[0][0]['Serial_Id'] !=''?$Serial_Ar[0][0]['Serial_Id']:0;
                        $list_value     =   "";
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                            $Serial_Id  =   $Serial_Id+1;
                            $Brand      =   $data[0];
                            $Size       =   $data[1];
                            $Vender     =   $data[2];
                            $Serial_No  =   $Branch."-".$Product."-".$Serial_Id;
                            
                            if($list_value!=''){									
                                    $list_value=$list_value.",('".$BranchName."','".$Brand."','".$Product."','".$Size."','".$Vender."','".$Serial_No."','".$Serial_Id."','".$Create_By."')";
                            }
                            else{
                                                 $list_value="('".$BranchName."','".$Brand."','".$Product."','".$Size."','".$Vender."','".$Serial_No."','".$Serial_Id."','".$Create_By."')";
                            } 
                        }
                        
                        $this->AssetsStocksMaster->query("INSERT INTO Assets_Stocks_Master(`Branch`,`Brand`,`Product`,`Size`,`Vender`,`Serial_No`,`Serial_Id`,`Create_By`) values $list_value");
                        
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Product upload successfully for '.$BranchName.' Branch.</span>');
                        $this->redirect(array('action'=>'upload_assets_stocks'));
                    }
                    else if($totalcolumn ==11 && $Product =='CPU'){
                        $Serial_Ar      =   $this->AssetsStocksMaster->query("SELECT MAX(Serial_Id) AS Serial_Id FROM `Assets_Stocks_Master` WHERE Branch='$BranchName' AND Product='$Product'");
                        $Serial_Id      =   $Serial_Ar[0][0]['Serial_Id'] !=''?$Serial_Ar[0][0]['Serial_Id']:0;
                        $list_value     =   "";
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                            $Serial_Id          =   $Serial_Id+1;
                            $Mother_Board       =   $data[0];
                            $Processor          =   $data[1];
                            $Speed              =   $data[2];
                            $RAM_Slot_1         =   $data[3];
                            $RAM_Slot_2         =   $data[4];
                            $RAM_Slot_3         =   $data[5];
                            $RAM_Slot_4         =   $data[6];
                            $HDD_1              =   $data[7];
                            $HDD_2              =   $data[8];
                            $Operating_System   =   $data[9];
                            $Vender             =   $data[10];
                            $Serial_No          =   $Branch."-".$Product."-".$Serial_Id;
                            
                            if($list_value!=''){									
                                    $list_value=$list_value.",('".$BranchName."','".$Product."','".$Mother_Board."','".$Processor."','".$Speed."','".$RAM_Slot_1."','".$RAM_Slot_2."','".$RAM_Slot_3."','".$RAM_Slot_4."','".$HDD_1."','".$HDD_2."','".$Operating_System."','".$Vender."','".$Serial_No."','".$Serial_Id."','".$Create_By."')";
                            }
                            else{
                                                 $list_value="('".$BranchName."','".$Product."','".$Mother_Board."','".$Processor."','".$Speed."','".$RAM_Slot_1."','".$RAM_Slot_2."','".$RAM_Slot_3."','".$RAM_Slot_4."','".$HDD_1."','".$HDD_2."','".$Operating_System."','".$Vender."','".$Serial_No."','".$Serial_Id."','".$Create_By."')";
                            } 
                        }
                        
                        $this->AssetsStocksMaster->query("INSERT INTO Assets_Stocks_Master(`Branch`,`Product`,`Mother_Board`,`Processor`,`Speed`,`RAM_Slot_1`,`RAM_Slot_2`,`RAM_Slot_3`,`RAM_Slot_4`,`HDD_1`,`HDD_2`,`Operating_System`,`Vender`,`Serial_No`,`Serial_Id`,`Create_By`) values $list_value");
                        
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Stocks upload successfully for '.$BranchName.' Branch.</span>');
                        $this->redirect(array('action'=>'upload_assets_stocks'));
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your csv column does not match.</span>');
                        $this->redirect(array('action'=>'upload_assets_stocks'));
                    }
                }
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please upload only csv file.</span>');
                $this->redirect(array('action'=>'upload_assets_stocks'));
            }
        }  
    }
    
    public function download_assets_stocks(){
        if($_REQUEST['b'] !="" && $_REQUEST['p'] !=""){
            
            $BranchName     =   $_REQUEST['b'];
            $Product        =   $_REQUEST['p'];
            $Branch         =   str_replace(' ', '-', $BranchName);
            $File_Name      =   $Branch."-".$Product."-".date("d-m-Y");
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$File_Name.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $Stock_Arr=$this->AssetsStocksMaster->find('all',array('conditions'=>array("Branch='$BranchName' AND Product='$Product' AND Working_Status IS NULL ORDER BY Serial_Id ASC")));
            
            if($Product =='TFT'){
                echo "<table border='1'>";
                echo "<tr>";
                echo "<th>Branch Name</th>";
                echo "<th>Product Name</th>";
                echo "<th>Brand Name</th>";
                echo "<th>Size(Inch)</th>";
                echo "<th>Vender Name</th>";
                echo "<th>Serial No</th>";
                echo "<th>Process Name</th>";
                echo "<th>Upload Date</th>";
                echo "<th>Upload By</th>";
                echo "</tr>";

                foreach($Stock_Arr as $Rows){
                    $row    =   $Rows['AssetsStocksMaster'];
                    echo "<tr>";
                    echo "<td>".$row['Branch']."</td>";
                    echo "<td>".$row['Product']."</td>";
                    echo "<td>".$row['Brand']."</td>";
                    echo "<td>".$row['Size']."</td>";
                    echo "<td>".$row['Vender']."</td>";
                    echo "<td>".$row['Serial_No']."</td>";
                    echo "<td>".$this->PROCESSNAME($row['Process'])."</td>";
                    echo "<td>".date("d-M-Y",strtotime($row['Create_Date']))."</td>";
                    echo "<td>".$row['Create_By']."</td>";
                    echo "</tr>";
                }
            }
            else if($Product =='CPU'){
                echo "<table border='1'>";
                echo "<tr>";
                echo "<th>Branch Name</th>";
                echo "<th>Product Name</th>";
                echo "<th>Mother Board</th>";
                echo "<th>Processor</th>";
                echo "<th>Speed</th>";
                echo "<th>RAM Slot 1</th>";
                echo "<th>RAM Slot 2</th>";
                echo "<th>RAM Slot 3</th>";
                echo "<th>RAM Slot 4</th>";
                echo "<th>HDD 1</th>";
                echo "<th>HDD 2</th>";
                echo "<th>Operating System</th>";
                echo "<th>Vender Name</th>";
                echo "<th>Serial No</th>";
                echo "<th>Process Name</th>";
                echo "<th>Upload Date</th>";
                echo "<th>Upload By</th>";
                echo "</tr>";

                foreach($Stock_Arr as $Rows){
                    $row    =   $Rows['AssetsStocksMaster'];
                    echo "<tr>";
                    echo "<td>".$row['Branch']."</td>";
                    echo "<td>".$row['Product']."</td>";
                    echo "<td>".$row['Mother_Board']."</td>";
                    echo "<td>".$row['Processor']."</td>";
                    echo "<td>".$row['Speed']."</td>";
                    echo "<td>".$row['RAM_Slot_1']."</td>";
                    echo "<td>".$row['RAM_Slot_2']."</td>";
                    echo "<td>".$row['RAM_Slot_3']."</td>";
                    echo "<td>".$row['RAM_Slot_4']."</td>";
                    echo "<td>".$row['HDD_1']."</td>";
                    echo "<td>".$row['HDD_2']."</td>";
                    echo "<td>".$row['Operating_System']."</td>";
                    echo "<td>".$row['Vender']."</td>";
                    echo "<td>".$row['Serial_No']."</td>";
                    echo "<td>".$this->PROCESSNAME($row['Process'])."</td>";
                    echo "<td>".date("d-M-Y",strtotime($row['Create_Date']))."</td>";
                    echo "<td>".$row['Create_By']."</td>";
                    echo "</tr>";
                }
            }
            exit();
        }
    }
    
    public function allocate_assets_stocks(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $Process_List   =   $this->CostCenterMaster->find('list',array('fields'=>array('id','process_name'),'conditions'=>array("branch='$branchName' AND process_name !='' ORDER BY process_name")));
        $this->set('Process_List',$Process_List); 
        
        $Product_List   =   $this->AssetsProductMaster->find('list',array('fields'=>array('Product_Name','Product_Name'),'conditions'=>array("Product_Status='1' ORDER BY Product_Name"))); 
        $this->set('Product_List',$Product_List);
       

        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Branch     =   $request['AssetsCategoryMasters']['branch_name'];
            $Product    =   $request['Product'];
            $Process    =   $request['Process'];
            $selectAll  =   $request['selectAll'];
            $Allocate_By=   $this->Session->read('email');
   
            foreach($selectAll as $Id){
                $this->AssetsStocksMaster->query("UPDATE `Assets_Stocks_Master` SET `Process`='$Process',Allocate_Date=NOW(),Allocate_By='$Allocate_By' WHERE Id='$Id'");   
            }
            die;  
        }
    }
    
    public function view_assets_stocks(){
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $BranchName =   $request['BranchName'];
            $Product    =   $request['Product'];

            $Stock_Arr=$this->AssetsStocksMaster->find('all',array('conditions'=>array("Branch='$BranchName' AND Product='$Product' AND `Process` IS NULL ORDER BY Serial_Id ASC")));
            if(!empty($Stock_Arr)){
            ?>
            <div class="col-sm-12">
                <input type="button" onclick="allocate_stocks(this.form)" value="Submit"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
            </div>
            <div class="col-sm-12" id="view_assets_stocks" >
            <script>
            $(document).ready(function(){
                $("#select_all").change(function(){  //"select all" change
                    $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
                });

                //".checkbox" change
                $('.checkbox').change(function(){
                    //uncheck "select all", if one of the listed checkbox item is unchecked
                    if(false == $(this).prop("checked")){ //if this item is unchecked
                        $("#select_all").prop('checked', false); //change "select all" checked status to false
                    }
                    //check "select all" if all checkbox items are checked
                    if ($('.checkbox:checked').length == $('.checkbox').length ){
                        $("#select_all").prop('checked', true);
                    }
                });
            });
            </script>
            <?php
            if($Product =='TFT'){
                echo "<table class='table table-striped table-hover  responstable'>";
                echo "<thead>";
                echo "<tr>";
                echo '<th><input type="checkbox" id="select_all"/></th>';
                echo "<th>Brand Name</th>";
                echo "<th>Size(Inch)</th>";
                echo "<th>Vender Name</th>";
                echo "<th>Serial No</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach($Stock_Arr as $Rows){
                    $row    =   $Rows['AssetsStocksMaster'];
                    echo "<tr>";
                    echo "<td><center><input type='checkbox' name='selectAll[]' class='checkbox' value='{$row['Id']}' ></center></td>";
                    echo "<td>".$row['Brand']."</td>";
                    echo "<td>".$row['Size']."</td>";
                    echo "<td>".$row['Vender']."</td>";
                    echo "<td>".$row['Serial_No']."</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }
            else if($Product =='CPU'){
                echo "<table class='table table-striped table-hover  responstable' >";
                 echo "<thead>";
                echo "<tr>";
                echo '<th><input type="checkbox" id="select_all"/></th>';
                echo "<th>Mother Board</th>";
                echo "<th>Processor</th>";
                echo "<th>Speed</th>";
                echo "<th>RAM Slot 1</th>";
                echo "<th>RAM Slot 2</th>";
                echo "<th>RAM Slot 3</th>";
                echo "<th>RAM Slot 4</th>";
                echo "<th>HDD 1</th>";
                echo "<th>HDD 2</th>";
                echo "<th>Operating System</th>";
                echo "<th>Vender Name</th>";
                echo "<th>Serial No</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach($Stock_Arr as $Rows){
                    $row    =   $Rows['AssetsStocksMaster'];
                    echo "<tr>";
                    echo "<td><center><input type='checkbox' name='selectAll[]' class='checkbox' value='{$row['Id']}' ></center></td>";
                    echo "<td>".$row['Mother_Board']."</td>";
                    echo "<td>".$row['Processor']."</td>";
                    echo "<td>".$row['Speed']."</td>";
                    echo "<td>".$row['RAM_Slot_1']."</td>";
                    echo "<td>".$row['RAM_Slot_2']."</td>";
                    echo "<td>".$row['RAM_Slot_3']."</td>";
                    echo "<td>".$row['RAM_Slot_4']."</td>";
                    echo "<td>".$row['HDD_1']."</td>";
                    echo "<td>".$row['HDD_2']."</td>";
                    echo "<td>".$row['Operating_System']."</td>";
                    echo "<td>".$row['Vender']."</td>";
                    echo "<td>".$row['Serial_No']."</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }
            echo "</div>";
            }
            else{
                echo "<div class='col-sm-12' style='color:red;margin-left:15px;' >Record Not Found.<div>"; 
            }
            die;
        }
    }
    
    public function ticket_creation(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $Ticket_Data=array();
        $TicketArr  =   $this->AssetsTicketCreationMaster->find('all',array('conditions'=>array("branch='$branchName' ORDER BY Create_At")));
        foreach($TicketArr as $rows){
            $row    =   $rows['AssetsTicketCreationMaster'];
            $Ticket_Data[]=array(
                'Process'=>$this->PROCESSNAME($row['Process']),
                'Product'=>$row['Product'],
                'Serial_No'=>$row['Serial_No'],
                'Problem'=>$row['Problem'],
                'Remarks'=>$row['Remarks'],
                'Agent_Name'=>$row['Agent_Name'],
                'TL_Name'=>$row['TL_Name'],
                'Ticket_Status'=>$row['Ticket_Status'],
                'Create_At'=>date('d-M-Y H:i:s',strtotime($row['Create_At'])),
                'Update_At'=>$row['Update_At'] !=''?date('d-M-Y H:i:s',strtotime($row['Update_At'])):"",
                'Ticket_Status_Remarks'=>$row['Ticket_Status_Remarks'],
                'Replacement_Serial_No'=>$row['Replacement_Serial_No'],
                'Replacement_Reason'=>$row['Replacement_Reason'],
                );
        }
        
        
        $this->set('TicketArr',$Ticket_Data);

        $Process_List   =   $this->CostCenterMaster->find('list',array('fields'=>array('id','process_name'),'conditions'=>array("branch='$branchName' AND process_name !='' ORDER BY process_name")));
        $this->set('Process_List',$Process_List);
        
        $TL_List1   =   $this->Masjclrentry->find('list',array('fields'=>array('EmpName','EmpName'),'conditions'=>array("BranchName='$branchName' AND Desgination IN('ASSISTANT MANAGER','BRANCH MANAGER','TEAM LEADER') AND `Status`='1' ORDER BY EmpName")));
        $TL_List2   =   $this->Masjclrentry->find('list',array('fields'=>array('EmpName','EmpName'),'conditions'=>array("BranchName='$branchName' AND Dept='INFORMATION TECHNOLOGY' AND `Status`='1' ORDER BY EmpName")));
        
        $TL_List    =   array_merge($TL_List1,$TL_List2);
        $this->set('TL_List',$TL_List);
        
        $Product_List   =   $this->AssetsProductMaster->find('list',array('fields'=>array('Product_Name','Product_Name'),'conditions'=>array("Product_Status='1' ORDER BY Product_Name"))); 
        $this->set('Product_List',$Product_List);
        

        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Branch     =   $request['AssetsCategoryMasters']['branch_name'];
            $Process    =   $request['Process'];
            $Product    =   $request['Product'];
            $Serial_No  =   $request['Serial_No'];
            $Problem    =   $request['Problem'];
            $Remarks    =   $request['Remarks'];
            $Agent_Name =   $request['Agent_Name'];
            $TL_Name    =   $request['TL_Name'];
            $Create_By  =   $this->Session->read('email');
            
            $data=array(
                'Branch'=>$Branch,
                'Process'=>$Process,
                'Product'=>$Product,
                'Serial_No'=>$Serial_No,
                'Problem'=>$Problem,
                'Remarks'=>$Remarks,
                'Agent_Name'=>$Agent_Name,
                'TL_Name'=>$TL_Name,
                'Create_By'=>$Create_By
            );
            
            if($this->AssetsTicketCreationMaster->save($data)){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Ticket create successfully.</span>');
                $this->redirect(array('action'=>'ticket_creation'));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Ticket not create please try again later.</span>');
                $this->redirect(array('action'=>'ticket_creation'));
            }
        }
    }

    public function get_serial_no_list(){
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Branch     =   $request['BranchName'];  
            $Process    =   $request['Process'];
            $Product    =   $request['Product'];
            
            $data       =   $this->AssetsStocksMaster->find('list',array('fields'=>array('Id','Serial_No'),'conditions'=>array("Branch='$Branch' AND Product='$Product' AND Process='$Process' AND Working_Status IS NULL ORDER BY Serial_Id")));
          
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    echo "<option value='$val'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    public function get_assets_problem_list(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Product        =   $request['Product'];           
            $data           =   $this->AssetsProblemMaster->find('list',array('fields'=>array('Id','Name'),'conditions'=>array("Product_Name='$Product' ORDER BY Name")));
          
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    echo "<option value='$val'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    public function get_assets_process(){
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Branch     =   $request['BranchName'];           
            $data       =   $this->CostCenterMaster->find('list',array('fields'=>array('id','process_name'),'conditions'=>array("branch='$Branch' AND process_name !='' ORDER BY process_name")));
          
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    echo "<option value='$val'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    public function ticket_solution(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $Ticket_Data=array();
        $TicketArr  =   $this->AssetsTicketCreationMaster->find('all',array('conditions'=>array("branch='$branchName' ORDER BY Create_At")));
        foreach($TicketArr as $rows){
            $row    =   $rows['AssetsTicketCreationMaster'];
            $Ticket_Data[]=array(
                'Id'=>$row['Id'],
                'Process'=>$this->PROCESSNAME($row['Process']),
                'Product'=>$row['Product'],
                'Serial_No'=>$row['Serial_No'],
                'Problem'=>$row['Problem'],
                'Remarks'=>$row['Remarks'],
                'Agent_Name'=>$row['Agent_Name'],
                'TL_Name'=>$row['TL_Name'],
                'Ticket_Status'=>$row['Ticket_Status'],
                'Create_At'=>date('d-M-Y H:i:s',strtotime($row['Create_At'])),
                'Update_At'=>$row['Update_At'] !=''?date('d-M-Y H:i:s',strtotime($row['Update_At'])):"",
                'Ticket_Status_Remarks'=>$row['Ticket_Status_Remarks'],
                'Replacement_Serial_No'=>$row['Replacement_Serial_No'],
                'Replacement_Reason'=>$row['Replacement_Reason'],
                );
        }
        
        
        $this->set('TicketArr',$Ticket_Data);

        $Process_List   =   $this->CostCenterMaster->find('list',array('fields'=>array('id','process_name'),'conditions'=>array("branch='$branchName' AND process_name !='' ORDER BY process_name")));
        $this->set('Process_List',$Process_List);
        
        if($this->request->is('POST')){
            $request                =   $this->request->data;
            $Ticket_Id              =   $request['Id'];
            
            $Ticket_Data            =   $this->AssetsTicketCreationMaster->find('first',array('conditions'=>array("Id='$Ticket_Id'")));
            $Ticket_Row             =   $Ticket_Data['AssetsTicketCreationMaster'];
            
            $Branch                 =   $Ticket_Row['Branch'];
            $Product                =   $Ticket_Row['Product'];
            $Process                =   $Ticket_Row['Process'];
            $Serial_No              =   $Ticket_Row['Serial_No'];
            $Create_At              =   $Ticket_Row['Create_At'];
            
            $Ticket_Status          =   $request['Ticket_Status'];
            $Ticket_Status_Remarks  =   $request['Ticket_Status_Remarks'];
            $Update_By              =   $this->Session->read('email');
            
            $data=array(
                'Ticket_Status'=>"'".$Ticket_Status."'",
                'Ticket_Status_Remarks'=>"'".$Ticket_Status_Remarks."'",
                'Update_At'=>"'".date('Y-m-d H:i:s')."'",
                'Update_By'=>"'".$Update_By."'", 
            );
            
            if($request['Replacement_Serial_No'] !=""){
                $data['Replacement_Serial_No']  =   "'".$request['Replacement_Serial_No']."'";
                $data['Replacement_Reason']     =   "'".$request['Replacement_Reason']."'"; 
            }
            
            if($this->AssetsTicketCreationMaster->updateAll($data,array('Id'=>$Ticket_Id))){
                if($request['Replacement_Serial_No'] !=""){
                    $this->AssetsStocksMaster->query("UPDATE `Assets_Stocks_Master` SET `Process`='$Process' WHERE Branch='$Branch' AND Product='$Product' AND Serial_No='{$request['Replacement_Serial_No']}'");
                    $this->AssetsTicketCreationMaster->query("UPDATE `Assets_Stocks_Master` SET `Working_Status`='NOT WORKING',`Working_Remarks`='{$request['Replacement_Reason']}',`Working_Date`='$Create_At' WHERE Branch='$Branch' AND Product='$Product' AND Serial_No='$Serial_No'");
                }
                
                echo "<span id='msgerr' style='color:green;' >Suatus update successfully.</span>";die;
            }
            else{
                echo "<span id='msgerr' style='color:red;' >Data not update.</span>";die;
            }
        }
    }
        
    public function show_ticket_details(){
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Id         =   $request['Id'];           
            $data       =   $this->AssetsTicketCreationMaster->find('first',array('conditions'=>array("Id='$Id'")));
            $row        =   $data['AssetsTicketCreationMaster'];
            
            $Branch     =   $row['Branch'];
            $Product    =   $row['Product'];
            $Replacement_Serial_No    =   $row['Replacement_Serial_No'];
            
            $Serial_Arr =   $this->AssetsStocksMaster->find('list',array('fields'=>array('Id','Serial_No'),'conditions'=>array("Branch='$Branch' AND Product='$Product' AND Process IS NULL AND Working_Status IS NULL ORDER BY Serial_Id")));
            ?>
            
            <input type="hidden" name="Id" id="Id" value="<?php echo isset($row['Id'])?$row['Id']:''?>" >
            <div class="form-group"  >
                <label class="col-sm-2 control-label">Ticket&nbsp;Status</label>
                <div class="col-sm-4">
                    <select name="Ticket_Status" id="Ticket_Status" class="form-control">
                        <option <?php echo isset($row['Ticket_Status']) && $row['Ticket_Status']=='OPEN'?"selected='selected'":''?> value="OPEN">OPEN</option>
                        <option <?php echo isset($row['Ticket_Status']) && $row['Ticket_Status']=='FOLLOW UP'?"selected='selected'":''?> value="FOLLOW UP">FOLLOW UP</option>
                        <option <?php echo isset($row['Ticket_Status']) && $row['Ticket_Status']=='CLOSED'?"selected='selected'":''?> value="CLOSED">CLOSED</option>
                    </select>
                </div>

                <label class="col-sm-2 control-label">Replacement</label>
                <div class="col-sm-4">
                    <?php if($Replacement_Serial_No ==""){?>
                    <select name="Replacement_Serial_No" id="Replacement_Serial_No" class="form-control">
                        <option value="">Select</option>
                        <?php foreach ($Serial_Arr as $key=>$val){ ?>
                        <option value="<?php echo $val;?>"><?php echo $val;?></option>
                        <?php }?>
                    </select>
                    <?php }else{?>
                    <input type="text" name="Replacement_Serial_No" id="Replacement_Serial_No" value="<?php echo $Replacement_Serial_No;?>" readonly="" class="form-control" >
                    <?php }?>
                </div>
                
                <label class="col-sm-2 control-label">Reason</label>
                <div class="col-sm-4">
                    <textarea name="Replacement_Reason" id="Replacement_Reason" class="form-control"><?php echo isset($row['Replacement_Reason'])?$row['Replacement_Reason']:''?></textarea>
                </div>
                
                <label class="col-sm-2 control-label">Ticket Status Remarks</label>
                <div class="col-sm-4">
                    <textarea id="Ticket_Status_Remarks" name="Ticket_Status_Remarks" class="form-control"><?php echo isset($row['Ticket_Status_Remarks'])?$row['Ticket_Status_Remarks']:''?></textarea>
                </div>
            </div>
                
            <?php
            die;
        }
    }
    
    
    public function not_working_assets(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $DataArr=array();
        $NotWorkingData  =   $this->AssetsStocksMaster->find('all',array('conditions'=>array("branch='$branchName' AND Working_Status='NOT WORKING' ORDER BY Working_Date")));
        foreach($NotWorkingData as $rows){
            $row    =   $rows['AssetsStocksMaster'];
            $DataArr[]=array(
                'Id'=>$row['Id'],
                'Product'=>$row['Product'],
                'Vender'=>$row['Vender'],
                'Process'=>$this->PROCESSNAME($row['Process']),
                'Serial_No'=>$row['Serial_No'],
                'Create_At'=>$row['Create_Date'] !=''?date('d-M-Y',strtotime($row['Create_Date'])):"",
                'Working_Status'=>$row['Working_Status'],
                'Working_Remarks'=>$row['Working_Remarks'],
                'Working_Date'=>$row['Working_Date'] !=''?date('d-M-Y',strtotime($row['Working_Date'])):"",
                );
        }
        
        $this->set('DataArr',$DataArr);

    }
    
    public function restore_not_working_assets(){
        $this->layout='home';

        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $Id =   $_REQUEST['Id'];
            
            $this->AssetsStocksMaster->query("UPDATE `Assets_Stocks_Master` SET `Process`=NULL,`Allocate_Date`=NULL,`Allocate_By`=NULL,
            `Working_Status`=NULL,`Working_Remarks`=NULL,`Working_Date`=NULL WHERE Id='$Id'");
        }
        
        $this->redirect(array('action'=>'not_working_assets'));
    }
    
    public function print_label(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $Product_List   =   $this->AssetsProductMaster->find('list',array('fields'=>array('Product_Name','Product_Name'),'conditions'=>array("Product_Status='1' ORDER BY Product_Name"))); 
        $this->set('Product_List',$Product_List);
        
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Branch     =   $request['BranchName'];  
            $Product    =   $request['Product'];
            
            $data       =   $this->AssetsStocksMaster->find('list',array('fields'=>array('Serial_Id','Serial_No'),'conditions'=>array("Branch='$Branch' AND Product='$Product'")));
          
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    echo "<option value='$key'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    /*
    public function download_assets_stocks(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Branch     =   $request['BranchName'];  
            $Product    =   $request['Product'];
            
            $data       =   $this->AssetsStocksMaster->find('list',array('fields'=>array('Serial_Id','Serial_No'),'conditions'=>array("Branch='$Branch' AND Product='$Product'")));
          
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    echo "<option value='$key'>$val</option>";
                } 
            } 
            die;
        }
    }
    */
    
    public function download_assets_data(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $request    =   $this->request->data;  
            
            $BranchName     =   $request['AssetsCategoryMasters']['branch_name'];  
            $Branch         =   str_replace(' ', '-', $BranchName);
            $File_Name      =   $Branch."-".date("d-m-Y");
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$File_Name.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $Stock_Arr=$this->AssetsStocksMaster->find('all',array('conditions'=>array("Branch='$BranchName' ORDER BY Serial_Id ASC")));
            
                echo "<table border='1'>";
                echo "<tr>";
                echo "<th>Branch Name</th>";
                echo "<th>Product Name</th>";
                echo "<th>Brand Name</th>";
                echo "<th>Size(Inch)</th>";
                echo "<th>Mother Board</th>";
                echo "<th>Processor</th>";
                echo "<th>Speed</th>";
                echo "<th>RAM Slot 1</th>";
                echo "<th>RAM Slot 2</th>";
                echo "<th>RAM Slot 3</th>";
                echo "<th>RAM Slot 4</th>";
                echo "<th>HDD 1</th>";
                echo "<th>HDD 2</th>";
                echo "<th>Operating System</th>";
                echo "<th>Vender Name</th>";
                echo "<th>Serial No</th>";
                echo "<th>Process Name</th>";
                echo "<th>Upload Date</th>";
                echo "<th>Upload By</th>";
                echo "<th>Allocate Date</th>";
                echo "<th>Allocate By</th>";
                echo "<th>Working Status</th>";
                echo "<th>Working Remarks</th>";
                echo "<th>Working Date</th>";
                
                echo "</tr>";
                foreach($Stock_Arr as $Rows){
                    $row    =   $Rows['AssetsStocksMaster'];
                    echo "<tr>";
                     echo "<td>".$row['Branch']."</td>";
                    echo "<td>".$row['Product']."</td>";
                    echo "<td>".$row['Brand']."</td>";
                    echo "<td>".$row['Size']."</td>";
                    echo "<td>".$row['Mother_Board']."</td>";
                    echo "<td>".$row['Processor']."</td>";
                    echo "<td>".$row['Speed']."</td>";
                    echo "<td>".$row['RAM_Slot_1']."</td>";
                    echo "<td>".$row['RAM_Slot_2']."</td>";
                    echo "<td>".$row['RAM_Slot_3']."</td>";
                    echo "<td>".$row['RAM_Slot_4']."</td>";
                    echo "<td>".$row['HDD_1']."</td>";
                    echo "<td>".$row['HDD_2']."</td>";
                    echo "<td>".$row['Operating_System']."</td>";
                    echo "<td>".$row['Vender']."</td>";
                    echo "<td>".$row['Serial_No']."</td>";
                    echo "<td>".$this->PROCESSNAME($row['Process'])."</td>";
                    if(isset($row['Create_Date']) && $row['Create_Date'] !=""){echo "<td>".date("d-M-Y",strtotime($row['Create_Date']))."</td>";}else{echo "<td></td>";}
                    echo "<td>".$row['Create_By']."</td>";
                    if(isset($row['Allocate_Date']) && $row['Allocate_Date'] !=""){echo "<td>".date("d-M-Y",strtotime($row['Allocate_Date']))."</td>";}else{echo "<td></td>";}
                    echo "<td>".$row['Allocate_By']."</td>";
                    
                    if($row['Working_Status']!=""){
                         echo "<td>".$row['Working_Status']."</td>";
                    }
                    else{
                        echo "<td>".$row['Working_Status']."</td>";
                    }
                    
                    echo "<td>".$row['Working_Remarks']."</td>";
                    if(isset($row['Working_Date']) && $row['Working_Date'] !=""){echo "<td>".date("d-M-Y",strtotime($row['Working_Date']))."</td>";}else{echo "<td></td>";}
                    echo "</tr>";
                }
                echo "</table>";
            exit();
        }
    }

    public function PROCESSNAME($id){
        $data       =   $this->CostCenterMaster->find('first',array('fields'=>array('process_name'),'conditions'=>array("id='$id' ORDER BY process_name")));
        return $data['CostCenterMaster']['process_name']; 
    }
    
    public function ticket_solution_report(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            //$this->set('branchName',$BranchArray);   
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $request    =   $this->request->data;  
            
            $BranchName     =   $request['AssetsCategoryMasters']['branch_name'];
            $FromDate       =   date("Y-m-d",strtotime($request['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($request['ToDate']));
            
            $branch         =   $BranchName !="ALL"?"AND branch='$BranchName'":"";
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=ticket_solution.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            

            $Ticket_Data=array();
            $TicketArr  =   $this->AssetsTicketCreationMaster->find('all',array('conditions'=>array("DATE(Create_At) >='$FromDate' AND DATE(Create_At) <='$ToDate' $branch  ORDER BY Create_At")));
            foreach($TicketArr as $rows){
                $row    =   $rows['AssetsTicketCreationMaster'];
                $Ticket_Data[]=array(
                    'Id'=>$row['Id'],
                    'Branch'=>$row['Branch'],
                    'Process'=>$this->PROCESSNAME($row['Process']),
                    'Product'=>$row['Product'],
                    'Serial_No'=>$row['Serial_No'],
                    'Problem'=>$row['Problem'],
                    'Remarks'=>$row['Remarks'],
                    'Agent_Name'=>$row['Agent_Name'],
                    'TL_Name'=>$row['TL_Name'],
                    'Ticket_Status'=>$row['Ticket_Status'],
                    'Create_At'=>date('d-M-Y H:i:s',strtotime($row['Create_At'])),
                    'Update_At'=>$row['Update_At'] !=''?date('d-M-Y H:i:s',strtotime($row['Update_At'])):"",
                    'Update_By'=>$row['Update_By'],
                    'Ticket_Status_Remarks'=>$row['Ticket_Status_Remarks'],
                    'Replacement_Serial_No'=>$row['Replacement_Serial_No'],
                    'Replacement_Reason'=>$row['Replacement_Reason'],
                    );
            }
            ?>
            <table border="1">     
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                     <th>Branch</th>
                                    <th>Product</th>
                                    <th>Process</th>
                                    <th>Serial No</th>
                                    <th>Problem</th>
                                    <th>Remarks</th>
                                    <th>Agent</th>
                                    <th>TL</th>
                                    <th>Status</th>
                                    <th>Create Date/Time</th>
                                    <th>Update Date/Time</th>
                                    <th>Update By</th>
                                    <th>Update Status</th>
                                    <th>Replace</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($Ticket_Data as $row){?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['Branch']?></td>
                                    <td><?php echo $row['Product']?></td>
                                    <td><?php echo $row['Process']?></td>
                                    <td><?php echo $row['Serial_No']?></td>
                                    <td><?php echo $row['Problem']?></td>
                                    <td><?php echo $row['Remarks']?></td>
                                    <td><?php echo $row['Agent_Name']?></td>
                                    <td><?php echo $row['TL_Name']?></td>
                                    <td><?php echo $row['Ticket_Status']?></td>
                                    <td><?php echo $row['Create_At']?></td>
                                    <td><?php echo $row['Update_At']?></td>
                                    <td><?php echo $row['Update_By']?></td>
                                    <td><?php echo $row['Ticket_Status_Remarks']?></td>
                                    <td>
                                        <?php echo $row['Replacement_Serial_No']?>
                                        <?php echo $row['Replacement_Reason']?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
            <?php
            exit();
        }
    }
         
}
?>