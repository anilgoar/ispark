<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('Controller', 'Controller');
class CommonsController extends AppController {
    public $uses=array('PageMaster');
    public function show(){
        $id = $this->request->query('page'); // clean access using getter method
        //$dd = $this->PageMaster->query("SELECT page_name,page_url FROM pages_master WHERE parent_id='P'");
        $this->set('id',$id);
    }
}
?>

