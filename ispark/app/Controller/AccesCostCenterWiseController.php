<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class AccesCostCenterWiseController extends AppController {

    public $uses = array('PageMaster', 'User','Addbranch','CostCenterMaster','AccessPages');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'view', 'assign_Access', 'view_access', 'edit_User', 'add_date', 'getMessage', 'getMessageDisplay', 'updateMessage', 'user_array', 'puser_array', 'change_password','branch','show_data','save_ride');
        $pages = explode(',', $this->Session->read("page_access"));
        if (in_array('40', $pages)) {
            $this->Auth->allow('view_users', 'edit_users');
        }
        if (in_array('8', $pages)) {
            $this->Auth->allow('manage_access', 'view_access');
        }
        if (in_array('15', $pages)) {
            $this->Auth->allow('create_User');
        }
    }

    public function index() {
        
        
        $role = $this->Session->read('user-type');
        $email = $this->Session->read('email');
        $userid = $this->Session->read("userid");
        
        $menu = array();
        $menu1 = array();
        
        //$menus = $this->PageMaster->query("SELECT * FROM acess_pages ORDER BY id ASC");
        
        $branch = $this->Addbranch->find("all",array("conditions"=>"Active='1'",'fields'=>array('id','branch_name')));

        
        
        
        
        if($role=='Super-Admin')
        {
            $sel = "SELECT id,username FROM tbl_user where  UserActive='1' AND username is not null  group by username order by username ";

            foreach ($branch as $row) {

                $menu[$row['Addbranch']['id']] = $row['Addbranch']['branch_name'];
                //$menu['parent_menus'][$row['pages_master_ispark']['parent_id']][] = $row['pages_master_ispark']['id'];
                $cost_master = $this->CostCenterMaster->find("all",array("conditions"=>"active='1' and branch='{$row['Addbranch']['branch_name']}'",'fields'=>array('id','cost_center')));
                        foreach ($cost_master as $row2) {

                    $menu1[$row2['CostCenterMaster']['id']] = $row2['CostCenterMaster']['cost_center'];

                }
            }

            
            
        }
        else
        {

            $sel = "SELECT id,username FROM tbl_user where   UserActive='1' AND username is not null  group by username order by username";

            foreach ($branch as $row) {

                $menu[$row['Addbranch']['id']] = $row['Addbranch']['branch_name'];
                //$menu['parent_menus'][$row['pages_master_ispark']['parent_id']][] = $row['pages_master_ispark']['id'];
                $cost_master = $this->CostCenterMaster->find("all",array("conditions"=>"active='1' and branch='{$row['Addbranch']['branch_name']}'",'fields'=>array('id','cost_center')));
                foreach ($cost_master as $row2) {

                $menu1[$row2['CostCenterMaster']['id']] = $row2['CostCenterMaster']['cost_center'];

                }
            }
            
            

           
        }
        
        //print_r($menu); exit;
        
        $users = $this->User->query($sel); 
        $this->set('users', $users);

        
        //$this->set('menus', $menus);

        $parent = 0;
        $this->set('UserRight', $this->buildMenu($menu,$menu1,$userid));
        $html = "";

        $this->layout = 'home';

    }


    function buildMenu($menu,$menu1,$userid) {
        $html = "";
        $char = " ";

        $i = 1;

        
        $branchArrList	=	array();
        $costArrList	=	array();
        $attUpList	=	array();
        $attAppList	=	array();
        $leaAppList	=	array();
            if($rides[0]['acess_pages']['branch_id'] !=""){
               // $branchArrList	=	explode( ',', $rides[0]['acess_pages']['branch_id']);
            }


            
            $check = 'checked';
            $notcheck = '';
            foreach ($menu as $key=>$menu_id)
            {
                $html .= "<li id='a".$key."'><div class='checkbox-primary'><label><input type='checkbox' onchange=".'"show_child('."'".++$i."'".')"'."  name='branch[]' id='" . $key . "'  value='" . $key . "' > " . $menu_id;
                $html .= "<ol class='user-tree'>"; 
                $menu1 = array();
                $cost_master = $this->CostCenterMaster->find("all",array("conditions"=>"active='1' and branch='$menu_id'",'fields'=>array('id','cost_center')));
                        foreach ($cost_master as $row2) {
                    $menu1[$row2['CostCenterMaster']['id']] = $row2['CostCenterMaster']['cost_center'];
                }
                foreach($menu1 as $key1=>$menu_id) 
                {
                    $new_key = $key."_".$key1;
                    $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' onchange=".'"show_child('."'".++$i."'".')"'."  name='cost_center[]' id='" . $new_key . "'  value='" . $key1 . "' > " . $menu_id;
                    $html .= "<ol class='user-tree'>"; 
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_1'  value='$key1@attendance_upload'>Attendance Upload  ";
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_2'  value='$key1@attendance_approval'>Attendance Approval ";
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_4'  value='$key1@leave_entry'>Leave Entry";
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_3'  value='$key1@leave_approval'>Leave Approval";
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_5'  value='$key1@email_ticket'>Email Id Ticket";
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_6'  value='$key1@bio_ticket'>Bio Id Ticket";
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_7'  value='$key1@partner_ticket'>Partner Id Ticket";
                        $html .= "<li id='a".++$i."'><div class='checkbox-primary'><label><input type='checkbox' name='page[]' id='$new_key"."_8'  value='$key1@bgv_ticket'>Bgv Ticket";
                        
                        $html .= "</label></div></li>";
                    $html .= "</ol>";
                    $html .= "</label></div></li>";
                }
                $html .= "</ol>";
                $html .= "</label></div></li>";
            }
           
       
        return $html;
    }
    
    public function show_data() {
        $id = $this->params['url']['user'];
        
        
        $rides = $this->AccessPages->query("SELECT * FROM acess_pages WHERE user_id='" . $id . "'");
        //print_r($rides);die;

       
        //$this->layout = 'home'; 
        
            $branchArrList	=	array();
            $costArrList	=	array();
            $attUpList	=	array();
            $attAppList	=	array();
            $leaAppList	=	array();

       

        // if($rides[0]['acess_pages']['cost_id'] !=""){
        //     $costArrList=	$rides[0]['acess_pages']['cost_id'];
        // }
        foreach($rides as $showacess)
            {
                $key = $showacess['acess_pages']['branch_id'];
                $key1 = $key."_".$showacess['acess_pages']['cost_id'];
                $array = array();
                
                
                if($showacess['acess_pages']['att_upload'])
                {
                    $key2 = $key1."_"."1";
                    $array[$key1][$key2][$key2] = $key2;
                }
                if($showacess['acess_pages']['att_approval'])
                {
                    $key2 = $key1."_"."2";
                    $array[$key1][$key2][$key2] = $key2;
                }
                if($showacess['acess_pages']['leave_entry'])
                {
                    $key2 = $key1."_"."4";
                    $array[$key1][$key2][$key2] = $key2;
                }
                if($showacess['acess_pages']['leave_approval'])
                {
                    $key2 = $key1."_"."3";
                    $array[$key1][$key2][$key2] = $key2;
                }
                if($showacess['acess_pages']['ticket_email'])
                {
                    $key2 = $key1."_"."5";
                    $array[$key1][$key2][$key2] = $key2;
                }
                if($showacess['acess_pages']['ticket_bio'])
                {
                    $key2 = $key1."_"."6";
                    $array[$key1][$key2][$key2] = $key2;
                }
                if($showacess['acess_pages']['ticket_partner'])
                {
                    $key2 = $key1."_"."7";
                    $array[$key1][$key2][$key2] = $key2;
                }
                if($showacess['acess_pages']['ticket_bgv'])
                {
                    $key2 = $key1."_"."8";
                    $array[$key1][$key2][$key2] = $key2;
                }
                $branchArrList[$key][] = $array;
                

            }

        //print_r($branchArrList);die;

        $this->set('branchArrList', array($branchArrList));
        
        $this->layout = null;
    }

    public function save_ride()
    {
        //print_r($this->params['url']);die;
        $branch = $this->request->data['branchs'];
        $costcenter = $this->request->data['costcenters'];
        $pages = $this->request->data['pages'];
        $username = $this->request->data['user'];
        //print_r($this->request->data);exit;
        $pages2 = explode(",", $pages);
        $this->AccessPages->query("delete from acess_pages where user_id='$username'");
       // echo count($ch);
        //print_r($pages);die;
        $access = '';
        $costcenter1 = array();
        foreach($pages2 as $page)
        {
            $cost = explode("@", $page);
            
            
            $att_up = '';
            if($cost[1] == 'attendance_upload')
            {
                $att_up = "1";

            }
            $att_app = '';
            if($cost[1] == 'attendance_approval')
            {
                $att_app = "1";

            }
            $lea_ent = '';
            if($cost[1] == 'leave_entry')
            {
                $lea_ent = "1";

            }
            $lea_app = '';
            if($cost[1] == 'leave_approval')
            {
                $lea_app = "1";

            }
            $tic_email = '';
            if($cost[1] == 'email_ticket')
            {
                $tic_email = "1";

            }
            $tic_bio = '';
            if($cost[1] == 'bio_ticket')
            {
                $tic_bio = "1";

            }
            $tic_partner = '';
            if($cost[1] == 'partner_ticket')
            {
                $tic_partner = "1";

            }
            $tic_bgv = '';
            if($cost[1] == 'bgv_ticket')
            {
                $tic_bgv = "1";

            }
            //echo "INSERT INTO acess_pages set user_id='" . $username . "', branch_id='" . $branch . "',cost_id='" . $cost[0] . "',att_upload='" . $att_up . "',att_approval='" . $att_app . "',leave_approval='" . $lea_app . "'";exit;
            
            if($att_up=='1')
            {
                $cost_app[$cost[0]]['att_upload'] = 1;
            }
            if($att_app=='1')
            {
                $cost_app[$cost[0]]['att_approval'] = 1;
            }
            if($lea_ent=='1')
            {
                $cost_app[$cost[0]]['leave_entry'] = 1;
            }
            if($lea_app=='1')
            {
                $cost_app[$cost[0]]['leave_approval'] = 1;
            }
            if($tic_email=='1')
            {
                $cost_app[$cost[0]]['ticket_email'] = 1;
            }
            if($tic_bio=='1')
            {
                $cost_app[$cost[0]]['ticket_bio'] = 1;
            }
            if($tic_partner=='1')
            {
                $cost_app[$cost[0]]['ticket_partner'] = 1;
            }
            if($tic_bgv=='1')
            {
                $cost_app[$cost[0]]['ticket_bgv'] = 1;
            }
        }
        
        foreach($cost_app as $cost_id=>$det)
        {
            $att_up = $det['att_upload'];
            $att_app = $det['att_approval'];
            $lea_app = $det['leave_approval'];
            $lea_ent = $det['leave_entry'];
            $tic_email = $det['ticket_email'];
            $tic_bio = $det['ticket_bio'];
            $tic_partner = $det['ticket_partner'];
            $tic_bgv = $det['ticket_bgv'];
            $branch = $this->AccessPages->query("select bm.id from branch_master bm inner join cost_master cm on bm.branch_name = cm.branch where cm.id='{$cost_id}'");
            $bm_id = $branch['0']['bm']['id'];
            $insert_data[] = "('$username','$bm_id','$cost_id','$att_up','$att_app','$lea_ent','$lea_app','$tic_email','$tic_bio','$tic_partner','$tic_bgv')";
        }

        //echo "insert into acess_pages(user_id,branch_id,cost_id,att_upload,att_approval,leave_entry,leave_approval,ticket_email,ticket_bio,ticket_partner,ticket_bgv) values ".implode(",",$insert_data).";"; die;
        if(!empty($insert_data))
        {$insert_query = "insert into acess_pages(user_id,branch_id,cost_id,att_upload,att_approval,leave_entry,leave_approval,ticket_email,ticket_bio,ticket_partner,ticket_bgv) values ".implode(",",$insert_data).";"; 
            
        $rides = $this->AccessPages->query($insert_query);}
      
        // $access = substr($access, 0, -1);
        // //echo $access;die;
        // $q1 = "SELECT id,user_id from acess_pages WHERE user_id='$username'";
        // $dd = $this->AccessPages->query($q1);
        // //echo "INSERT INTO acess_pages set user_id='" . $username . "', branch_id='" . $branch . "',cost_id='" . $costcenter . "',$access";die;
        // //echo "Update acess_pages set branch_id='" . $branch . "',cost_id='" . $costcenter . "',$access WHERE user_name='" . $username . "'";die;
        // if(empty($dd))
        // {
        //     $rides = $this->AccessPages->query("INSERT INTO acess_pages set user_id='" . $username . "', branch_id='" . $branch . "',cost_id='" . $costcenter . "',$access");

        // }else{
        //     $rides = $this->AccessPages->query("Update acess_pages set branch_id='" . $branch . "',cost_id='" . $costcenter . "',$access WHERE user_id='" . $username . "'");
        // }
    
        
        $this->set('response', "save");
        $this->layout = null;
    }
    

}

?>
