<?php
class PnlManualsController extends AppController 
{
    public $uses =array("Addbranch","CostCenterMaster","MonthMaster","Tbl_bgt_expenseheadingmaster",'PnlTmpFileUpload','PnlFileUploadHeaders','PnlFileUploadRecords');
    public function beforeFilter()
    {
        parent::beforeFilter();
        
	$this->layout='home';
        $this->Auth->allow('index');
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
				
            $this->Auth->allow('index');$this->Auth->allow('get_revenue_cost');$this->Auth->allow('send_bps');
            $this->Auth->allow('get_revenue_cost'); $this->Auth->allow('send_bps');
            
	}
    }
		
    public function index() 
    {
        $this->layout='home';
        $FinanceYearLogin = $this->Session->read('FinanceYearLogin');
        //$this->set('FinanceYearLogin',$FinanceYearLogin);
        $month_master = $this->MonthMaster->find('list',array('fields'=>array('MonthId','MonthName'),'conditions'=>array('MonthType'=>'3')));
        
        $login_year = explode('-',$FinanceYearLogin);
        if(!empty($login_year))
        {
            $y_arr = array(($login_year[1]-1)=>$login_year[1]);
        }
        else
        {
            $y_arr = array("21"=>"22","20"=>"21");
        }
        
        
         $new_month_master = array();
        foreach($y_arr as $key=>$value)
        {
            $year = $value;
            $Nyear = $year-1;
           
        
        //print_r($month_master); exit;
        
            foreach($month_master as $mnt)
            {
                if(in_array($mnt,array('Jan','Feb','Mar')))
                {
                    //$new_mnt = $mnt.'-'.$Nyear;
                    $new_mnt = $mnt.'-'.$year;
                }
                else
                {
                    $new_mnt = $mnt.'-'.$Nyear;
                }
                $new_month_master[$new_mnt] = $new_mnt;
            }
        }
        
        
        //print_r($new_month_master); exit;
        
        $direct_exp= $this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),
            'order'=>array('OrderPriority_pnl_file'),
            'conditions'=>"OrderPriority_pnl_file is not null and EntryBy='' and Cost='D' AND close_status='1'"));
        
        $indirect_exp1= $this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),
            'order'=>array('OrderPriority_pnl_file'),
            'conditions'=>"HeadingId in ('000022','000023')"));
        
        //print_r($indirect_exp1);die;
        
        $indirect_exp2= $this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),
            'order'=>array('OrderPriority'),
            'conditions'=>"OrderPriority_pnl_file is not null and EntryBy='' and Cost='I' AND close_status='1'"));
        
        $this->set('new_month_master', $new_month_master);
        
        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('pnl_active'=>1),'order'=>array('branch_name'))); 
        $this->set('branch_name', $BranchArray);
        
        //$this->set('direct_exp',$direct_exp);
        //$this->set('indirect_exp',($indirect_exp1+$indirect_exp2));
        
        
        $this->set('direct_exp',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'D',"close_status"=>"1"),'order'=>array('OrderPriority')))) ;
        $this->set('indirect_exp',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'I',"close_status"=>"1"),'order'=>array('OrderPriority')))) ;
      
        
        
        
        
        $pnlFileHeaders = $this->PnlFileUploadHeaders->find('all');
        foreach($pnlFileHeaders as $head)
        {
            $pnl_head_arr[$head['PnlFileUploadHeaders']['Particulars']]['Col_Start'] = $head['PnlFileUploadHeaders']['Col_Start'];
            $pnl_head_arr[$head['PnlFileUploadHeaders']['Particulars']]['Col_End'] = $head['PnlFileUploadHeaders']['Col_End'];
            $pnl_head_arr[$head['PnlFileUploadHeaders']['Particulars']]['RowNumber'] = $head['PnlFileUploadHeaders']['RowNumber'];
        }
        $this->set('pnl_head_arr',$pnl_head_arr);
        if($this->request->is('Post'))
        {
            //print_r($this->request->data); exit;
            
            $request = $this->request->data;
            
            
            $columnArr = array(1=>'A',	2=>'B',	3=>'C',	4=>'D',	5=>'E',	6=>'F',	7=>'G',	8=>'H',	9=>'I',	10=>'J',	11=>'K',	12=>'L',	13=>'M',	14=>'N',	15=>'O',
16=>'P',	17=>'Q',	18=>'R',	19=>'S',	20=>'T',	21=>'U',	22=>'V',	23=>'W',	24=>'X',	25=>'Y',	26=>'Z',	27=>'AA',	28=>'AB',	29=>'AC',
30=>'AD',	31=>'AE',	32=>'AF',	33=>'AG',	34=>'AH',	35=>'AI',	36=>'AJ',	37=>'AK',	38=>'AL',	39=>'AM',	40=>'AN',	41=>'AO',	42=>'AP',	43=>'AQ',
44=>'AR',	45=>'AS',	46=>'AT',	47=>'AU',	48=>'AV',	49=>'AW',	50=>'AX',	51=>'AY',	52=>'AZ',	53=>'BA',	54=>'BB',	55=>'BC',	56=>'BD',	57=>'BE',
58=>'BF',	59=>'BG',	60=>'BH',	61=>'BI',	62=>'BJ',	63=>'BK',	64=>'BL',	65=>'BM',	66=>'BN',	67=>'BO',	68=>'BP',	69=>'BQ',	70=>'BR',	71=>'BS',
72=>'BT',	73=>'BU',	74=>'BV',	75=>'BW',	76=>'BX',	77=>'BY',	78=>'BZ',	79=>'CA',	80=>'CB',	81=>'CC',	82=>'CD',	83=>'CE',	84=>'CF',	85=>'CG',
86=>'CH',	87=>'CI',	88=>'CJ',	89=>'CK',	90=>'CL',	91=>'CM',	92=>'CN',	93=>'CO',	94=>'CP',	95=>'CQ',	96=>'CR',	97=>'CS',	98=>'CT',	99=>'CU',
100=>'CV',	101=>'CW',	102=>'CX',	103=>'CY',	104=>'CZ',	105=>'DA',	106=>'DB',	107=>'DC',	108=>'DD',	109=>'DE',	110=>'DF',	111=>'DG',	112=>'DH',	113=>'DI',
114=>'DJ',	115=>'DK',	116=>'DL',	117=>'DM',	118=>'DN',	119=>'DO',	120=>'DP',	121=>'DQ',	122=>'DR',	123=>'DS',	124=>'DT',	125=>'DU',	126=>'DV',	127=>'DW',
128=>'DX',	129=>'DY',	130=>'DZ',	131=>'EA',	132=>'EB',	133=>'EC',	134=>'ED',	135=>'EE',	136=>'EF',	137=>'EG',	138=>'EH',	139=>'EI',	140=>'EJ',	141=>'EK',
142=>'EL',	143=>'EM',	144=>'EN',	145=>'EO',	146=>'EP',	147=>'EQ',	148=>'ER',	149=>'ES',	150=>'ET',	151=>'EU',	152=>'EV',	153=>'EW',	154=>'EX',	155=>'EY',
156=>'EZ',	157=>'FA',	158=>'FB',	159=>'FC',	160=>'FD',	161=>'FE',	162=>'FF',	163=>'FG',	164=>'FH',	165=>'FI',	166=>'FJ',	167=>'FK',	168=>'FL',	169=>'FM',
170=>'FN',	171=>'FO',	172=>'FP',	173=>'FQ',	174=>'FR',	175=>'FS',	176=>'FT',	177=>'FU',	178=>'FV',	179=>'FW',	180=>'FX',	181=>'FY',	182=>'FZ',	183=>'GA',
184=>'GB',	185=>'GC',	186=>'GD',	187=>'GE',	188=>'GF',	189=>'GG',	190=>'GH',	191=>'GI',	192=>'GJ',	193=>'GK',	194=>'GL',	195=>'GM',	196=>'GN',	197=>'GO',
198=>'GP',	199=>'GQ',	200=>'GR',	201=>'GS',	202=>'GT',	203=>'GU',	204=>'GV',	205=>'GW',	206=>'GX',	207=>'GY',	208=>'GZ',	209=>'HA',	210=>'HB',	211=>'HC',
212=>'HD',	213=>'HE',214=>'HF',215=>'HG',216=>'HH',217=>'HI',218=>'HJ',219=>'HK',220=>'HL',221=>'HM',222=>'HN',223=>'HO',224=>'HP',225=>'HQ',	226=>'HR',227=>'HS',228=>'HT',229=>'HU',217=>'HV',218=>'HW',219=>'HX',
                220=>'HY',221=>'HZ',222=>'IA',223=>'IB',224=>'IC');
           
            $columnArr_swap = array_flip($columnArr);
            
            $FileTye = $this->request->data['PnlMannuals']['file_upload']['type'];
            $info = explode(".",$this->request->data['PnlMannuals']['file_upload']['name']);
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
            {
                $FilePath = $this->request->data['PnlMannuals']['file_upload']['tmp_name'];
                $files = fopen($FilePath, "r");
                $date = date('Y-m-d H:i:s');
                $this->PnlTmpFileUpload->query("truncate table pnl_tmp_file_upload");
                
                $cost_start = $request['cost_start'];
                $cost_end = $request['cost_end'];
            
                $column_start = preg_replace('/\d+/u', '', $cost_start);
                $column_end = preg_replace('/\d+/u', '', $cost_end);
                $cost_column_start = (int) filter_var($cost_start, FILTER_SANITIZE_NUMBER_INT);
                $cost_column_end = (int) filter_var($cost_end, FILTER_SANITIZE_NUMBER_INT);
                $rowNumber = $columnArr_swap[$column_start];
                $end = $columnArr_swap[$column_end];
                
                $recordArr = array();
                for($k=0;$k<=150;$k++)
                {
                    $row = fgetcsv($files,5000,",");
                    $record = array(); $j=1;
                    for($i=($cost_column_start-1); $i<$end;$i++)
                    {
                        $record['field'.($j++)] = $row[$i];
                    }
                    $record['user_id'] = $this->Session->read('userid');
                    $record['entry_date'] = $date;
                    $record['branch_id'] = $request['PnlMannuals']['Branch'];
                    $record['finance_month'] = $request['PnlMannuals']['month'];
                    
                    $recordArr[] = $record;
                }
                
                
                
                //print_r($recordArr); exit;
                
                $this->PnlTmpFileUpload->saveAll($recordArr);
                
                
                
                
                
                
                
                
                
                $pnl_file_index = array();
                $pnl_file_index['Particulars'] = 'cost_start';
                $pnl_file_index['RowNumber'] = $rowNumber;
                $pnl_file_index['Col_Start'] = $column_start;
                $pnl_file_index['Col_End'] = $column_end;
                $pnl_file_index['created_by'] = $this->Session->read('userid');
                    $pnl_file_index['created_at'] = $date;
                $index_arr[] = $pnl_file_index;
                
                
                $pnl_file_index = array();
                $pnl_file_index['Particulars'] = 'net_revenue';
                $pnl_file_index['RowNumber'] =(int) filter_var($request['net_revenue_start'], FILTER_SANITIZE_NUMBER_INT); ;
                $pnl_file_index['Col_Start'] = $column_start;
                $pnl_file_index['Col_End'] = $column_end;
                $pnl_file_index['created_by'] = $this->Session->read('userid');
                    $pnl_file_index['created_at'] = $date;
                $index_arr[] = $pnl_file_index;
                
                
                $pnl_file_index = array();
                $pnl_file_index['Particulars'] = 'actual_salary';
                $pnl_file_index['RowNumber'] =(int) filter_var($request['actual_start'], FILTER_SANITIZE_NUMBER_INT); ;
                $pnl_file_index['Col_Start'] = $column_start;
                $pnl_file_index['Col_End'] = $column_end;
                $pnl_file_index['created_by'] = $this->Session->read('userid');
                    $pnl_file_index['created_at'] = $date;
                $index_arr[] = $pnl_file_index;
                
                $pnl_file_index = array();
                $pnl_file_index['Particulars'] = 'future_revenue';
                $pnl_file_index['RowNumber'] =(int) filter_var($request['start_future_revenue'], FILTER_SANITIZE_NUMBER_INT); ;
                $pnl_file_index['Col_Start'] = $column_start;
                $pnl_file_index['Col_End'] = $column_end;
                $pnl_file_index['created_by'] = $this->Session->read('userid');
                $pnl_file_index['created_at'] = $date;
                $index_arr[] = $pnl_file_index;
                
                
                
                $id_arr = explode(',',$request['id_arr']);
                foreach($id_arr as $idr)
                {
                    $pnl_file_index = array();
                    $pnl_file_index['Particulars'] = $idr;
                    $pnl_file_index['RowNumber'] =(int) filter_var($request['start_'.$idr], FILTER_SANITIZE_NUMBER_INT); ;
                    $pnl_file_index['Col_Start'] = $column_start;
                    $pnl_file_index['Col_End'] = $column_end;
                    $pnl_file_index['created_by'] = $this->Session->read('userid');
                    $pnl_file_index['created_at'] = $date;
                    $index_arr[] = $pnl_file_index;
                    
                }   
                
                $this->PnlFileUploadHeaders->query("truncate table pnl_file_upload_headers");
                $this->PnlFileUploadHeaders->saveAll($index_arr);
                //print_r($index_arr); exit;
                
                $cost_numbers = $this->PnlTmpFileUpload->find('first',array('conditions'=>"Temp_Id='$rowNumber'"));
                
                $cost_center_arr = array();
                
                for($i=1; $i<=($end-$cost_column_start)+1; $i++)
                {
                    $cost_center_arr[$i] = $cost_numbers['PnlTmpFileUpload']['field'.$i];
                }
                
                //print_r($cost_center_arr); exit;
                
                $headers    =   $this->PnlFileUploadHeaders->find('all',array('conditions'=>"Particulars!='cost_start'"));
                $recor_arr  =   array();
                foreach($headers as $head){
                    foreach($cost_center_arr as $key=>$val){
                        $record = array();
                        $record['HeaderId']         =   $head['PnlFileUploadHeaders']['Particulars'];
                        $record['branch']           =   $request['PnlMannuals']['Branch'];;
                        $record['finance_month']    =   $request['PnlMannuals']['month'];
                        $record['CostCenter']       =   $val;
                        $record_det                 =   $this->PnlTmpFileUpload->find('first',array('conditions'=>"Temp_Id='{$head['PnlFileUploadHeaders']['RowNumber']}'"));
                        $record['HeadValue']        =   $record_det['PnlTmpFileUpload']['field'.$key];
                        $record['created_at']       =   $date;
                        $record['updated_at']       =   date("Y-m-d",strtotime($request['PnlMannuals']['updated_at']));
                        $record['created_by']       =   $this->Session->read('userid');
                        $recor_arr[]                = $record;
                    }
                }
                //print_r($recor_arr); exit;
                $this->PnlFileUploadRecords->saveAll($recor_arr);
                $this->Session->setFlash('File Uploaded Successfully.');
                return $this->redirect(array('action' => 'index'));
            }
            exit;
        }
    }
	   
    
}

?>