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
class AccesIsparkController extends AppController {

    public $uses = array('PageMaster', 'User','Addbranch');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'view', 'assign_Access', 'view_access', 'edit_User', 'add_date', 'getMessage', 'getMessageDisplay', 'updateMessage', 'user_array', 'puser_array', 'change_password','branch');
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
        $userid = $this->Session->read('userid');
        
        $menu = array(
            'menus' => array(),
            'parent_menus' => array(),
        );
        
        $menus = $this->PageMaster->query("SELECT * FROM pages_master_ispark ORDER BY parent_id ASC,id ASC"); 
        
        if($role=='Super-Admin')
        {
            $sel = "SELECT id,username FROM tbl_user where  UserActive='1' AND username is not null  group by username order by username ";
            foreach ($menus as $row) {
            $menu['menus'][$row['pages_master_ispark']['id']] = $row['pages_master_ispark'];
            $menu['parent_menus'][$row['pages_master_ispark']['parent_id']][] = $row['pages_master_ispark']['id'];
            }
            
        }
        else
        {
            $sel = "SELECT id,username FROM tbl_user where (process_head='$email' or createdby='$userid') and  UserActive='1' AND username is not null   group by username order by username ";
            $new_list = array();
            
            $menu_list_arr =  $this->PageMaster->query("SELECT * FROM pages_ride_ispark where user_name ='$email'");
            
            //print_r($menu_list_arr); exit;
            
            foreach($menu_list_arr as $m_list)
            {
                $parent_access= explode(',',$m_list['pages_ride_ispark']['parent_access']);
                $access= explode(',',$m_list['pages_ride_ispark']['access']); 
            }
            
            //print_r($menus); exit;
            
            
            foreach ($menus as $row) {
                
                /*print_r($row); echo '<br/>';
                print_r($parent_access); echo '<br/>';
                print_r($access); exit; echo '<br/>';*/
                $flag = false;
                
                if($row['pages_master_ispark']['parent_id']=='0' && in_array($row['pages_master_ispark']['id'], $parent_access))
                {
                    $flag = true; 
                }
                else if(in_array($row['pages_master_ispark']['parent_id'], $parent_access) &&  in_array($row['pages_master_ispark']['id'], $access))
                {
                    $flag = true;
                }
                
                if($flag)
                {
                    $menu['menus'][$row['pages_master_ispark']['id']] = $row['pages_master_ispark'];
                    $menu['parent_menus'][$row['pages_master_ispark']['parent_id']][] = $row['pages_master_ispark']['id'];
                }
            }
        }
        
        //print_r($menu); exit;
        
        $users = $this->User->query($sel); 
        $this->set('users', $users);

        
        //$this->set('menus', $menus);
        
        
        

        
        $parent = 0;
        $this->set('UserRight', $this->buildMenu($parent, $menu)); 
        $html = "";

        $this->layout = 'home';
    }

    function buildMenu($parent, $menu) {
        $html = "";
        $char = " ";
        if (isset($menu['parent_menus'][$parent])) {
            foreach ($menu['parent_menus'][$parent] as $menu_id) {
                if (!isset($menu['parent_menus'][$menu_id])) { 
                    $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' name='selectAll[]' id='" . $menu['menus'][$menu_id]['id'] . "'  value='" . $menu['menus'][$menu_id]['id'] . "'> " . $menu['menus'][$menu_id]['page_name'] . "</label></div></li>";
                }
                if (isset($menu['parent_menus'][$menu_id])) {  
                    $html .= "<li id='a".$menu['menus'][$menu_id]['id']."'><div class='checkbox-primary'><label><input type='checkbox' onchange=".'"show_child('."'".$menu['menus'][$menu_id]['id']."'".')"'."  name='selectAll[]' id='" . $menu['menus'][$menu_id]['id'] . "'  value='" . $menu['menus'][$menu_id]['id'] . "'> " . $menu['menus'][$menu_id]['page_name'];
                    $html .= "<ol class='user-tree'>"; 
                    $html .= $this->buildMenu($menu_id, $menu);
                    $html .= "</ol>";
                    $html .= "</label></div></li>";
                }
            }
        }
        return $html;
    }

    function buildMenu2($parent, $menu) {
        $html = "";
        $char = " ";
        //echo json_encode($menu['parent_menus'][$parent])." pppppppp";
        if (isset($menu['parent_menus'][$parent])) {
            foreach ($menu['parent_menus'][$parent] as $menu_id) {
                echo json_encode($menu_id);
                if (!isset($menu['parent_menus'][$menu_id])) {
                    $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' name='selectAll[]'  value='" . $menu['menus'][$menu_id]['id'] . "'> " . $menu['menus'][$menu_id]['page_name'] . "</label></div></li>";
                }
                /* if (isset($menu['parent_menus'][$menu_id])) {
                  $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]'  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name'];
                  $html .= "<ol class='user-tree'>";
                  $html .= $this->buildMenu($menu_id, $menu);
                  $html .= "</ol>";
                  $html .= "</label></div></li>";
                  } */
            }
        }
        return $html;
    }
	
	
	public function branch(){
		$this->layout='home';

		$users = $this->User->query("SELECT id,username FROM tbl_user where UserActive='1' AND username is not null AND role !='admin'  group by username order by username ");
        $this->set('users', $users);
        
        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        $this->set('branchName',$BranchArray); 
		
        if($this->request->is('Post')){ 

			$id				=	$_REQUEST['userid'];
			$branchArrList	=	array();
			$branchArr 		= 	$this->User->query("SELECT Access_Branch FROM tbl_user where id='".$id."'");
			
			if($_REQUEST['Submit'] =="Submit"){
				$branch		=	implode(",",$_REQUEST['branch']);
				$this->User->query("UPDATE tbl_user SET Access_Branch='$branch' where id='".$id."'");
				$this->Session->setFlash('<span style="font-weight:bold;color:green;" >Branch rights update successfully.</span>'); 
			}
			
			if($branchArr[0]['tbl_user']['Access_Branch'] !=""){
				$branchArrList	=	explode( ',', $branchArr[0]['tbl_user']['Access_Branch'] );
			}
			
			$this->set('branchArrList', $branchArrList);
			$this->set('id',$id); 
			
			
			
        } 
	}

}

?>
