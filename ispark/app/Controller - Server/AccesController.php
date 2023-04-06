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
class AccesController extends AppController {

    public $uses = array('PageMaster', 'User');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'view', 'assign_Access', 'view_access', 'edit_User', 'add_date', 'getMessage', 'getMessageDisplay', 'updateMessage', 'user_array', 'puser_array', 'change_password');
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
        $menus = $this->PageMaster->query("SELECT * FROM pages_master ORDER BY parent_id ASC,id ASC");
        $this->set('menus', $menus);


        //$this->Session->write("dd",$dd);
        //$this->layout='home';


        // AND hr_eligible='Yes'

        $users = $this->User->query("SELECT id,username FROM tbl_user where UserActive='1' AND username is not null  group by username order by username ");
        $this->set('users', $users);

        $menu = array(
            'menus' => array(),
            'parent_menus' => array(),
        );

        foreach ($menus as $row) {
            $menu['menus'][$row['pages_master']['id']] = $row['pages_master'];
            $menu['parent_menus'][$row['pages_master']['parent_id']][] = $row['pages_master']['id'];
        }
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
                    $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' id='" . $menu['menus'][$menu_id]['id'] . "'  value='" . $menu['menus'][$menu_id]['id'] . "'> " . $menu['menus'][$menu_id]['page_name'];
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

}

?>
