<?php
App::uses('AppController', 'Controller');

class DesktopController extends AppController {

    public $name       = 'Desktop';
    public $components = array('Aa');   //We'll use the Aa component to determine certain rights
    protected $base    = "Access Providers/Controllers/Desktop/";


    public function authenticate(){

        $this->Auth = $this->Components->load('Auth');
        $this->request->data['User']['username']     = $this->request->data['username'];
        $this->request->data['User']['password']     = $this->request->data['password'];

        if($this->Auth->identify($this->request,$this->response)){
            
            //We can get the detail for the user
            $data = $this->_get_user_detail($this->request->data['User']['username']);
            $this->set(array(
                'data'          => $data,
                'success'       => true,
                '_serialize' => array('data','success')
            ));

        }else{
            //We can get the detail for the user

            $this->set(array(
                'errors'        => array('username' => __('Confirm this name'),'password'=> __('Type the password again')),
                'success'       => false,
                'message'       => array('message'  => __('Authentication failed')),
                '_serialize' => array('errors','success','message')
            ));
        }
    }

    public function check_token(){

        if((isset($this->request->query['token']))&&($this->request->query['token'] != '')){

            $token      = $this->request->query['token'];
            $this->User = ClassRegistry::init('User');
            $q_r        = $this->User->find('first',array(
                'conditions'    => array('User.token' => $token)
            ));

            if($q_r == ''){

                $this->set(array(
                    'errors'        => array('token'=>'invalid'),
                    'success'       => false,
                    '_serialize'    => array('errors','success')
                ));
  
            }else{

                $data = $this->_get_user_detail($q_r['User']['username']);
                $this->set(array(
                    'data'          => $data,
                    'success'       => true,
                    '_serialize'    => array('data','success')
                ));
            }
        }else{

            $this->set(array(
                'errors'        => array('token'=>'missing'),
                'success'       => false,
                '_serialize'    => array('errors','success')
            ));
        }
    }

    public function list_wallpapers(){
        $items = array();
        //List all the wallpapres in the wallpaper directory:
        $wp_document_root   = "/var/www";
        $r_wp_dir           = "/rd/resources/images/wallpapers/";
        $wp_dir             = "/var/www/rd/resources/images/wallpapers/";

        $id = 1;

        if ($handle = opendir($wp_dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $regexp = "/^[0-9a-zA-z\.]+\.(gif|jpg|png|jpeg)$/"; //Match only images
                    if(preg_match($regexp, $entry)){
                      //  echo "$entry\n";
                        array_push($items, array(
                            'id'    => $id,
                            'file'  => $entry,
                            'r_dir' => $r_wp_dir,
                            'img'   => "/cake2/rd_cake/webroot/files/image.php?width=200&height=200&image=".$r_wp_dir.$entry
                            //'img'   => "/cake2/rd_cake/webroot/files/image.php/image-name.jpg?width=200&height=200&image=".$r_wp_dir.$entry
                        ));
                        $id++;
                    }     
                }
            }
            closedir($handle);
        }
        $this->set(array(
            'items'          => $items,
            'success'       => true,
            '_serialize'    => array('items','success')
        ));
    }

    public function save_wallpaper_selection(){
        $user = $this->_ap_right_check();
        if(!$user){
            return;
        }
        $user_id    = $user['id'];
        if(isset($this->request->query['wallpaper'])){
            $path_parts     = pathinfo($this->request->query['wallpaper']);
            $this->UserSetting = ClassRegistry::init('UserSetting');
            $q_r = $this->UserSetting->find('first',array('conditions' => array('UserSetting.user_id' => $user_id,'UserSetting.name' => 'wallpaper')));
            if($q_r){
                $this->UserSetting->id = $q_r['UserSetting']['id'];    
                $this->UserSetting->saveField('value', $path_parts['basename']);
            }else{
                $d['UserSetting']['user_id']= $user_id;
                $d['UserSetting']['name']   = 'wallpaper';
                $d['UserSetting']['value']  = $path_parts['basename'];
                $this->UserSetting->create();
                $this->UserSetting->save($d);
            }
        }

        $this->set(array(
            'success' => true,
            '_serialize' => array('success')
        ));
    }

    public function change_password(){
        $user = $this->_ap_right_check();
        if(!$user){
            return;
        }
        $user_id    = $user['id'];

        $d                      = array();
        $d['User']['id']        = $user_id;
        $d['User']['password']  = $this->request->data['password'];
        $d['User']['token']     = '';
        
        $this->User             = ClassRegistry::init('User');
        $this->User->contain();
        $this->User->id         = $user_id;
        $this->User->save($d);
        $q_r                    = $this->User->findById($user_id);
        $data['token']          = $q_r['User']['token'];

        $this->set(array(
            'success' => true,
            'data'    => $data,
            '_serialize' => array('success','data')
        ));
    }

    public function desktop_shortcuts(){

        $user = $this->_ap_right_check();
        if(!$user){
            return;
        }
        $user_id    = $user['id'];
        $items = array();
        if($user['group_name'] == Configure::read('group.admin')){ 
            $items = $this->_build_admin_shortcuts();
        }

        if($user['group_name'] == Configure::read('group.ap')){ 
            $items = $this->_build_ap_shortcuts($user_id);
        }

        $this->set(array(
            'success' => true,
            'items'    => $items,
            '_serialize' => array('success','items')
        ));

    }


    private function _get_user_detail($username){

        $this->User = ClassRegistry::init('User');
        $this->User->contain('Group');
        $q_r        = $this->User->find('first',array('conditions'    => array('User.username' => $username)));
        $token      = $q_r['User']['token'];
        $id         = $q_r['User']['id'];
        $group      = $q_r['Group']['name'];
        $username   = $q_r['User']['username'];

        $cls        = 'user';
        $menu       = array();
        if( $group == Configure::read('group.admin')){  //Admin
            $cls = 'admin';
            $menu= $this->_build_admin_menus();  //We do not care for rights here;
        }
        if( $group == Configure::read('group.ap')){  //Or AP
            $cls = 'access_provider';
            $menu= $this->_build_ap_menus($id);  //We DO care for rights here!
        }

        $wp_url = Configure::read('paths.wallpaper_location').Configure::read('user_settings.wallpaper');
        //Check for personal overrides
        $this->UserSetting = ClassRegistry::init('UserSetting');
        $q = $this->UserSetting->find('first',array('conditions' => array('UserSetting.user_id' => $id,'UserSetting.name' => 'wallpaper')));
        if($q){
            $wp_base = $q['UserSetting']['value'];
            $wp_url = Configure::read('paths.wallpaper_location').$wp_base;
        }

        return array(
            'token'         =>  $q_r['User']['token'],
            'menu'          =>  $menu,
            'user'          =>  array('id' => $id, 'username' => $username,'group' => $group,'cls' => $cls),
            'urlWallpaper'  =>  $wp_url,
            'shortcuts'     =>  array() 
        );
    }

    private function _build_admin_menus(){

      

        $menus = array(
          //  array(  'text'  => __('Vouchers'),              'iconCls' => 'vouchers'),
          //  array(  'text'  => __('Permanent users'),       'iconCls' => 'group'),
          //  array(  'text'  => __('Accounting'),            'iconCls' => 'accounting'),
            array(  'text'  => __('Realms and Providers'),  'iconCls' => 'realms',  'glyph' => Configure::read('icnRealm') ,'menu'  =>
                 array( 'items' =>
                    array(
                        array('text' => __('Access Providers') ,'iconCls' => 'key',  'glyph' => Configure::read('icnKey'),   'itemId' => 'cAccessProviders'),
                        array('text' => __('Realms') ,          'iconCls' => 'realms','glyph' => Configure::read('icnRealm'), 'itemId' => 'cRealms'),
                    )
                )
            ),
        //    array(  'text'  => __('Profiles'),              'iconCls' => 'profiles'),
          //  array(  'text'  => __('Activity/Stats'),        'iconCls' => 'stats'),
            array(  'text'  => __('NAS Devices'),  'iconCls' => 'nas', 'glyph' => Configure::read('icnNas'), 'menu'  =>
                 array( 'items' =>
                    array(
                        array('text' => __('NAS Devices') ,     'iconCls' => 'nas', 'glyph' => Configure::read('icnNas'),  'itemId' => 'cNas'),
                        array('text' => __('NAS Device tags') , 'iconCls' => 'tags','glyph' => Configure::read('icnTag'), 'itemId' => 'cTags'),
                    )
                )
            ),
            array(  'text'  => __('Profiles'),  'iconCls' => 'profiles', 'glyph' => Configure::read('icnProfile'), 'menu'  =>
                 array( 'items' =>
                    array(
                        array('text' => __('Profile Components') ,  'iconCls' => 'components', 'glyph' => Configure::read('icnComponent'),  'itemId' => 'cProfileComponents'),
                        array('text' => __('Profiles') ,            'iconCls' => 'profiles',   'glyph' => Configure::read('icnProfile'), 'itemId' => 'cProfiles'),
                    )
                )
            ),
            array(  'text'  => __('Tools'),  'iconCls' => 'tools', 'glyph' => Configure::read('icnLight'), 'menu'  =>
                 array( 'items' =>
                    array(
                        array(  'text'  => __('Activity monitor'),  'iconCls' => 'activity',        'glyph' => Configure::read('icnActivity'), 'itemId' => 'cActivityMonitor'),
                        array(  'text'  => __('RADIUS client'),     'iconCls' => 'radius_client',   'glyph' => Configure::read('icnRadius'),'itemId' => 'cRadiusClient'),
                        array(  'text'  => __('Logfile viewer'),    'iconCls' => 'logfile_viewer',  'glyph' => Configure::read('icnLog'), 'itemId' => 'cLogViewer'),
                        array(  'text'  => __('Debug output'),      'iconCls' => 'debug',           'glyph' => Configure::read('icnBug'), 'itemId' => 'cDebug'), 
                        array(  'text'  => __('Translation manager'), 'iconCls' => 'translate',       'glyph' => Configure::read('icnTranslate'),'itemId' => 'cI18n'),
                        array(  'text'  => __('Rights manager'),    'iconCls' => 'rights',          'glyph' => Configure::read('icnKey'), 'itemId' => 'cAcos'),  
                    )
                )
            ),
            //Finances
            array(  'text'  => __('Finances'),  'iconCls' => 'realms',  'glyph' => Configure::read('icnFinance') ,'menu'  =>
                 array( 'items' =>
                    array(
                        array(
                            'text'      => __('Paypal'),
                            'iconCls'   => 'key',  
                            'glyph'     => Configure::read('icnOnlineShop'),
                            'itemId'    => 'cFinPaypalTransactions'
                        ),
                        array(
                            'text'      => __('PayU'), 
                            'iconCls'   => 'realms',
                            'glyph'     => Configure::read('icnOnlineShop'), 
                            'itemId'    => 'cFinPayUTransactions'
                        ),
                    )
                )
            ),

            //Permanent users
            array(  'text'  => __('Permanent Users'),  'iconCls' => 'users',  'glyph' => Configure::read('icnUser') ,'menu'  =>
                 array( 'items' =>
                    array(
                        array(
                            'text'      => __('Permanent Users'),
                            'glyph'     => Configure::read('icnUser'),
                            'itemId'    => 'cPermanentUsers'
                        ),
                        array(
                            'text'      => __('BYOD Manager'), 
                            'glyph'     => Configure::read('icnDevice'), 
                            'itemId'    => 'cDevices'
                        ),
                        array(
                            'text'      => __('Top-ups'), 
                            'glyph'     => Configure::read('icnTopUp'), 
                            'itemId'    => 'cTopUps'
                        ),
                    )
                )
            ),
            array(  'text'  => __('Vouchers'),        'iconCls' => 'vouchers','glyph' => Configure::read('icnVoucher'), 'itemId' => 'cVouchers'),
        );

        //Optional experimental stuff 
        if(Configure::read('experimental.active') == true){
            array_push($menus,array(  'text'  => __('Auto Setup'), 'iconCls' => 'setup', 'glyph' => Configure::read('icnConfigure'), 'itemId' => 'cAutoSetups'));
        }

        array_push($menus,array(  'text'  => __('MESHdesk'),           'iconCls' => 'mesh',  'glyph' => Configure::read('icnMesh'), 'itemId' => 'cMeshes'));
        array_push($menus,array(  'text'  => __('Dynamic login pages'),'iconCls' => 'dynamic_pages', 'glyph' => Configure::read('icnDynamic'), 'itemId' => 'cDynamicDetails'));

        return $menus;
    }

    private function _build_ap_menus($id){

        $menu   = array();

        //Add-on for Password Manager Only (Typically Hotel Front Desk)
        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), "Access Providers/Other Rights/Password Manager Only")){
            return $menu;
        }

        $base   = "Access Providers/Controllers/";

        //____ Realms and Providers ____
        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Realms/index")){    //Will not give an AP AP rigts without this

                //___Check the sub-menu rights___:
                $sm_r_p = array();
                if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."AccessProviders/index")){
                    array_push($sm_r_p, array('text' => __('Access Providers') ,'iconCls' => 'key', 'glyph' => Configure::read('icnKey'),    'itemId' => 'cAccessProviders'));
                }
                //Then the one we checked for ... realms
                array_push($sm_r_p, array('text' => __('Realms') , 'iconCls' => 'realms', 'glyph' => Configure::read('icnRealm'), 'itemId' => 'cRealms'));
                //___ END Sub Menu___

            array_push($menu, array(  'text'  => __('Realms and Providers'),  'iconCls' => 'realms', 'glyph' => Configure::read('icnRealm'), 'menu'  => array('items' =>$sm_r_p)));     
        }

        //____ NAS devices _____
        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Nas/index")){    //Required to show the NAS Devices menu item

            $sm_nas_devices = array();
            array_push($sm_nas_devices, array(  'text'  => __('NAS Devices'),  'iconCls' => 'nas', 'glyph' => Configure::read('icnNas'),  'itemId' => 'cNas'));

            //___Check the sub-menu rights___:
            if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Tags/index")){
                array_push($sm_nas_devices, array(  'text'  => __('NAS Device tags'),   'iconCls' => 'tags', 'glyph' => Configure::read('icnTag'), 'itemId' => 'cTags'));
            } 
            //___ END Sub Menu___

            array_push($menu, array(  'text'  => __('NAS Devices'),  'iconCls' => 'nas', 'glyph' => Configure::read('icnNas'), 'menu'  => array('items' =>$sm_nas_devices)));     
        }

        //____ Profiles _____
        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Profiles/index")){    //Required to show the Profiles menu item

            $sm_profiles = array();

            //___Check the sub-menu rights___:
            if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."ProfileComponents/index")){
                array_push($sm_profiles, array('text' => __('Profile Components') ,  'iconCls' => 'components', 'glyph' => Configure::read('icnComponent'), 'itemId' => 'cProfileComponents'));
            } 
            //___ END Sub Menu___

            array_push($sm_profiles, array('text' => __('Profiles') ,  'iconCls' => 'profiles', 'glyph' => Configure::read('icnProfile'),'itemId' => 'cProfiles'));

            array_push($menu, array(  'text'  => __('Profiles'),  'iconCls' => 'profiles', 'glyph' => Configure::read('icnProfile'),  'menu'  => array('items' =>$sm_profiles)));     
        }

        //____ Tools ____
        array_push($menu,
             array(  'text'  => __('Tools'),  'iconCls' => 'tools', 'glyph' => Configure::read('icnLight'),  'menu'  =>
                     array( 'items' =>
                        array(
                            array(  'text'  => __('Activity monitor'),      'iconCls' => 'activity', 'glyph' => Configure::read('icnActivity'),       'itemId' => 'cActivityMonitor'),
                            array(  'text'  => __('RADIUS client'),         'iconCls' => 'radius_client', 'glyph' => Configure::read('icnRadius'),  'itemId' => 'cRadiusClient'),                          
                        )
                    )
                )
        );

        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."PermanentUsers/index")){
             $pu_sub_menu = array(
                        array(
                            'text'      => __('Permanent Users'),
                            'glyph'     => Configure::read('icnUser'),
                            'itemId'    => 'cPermanentUsers'
                        ),
                        array(
                            'text'      => __('BYOD Manager'), 
                            'glyph'     => Configure::read('icnDevice'), 
                            'itemId'    => 'cDevices'
                        ),
                        array(
                            'text'      => __('Top-ups'), 
                            'glyph'     => Configure::read('icnTopUp'), 
                            'itemId'    => 'cTopUps'
                        ),
                    );
            
            array_push($menu, 
                array(  'text'  => __('Permanent Users'), 
                        'glyph' => Configure::read('icnUser'),  
                        'menu'  => array('items' =>$pu_sub_menu))
            );
        }

        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Devices/index")){
            array_push($menu,
                array(  'text'  => __('BYOD Manager'),          'iconCls' => 'devices',  'glyph' => Configure::read('icnDevice'),   'itemId' => 'cDevices')
            );
        }

		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Meshes/index")){
			  array_push($menu,
				array(  'text'  => __('MESHdesk'),  'glyph' => Configure::read('icnMesh'), 'itemId' => 'cMeshes')
			);
		}

        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."DynamicDetails/index")){
            array_push($menu,
                array(  'text'  => __('Dynamic login pages'),  'iconCls' => 'dynamic_pages', 'glyph' => Configure::read('icnDynamic'),'itemId' => 'cDynamicDetails')
            );
        }
        return $menu;
    }


    private function _build_admin_shortcuts(){
        $items = array();
        array_push($items, array( 'name'    => __('Permanent Users'), 'iconCls' => 'users-shortcut', 'controller' => 'cPermanentUsers'));
        array_push($items, array( 'name'    => __('BYOD Manager'), 'iconCls' => 'byod-shortcut', 'controller' => 'cDevices'));
        array_push($items, array( 'name' => __('Vouchers'), 'iconCls' => 'vouchers-shortcut', 'controller' => 'cVouchers'));
        array_push($items, array( 'name'    => __('Activity monitor'), 'iconCls' => 'activity-shortcut', 'controller' => 'cActivityMonitor'));
        array_push($items, array( 'name'    => __('Password manager'), 'iconCls' => 'password-shortcut', 'controller' => 'cPassword'));
        return $items;
    }




    private function _build_ap_shortcuts($id){

        $items = array();

        //Add-on for Password Manager Only (Typically Hotel Front Desk)
        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), "Access Providers/Other Rights/Password Manager Only")){
            //WIP
            array_push($items, array( 'name'    => __('Password manager'), 'iconCls' => 'password-shortcut', 'controller' => 'cPassword'));
            return $items;
        }

        $base   = "Access Providers/Controllers/";

       
        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."PermanentUsers/index")){
            array_push($items, array( 'name'    => 'Permanent Users', 'iconCls' => 'users-shortcut', 'controller' => 'cPermanentUsers'));
        }

        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Devices/index")){
            array_push($items, array( 'name'    => 'BYOD Manager', 'iconCls' => 'byod-shortcut', 'controller' => 'cDevices'));
        }

        if($this->Acl->check(array('model' => 'User', 'foreign_key' => $id), $base."Vouchers/index")){
            array_push($items, array( 'name' => 'Vouchers', 'iconCls' => 'vouchers-shortcut', 'controller' => 'cVouchers'));
        }

        array_push($items, array( 'name'    => 'Activity monitor', 'iconCls' => 'activity-shortcut', 'controller' => 'cActivityMonitor'));
        return $items;

    }

}
