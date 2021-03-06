<?php
//The groups that is defined 
$config['group']['admin']   = 'Administrators';     //Has all the rights
$config['group']['ap']      = 'Access Providers';   //Has selected right
$config['group']['user']    = 'Permanent Users';              //Has very limited rights

$config['language']['default']    = '4_4';     //This is the id 4 of Languages and id 4 of countries (GB_en)

$config['commands']['msgcat'] = '/usr/bin/msgcat';

//Define the connection types and if they are active or not
$config['conn_type'][0]     = array('name' => __('Direct (Fixed IP)'),  'id' => 'direct',   'active' => true);
$config['conn_type'][1]     = array('name' => __('OpenVPN'),            'id' => 'openvpn',  'active' => false);
$config['conn_type'][2]     = array('name' => __('PPTP'),               'id' => 'pptp',     'active' => false);
$config['conn_type'][3]     = array('name' => __('Dynamic Client'),     'id' => 'dynamic',  'active' => true);

//Define the location of ccd (client config directory)
//FIXME This value does not get read by the OpenvpnClients Model - investigate
$config['openvpn']['ccd_dir_location']  = '/etc/openvpn/ccd/';
$config['openvpn']['ip_half']           = '10.8.';

//Define pptp specific settings
$config['pptp']['start_ip']                        = '10.20.30.2';
$config['pptp']['server_ip']                       = '10.20.30.1';
$config['pptp']['chap_secrets']                    = '/etc/ppp/chap-secrets';

//Define dynamic specific settings
$config['dynamic']['start_ip']                     = '10.120.0.1'; //Make this a Class B subnet (64000) which will never include a value also specified for a FIXED client

//Dictionary files to include for profiles...
$config['freeradius']['path_to_dictionary_files']   = '/usr/local/share/freeradius/';
$config['freeradius']['main_dictionary_file']       = '/usr/local/etc/raddb/dictionary';
$config['freeradius']['radclient']                  = '/usr/local/bin/radclient';


//Define the configured dynamic attributes
$config['dynamic_attributes'][0]     = array('name' => 'Called-Station-Id',  'id' => 'Called-Station-Id',   'active' => true);
$config['dynamic_attributes'][1]     = array('name' => 'Mikrotik-Realm',     'id' => 'Mikrotik-Realm',      'active' => true);
$config['dynamic_attributes'][2]     = array('name' => 'NAS-Identifier',     'id' => 'NAS-Identifier',      'active' => true);

//Define nas types
$config['nas_types'][0]     = array('name' => 'Other',                  'id' => 'other',                    'active' => true);
$config['nas_types'][1]     = array('name' => 'CoovaChilli',            'id' => 'CoovaChilli',              'active' => true);
$config['nas_types'][2]     = array('name' => 'CoovaChilli-Heartbeat',  'id' => 'CoovaChilli-Heartbeat',    'active' => true);
$config['nas_types'][3]     = array('name' => 'Mikrotik',               'id' => 'Mikrotik',                 'active' => true);
$config['nas_types'][3]     = array('name' => 'Mikrotik-Heartbeat',     'id' => 'Mikrotik-Heartbeat',       'active' => true);

//Define Voucher format types
$config['voucher_formats'][0]     = array('name' => 'Generic A4',               'id' => 'a4',               'active' => true);
$config['voucher_formats'][1]     = array('name' => 'Generic A4 Page/Voucher',  'id' => 'a4_page',          'active' => true);
$config['voucher_formats'][2]     = array('name' => 'Avery 5160',               'id' => '5160',             'active' => true);
$config['voucher_formats'][3]     = array('name' => 'Avery 5161',               'id' => '5161',             'active' => true);
$config['voucher_formats'][4]     = array('name' => 'Avery 5162',               'id' => '5162',             'active' => true);
$config['voucher_formats'][5]     = array('name' => 'Avery 5163',               'id' => '5163',             'active' => true);
$config['voucher_formats'][6]     = array('name' => 'Avery 5164',               'id' => '5164',             'active' => false); //gives trouble
$config['voucher_formats'][7]     = array('name' => 'Avery 8600',               'id' => '8600',             'active' => true); 
$config['voucher_formats'][8]     = array('name' => 'Avery L7160',              'id' => 'L7160',            'active' => true); 
$config['voucher_formats'][9]     = array('name' => 'Avery L7161',              'id' => 'L7161',            'active' => true); 
$config['voucher_formats'][10]     = array('name' => 'Avery L7163',              'id' => 'L7163',            'active' => true); 



//FIXME To incorporate
$config['paths']['wallpaper_location']  = "/rd/resources/images/wallpapers/";

$config['paths']['dynamic_photos']      = "/cake2/rd_cake/webroot/img/dynamic_photos/";   
$config['paths']['dynamic_detail_icon'] = "/cake2/rd_cake/webroot/img/dynamic_details/";

//Define default settings for the users:
$config['user_settings']['wallpaper']       = "9.jpg";
$config['user_settings']['map']['type']     = "ROADMAP";
$config['user_settings']['map']['zoom']     = 18;
$config['user_settings']['map']['lng']      = -71.0955740216735;
$config['user_settings']['map']['lat']      = 42.3379770178396;


//====== Mobile devices that do not support Sencha Touch =====
//ID your device by visiting: http://127.0.0.1/cake2/rd_cake/dynamic_details/id_me
$config['DynamicLogin']['use_jquery']       = array(
   //array('type' => 'contain',  'value' => 'Ubuntu'),
   //array('type' => 'match',    'value' => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.19 (KHTML, like Gecko) Ubuntu/10.04 Chromium/18.0.1025.151 Chrome/18.0.1025.151 Safari/535.19'),
);

//The location of the mobile and desktop login pages for CoovaChilli
//$config['CoovaDynamicLogin']['mobile']      = '/rd_login_pages/mobile/CoovaChilli/index.html';

$config['CoovaDynamicLogin']['mobile']          = '/rd_login_pages/mobile/CoovaChilli/build/production/CoovaChilli/index.html';
$config['CoovaDynamicLogin']['jquery_mobile']   = '/rd_login_pages/jquery_mobile/CoovaChilli/index.html';

//$config['CoovaDynamicLogin']['desktop']     = '/rd_login_pages/desktop/CoovaChilli/index.html';
$config['CoovaDynamicLogin']['desktop']     = '/rd_login_pages/desktop/CoovaChilli/build/production/CoovaChilli/index.html';


//The location of the mobile and desktop login pages for Mikrotik
//$config['MikrotikDynamicLogin']['mobile']      = '/rd_login_pages/mobile/Mikrotik/index.html';

$config['MikrotikDynamicLogin']['mobile']       = '/rd_login_pages/mobile/Mikrotik/build/production/Mikrotik/index.html';
$config['MikrotikDynamicLogin']['jquery_mobile']= '/rd_login_pages/jquery_mobile/Mikrotik/index.html';


//$config['MikrotikDynamicLogin']['desktop']     = '/rd_login_pages2/desktop/Mikrotik/index.html';
$config['MikrotikDynamicLogin']['desktop']     = '/rd_login_pages/desktop/Mikrotik/build/production/Mikrotik/index.html';


//Set to true to allow  the user to remove their device out of the realm it has been assigned to
$config['UserCanRemoveDevice']              = true;


//========== WORK IN PROGRES =============
//----- PayU settings ----- Change to live and your details once working
$config['payu']['soapWdslUrl']      = 'https://staging.payu.co.za/service/PayUAPI?wsdl';
$config['payu']['payuRppUrl']       = 'https://staging.payu.co.za/rpp.do?PayUReference=';
$config['payu']['notificationUrl']  = 'http://41.134.95.22/cake2/rd_cake/fin_pay_u_transactions/payu_ipn';
$config['payu']['apiVersion']       = 'ONE_ZERO';
$config['payu']['safeKey']          = '{45D5C765-16D2-45A4-8C41-8D6F84042F8C}';
$config['payu']['soapUsername']     = 'Staging Integration Store 1';
$config['payu']['soapPassword']     = '78cXrW1W';

//--- PayU Vouchers-----
$config['payu']['vouchers']['a']['data_175m'] = array(
    'name'      => '175MB',  
    'price'     => '129.00',
    'currency'  => 'R', 
    'position'  => 'pre',
    'voucher'   => array(
        'activate_on_login' => '1',
        'time_valid'        => '0-02-00-00',
        'expire'            => '05/31/2014',
        'precede'           => '',
        'profile_id'        => 7,
        'pwd_length'        => 5,
        'realm_id'          => 34,
        'sel_language'      => '4_4',
        'user_id'           => '44'
    )
);

$config['payu']['vouchers']['a']['data_400m'] = array(
    'name'      => '400MB',  
    'price'     => '165.00',
    'currency'  => 'R', 
    'position'  => 'pre',
    'voucher'   => array(
        'activate_on_login' => '1',
        'time_valid'        => '2-22-22-00',
        'expire'            => '05/31/2014',
        'precede'           => '',
        'profile_id'        => 7,
        'pwd_length'        => 5,
        'realm_id'          => 34,
        'sel_language'      => '4_4',
        'user_id'           => '44'
    )
);


//From Paypal data 'item_name' (RDVoucher') 'item_number' (rd_v1) option_selection1 ('2Hours')
$config['paypal']['RDVoucher']['rd_v1']['2Hours'] = array(
                                                        'activate_on_login' => '1',
                                                        'time_valid'        => '0-02-00-00',
                                                        'expire'            => '06/31/2014',
                                                        'precede'           => '',
                                                        'profile_id'        => 7,
                                                        'pwd_length'        => 5,
                                                        'realm_id'          => 34,
                                                        'sel_language'      => '4_4',
                                                        'user_id'           => '44'
                                                    ); 

$config['paypal']['RDVoucher']['rd_v1']['4Hours'] = array(
                                                        'activate_on_login' => '1',
                                                        'time_valid'        => '0-04-00-00',
                                                        'expire'            => '06/31/2014',
                                                        'precede'           => '',
                                                        'profile_id'        => 7,
                                                        'pwd_length'        => 5,
                                                        'realm_id'          => 34,
                                                        'sel_language'      => '4_4',
                                                        'user_id'           => '44'
                                                    ); 

$config['paypal']['RDVoucher']['rd_v1']['12Hours'] = array(
                                                        'activate_on_login' => '1',
                                                        'time_valid'        => '0-12-00-00',
                                                        'expire'            => '06/31/2014',
                                                        'precede'           => '',
                                                        'profile_id'        => 7,
                                                        'pwd_length'        => 5,
                                                        'realm_id'          => 34,
                                                        'sel_language'      => '4_4',
                                                        'user_id'           => '44'
                                                    );

//======== END WORK IN PROGRESS ==========

//==== Define glyphs -> We'll use glyphs insteadd of icons
$config['icnLock']      = 57495;
$config['icnYes']       = 57605;
$config['icnMenu']      = 57594;
$config['icnInfo']      = 57479;
$config['icnPower']     = 57541;
$config['icnSpanner']   = 57583;
$config['icnHome']      = 57473;
$config['icnDynamic']   = 57392;
$config['incVoucher']   = 57606;
$config['icnReload']    = 57374;
$config['icnAdd']       = 57537;
$config['icnEdit']      = 57524;
$config['icnDelete']    = 57610;
$config['icnPdf']       = 57447;
$config['icnCsv']       = 57415;
$config['icnRadius']    = 57444;
$config['icnLight']     = 57487;
$config['icnNote']      = 57531;
$config['icnKey']       = 57485;
$config['icnRealm']     = 57557;
$config['icnNas']       = 57589;
$config['icnTag']       = 57592;
$config['icnProfile']   = 57468;
$config['icnComponent'] = 57544;
$config['icnLight']     = 57487;
$config['icnActivity']  = 57408;
$config['icnLog']       = 57438;
$config['icnTranslate'] = 57466;
$config['icnConfigure'] = 57583;
$config['icnUser']      = 57618;
$config['icnVoucher']   = 57606;
$config['icnDevice']    = 57432;
$config['icnMesh']      = 57460;
$config['icnBug']       = 57344;
$config['icnMobile']    = 57431;
$config['icnDesktop']   = 57429;
$config['icnView']      = 57650;
$config['icnMeta']      = 57630;
$config['icnMap']       = 57645;
$config['icnConnect']   = 57489;
$config['icnGraph']     = 57410;
$config['icnKick']      = 57535;
$config['icnClose']     = 57609;
$config['icnFinance']   = 57586;
$config['icnOnlineShop']= 57554;
$config['icnEmail']     = 57378;
$config['icnAttach']    = 57380;
$config['icnCut']       = 57551;
$config['icnTopUp']     = 57419;
$config['icnSubtract']  = 57520;
$config['icnWatch']     = 57628;


//===== MESHdesk ======
//== Encryption types ==
//Define the encryption types and if they are active or not
$config['encryption'][0]     = array('name' => __('None'),              'id' => 'none',     'active' => true);
$config['encryption'][1]     = array('name' => __('WEP'),               'id' => 'wep',      'active' => true);
$config['encryption'][2]     = array('name' => __('WPA Personal'),      'id' => 'psk',      'active' => true);
$config['encryption'][3]     = array('name' => __('WPA2 Personal'),     'id' => 'psk2',     'active' => true);
$config['encryption'][4]     = array('name' => __('WPA Enterprise'),    'id' => 'wpa',      'active' => true);
$config['encryption'][5]     = array('name' => __('WPA2 Enterprise'),   'id' => 'wpa2',     'active' => true);

//== Default mesh settings ==
//Define default settings for the mesh which can be overwritten
$config['mesh_settings']['aggregated_ogms']  		= true;  //Aggregation*
$config['mesh_settings']['ap_isolation']  			= false; //AP Isolation*
$config['mesh_settings']['bonding']   				= false; //Bonding*
$config['mesh_settings']['fragmentation']   		= true;  //Fragmentation*
$config['mesh_settings']['gw_sel_class'] 			= 20; //Client Gateway switching*
$config['mesh_settings']['orig_interval']  			= 1000; //OGM Interval*
$config['mesh_settings']['bridge_loop_avoidance']  	= false; //Bridge loop avoidence*
$config['mesh_settings']['distributed_arp_table'] 	= true; //Distributed ARP table

//== Node start ip for defined mesh ==
$config['mesh_node']['start_ip']    = '10.5.5.1';

//== Default node settings ==
$config['common_node_settings']['password']  		= 'admin'; //Root password on nodes
$config['common_node_settings']['password_hash']  	= '$1$TJn8xhHP$BLhc3QEW54de0V8yCYD/T.'; //Output of 'openssl passwd -1 admin'
$config['common_node_settings']['power']     		= 100; //% of tx power to use on devices
$config['common_node_settings']['all_power'] 		= true; //Apply this power to all devices?
$config['common_node_settings']['two_chan']  		= 6; //% Channel to use on 2.4G wifi
$config['common_node_settings']['five_chan'] 		= 44; //% Channel to use on 5G wifi
$config['common_node_settings']['heartbeat_interval']  = 60; //Send a heartbeat pulse through every interval seconds
$config['common_node_settings']['heartbeat_dead_after'] = 300; //Mark a device as dead if we have not had heartbeats in this time
 

//== Device types for MESHdesk ==

$config['hardware'][0]      = array(
		'name' 		=> __('Dragino MS14'),   	
		'id'    	=> 'dragino', 
		'active'    => true, 
		'max_power' => 18,
		'eth_br'	=> 'eth0 eth1',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'	
);

$config['hardware'][1]      = array(
		'name' 		=> __('MP2 Basic'),   	
		'id'    	=> 'mp2_basic', 
		'active'    => true, 
		'max_power' => 18,
		'eth_br'	=> 'eth0 eth1',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'	
);

$config['hardware'][2]      = array(
		'name' 		=> __('MP2 Phone'),   	
		'id'    	=> 'mp2_phone', 
		'active'    => true, 
		'max_power' => 18,
		'eth_br'	=> 'eth0 eth1',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'	
);


$config['hardware'][3]      = array(
		'name' 		=> __('OpenMesh OM2P'),  	
		'id'    	=> 'om2p' ,   
		'active'    => true, 
		'max_power' => '20',
		'eth_br'	=> 'eth0 eth1',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'	
);

$config['hardware'][4]      = array(
		'name' 		=> __('PicoStation M2'),	
		'id'    	=> 'pico2', 
		'active'    => true, 
		'max_power' => '28',
		'eth_br'	=> 'eth0',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'
);

$config['hardware'][5]      = array(
		'name' 		=> __('PicoStation M5'),	
		'id'    	=> 'pico5', 
		'active'    => true, 
		'max_power' => '28',
		'eth_br'	=> 'eth0',
		'two'		=> false,
		'five'		=> true,
		'hwmode'	=> '11na'
);

$config['hardware'][6]      = array(
		'name' 		=> __('NanoStation M2'),	
		'id'    	=> 'nano2', 
		'active'    => true, 
		'max_power' => '28',
		'eth_br'	=> 'eth0',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'
);


$config['hardware'][7]      = array(
		'name' 		=> __('NanoStation M5'),	
		'id'    	=> 'nano5', 
		'active'    => true, 
		'max_power' => '28',
		'eth_br'	=> 'eth0',
		'two'		=> false,
		'five'		=> true,
		'hwmode'	=> '11na'	
);

$config['hardware'][8]      = array(
		'name' 		=> __('UniFi AP'),	
		'id'    	=> 'unifiap', 
		'active'    => true, 
		'max_power' => '23',
		'eth_br'	=> 'eth0',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'	
);

$config['hardware'][9]      = array(
		'name' 		=> __('UniFi AP-LR'),	
		'id'    	=> 'unifilrap', 
		'active'    => true, 
		'max_power' => '27',
		'eth_br'	=> 'eth0',
		'two'		=> true,
		'five'		=> false,
		'hwmode'	=> '11ng'	
);



//== MESHdesk SSID/BSSID
$config['MEHSdesk']['bssid'] = "02:CA:FE:CA:00:00"; //This will be the first one; subsequent ones will be incremented 

//== MESHdesk Defaul MAP settings ==
$config['mesh_specifics']['map']['type']     = "ROADMAP";
$config['mesh_specifics']['map']['zoom']     = 18;
$config['mesh_specifics']['map']['lng']      = -71.0955740216735;
$config['mesh_specifics']['map']['lat']      = 42.3379770178396;

//=== EXPERIMENTAL STUFF =====
//Show experimental menus
$config['experimental']['active']                   = false;

//IP Settings
$config['experimental']['defaults']['ip_mask']      = '255.255.255.0';
$config['experimental']['defaults']['ip_dns_1']     = '192.168.99.99';
$config['experimental']['defaults']['ip_dns_2']     = '192.168.99.100';
$config['experimental']['defaults']['ip_dns_2']     = '192.168.99.100';

//Wifi Settings
$config['experimental']['defaults']['wifi_active']  = true;
$config['experimental']['defaults']['channel']      = 1;
$config['experimental']['defaults']['power']        = 10;
$config['experimental']['defaults']['distance']     = 30;


$config['experimental']['defaults']['ssid_secure']  = 'RD Wireless';
$config['experimental']['defaults']['radius']       = '192.168.99.99';
$config['experimental']['defaults']['secret']       = 'testing123';

$config['experimental']['defaults']['ssid_open']    = 'RD Guest';

//OpenVPN Settings
$config['experimental']['defaults']['vpn_server']   = '192.168.99.99';

$config['experimental']['openvpn']['start_ip']      = '10.8.1.2';
$config['experimental']['openvpn']['ca']            = '-----BEGIN CERTIFICATE-----
MIIDYDCCAsmgAwIBAgIJAK+7qcW3f0W6MA0GCSqGSIb3DQEBBQUAMH4xCzAJBgNV
BAYTAlpBMRAwDgYDVQQIEwdHYXV0ZW5nMREwDwYDVQQHEwhQcmV0b3JpYTEMMAoG
A1UEChMDWUZpMRMwEQYDVQQDEwpPcGVuVlBOLUNBMScwJQYJKoZIhvcNAQkBFhhk
aXJrdmFuZGVyd2FsdEBnbWFpbC5jb20wHhcNMTMwNDE1MDgxOTM1WhcNMjMwNDEz
MDgxOTM1WjB+MQswCQYDVQQGEwJaQTEQMA4GA1UECBMHR2F1dGVuZzERMA8GA1UE
BxMIUHJldG9yaWExDDAKBgNVBAoTA1lGaTETMBEGA1UEAxMKT3BlblZQTi1DQTEn
MCUGCSqGSIb3DQEJARYYZGlya3ZhbmRlcndhbHRAZ21haWwuY29tMIGfMA0GCSqG
SIb3DQEBAQUAA4GNADCBiQKBgQDmfB1FBrjuB5xRYJGjr8fCgoxY9M99nPzMcnBQ
tFnkc7TjsoPfDTAOgecpmwfPrfxjBvi9Vae+TwiiwiLLMCewvXP47vySRhXIRUVL
OvEcgapdIGbl26JaHyUrjbsAdrc/Fp5OTmjU5EZ/BciheZJr+ZLUWg/5bkDtI3rH
g+moPQIDAQABo4HlMIHiMB0GA1UdDgQWBBTf/iG94D0pd+3z5RURkZ+43j43LDCB
sgYDVR0jBIGqMIGngBTf/iG94D0pd+3z5RURkZ+43j43LKGBg6SBgDB+MQswCQYD
VQQGEwJaQTEQMA4GA1UECBMHR2F1dGVuZzERMA8GA1UEBxMIUHJldG9yaWExDDAK
BgNVBAoTA1lGaTETMBEGA1UEAxMKT3BlblZQTi1DQTEnMCUGCSqGSIb3DQEJARYY
ZGlya3ZhbmRlcndhbHRAZ21haWwuY29tggkAr7upxbd/RbowDAYDVR0TBAUwAwEB
/zANBgkqhkiG9w0BAQUFAAOBgQCLiXXSKSPIAkMVwFq935zb8RIoEu6fVbo9nbwN
fVIBgZIqpSZT59Ueef/l5zcTabRH7cIZGe6RqBK17I2nSr4s5+Ut4lgdvu7xe3g8
t72pyVfDVfHr1sSMRGSjVt1SPI13uz3a6hzFVFxBoHWdyhXoGmvidNIm09hwPsJN
S6UMIw==
-----END CERTIFICATE-----
';
$config['experimental']['openvpn']['mask']          = '255.255.0.0';
$config['experimental']['openvpn']['broadcast']     = '10.8.255.255';

$config['experimental']['eduroam']['radius_server'] = '192.168.10.20';
$config['experimental']['eduroam']['radius_secret'] = 'eduroam';

$config['experimental']['snmp']['ro']               = 'public';
$config['experimental']['snmp']['rw']               = 'private';
$config['experimental']['snmp']['contact']          = 'radiusdesk@gmail.com';

?>
