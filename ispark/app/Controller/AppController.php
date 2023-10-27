<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'users',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'login',
                'action' => 'display',
                'home'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            )
        )
    );

    var $uses = array('PageMaster','LoginLog');
    
    public function beforeFilter() {
        $this->getLog(); 
        $this->Auth->allow('index', 'view');
    }
    
    function getLog()
        {
            
                $admin_id = $this->Session->read("userid");
                $role = $this->Session->read("role");
                $name = $this->Session->read("username");
                
                $url = $this->request->here();
                $url1 = explode("/",$url);

                $pages = $this->PageMaster->find('first',array('conditions'=>array('page_url'=>$url1[2])));
                $page_name = $pages['PagesMaster1']['page_name'];
                
            
            

            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

            

            $data['user_id'] = $admin_id;
            $data['type'] = $role;
            $data['ip_address'] = $ip;
            $data['user_name'] = $name;
            $data['page_name'] = $page_name;
            $data['page_url'] = $url1[2].'/'.$url1[3];
            $data['hit_time'] = date("Y-m-d H:i:s");

            //print_r($data);die;
            if(!empty($admin_id) )
            {
                $save = $this->LoginLog->save($data);
            }
            

        }
}
