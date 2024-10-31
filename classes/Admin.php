<?php
/**
* @package semplisio
*/


namespace Semplisio;


class Admin {
    
    
    public function register(){
        add_action("admin_menu", array($this, "addMenu"));
        add_action('wp_ajax_semplisio-diagnostica', array($this, 'diagnosticaCheck'));
        add_action('wp_ajax_semplisio-gestionale', array($this, 'gestionaleCheck'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));   
    }

    public function unregister(){
        $this->resetConnector();
    }

    public function enqueueScripts(){
        $version = '1.0';
        wp_enqueue_style('semplisio-style', Init::$STATIC_URL.'style.css?r='.time(), array(), $version);
        wp_enqueue_script('semplisio-script', Init::$PLUGIN_URL.'js/script.js', array('jquery'), $version);
    }

    public function addMenu(){
        $access_capability = 'manage_options';
        add_menu_page( 'Semplisio', 'Semplisio', $access_capability, 'semplisio', array($this, 'home'), Init::$STATIC_URL.'semplisio.png', 40 );
        // add_submenu_page( 'semplisio', __('Home','semplisio'), __('Home','semplisio'), $access_capability, 'semplisio', array($this, 'home') );
        // add_submenu_page( 'semplisio', __('Attivazione','semplisio'), __('Attivazione','semplisio'), $access_capability, 'semplisio-attivazione', array($this, 'attivazione') );
        // add_submenu_page( 'semplisio', __('Diagnostica','semplisio'), __('Diagnostica','semplisio'), $access_capability, 'semplisio-diagnostica', array($this, 'diagnostica') );
        // add_submenu_page( 'semplisio', __('Impostazioni','semplisio'), __('Impostazioni','semplisio'), $access_capability, 'semplisio-impostazioni', array($this, 'impostazioni') );
        // add_submenu_page( 'semplisio', __('Workflows','semplisio'), __('Workflows','semplisio'), $access_capability, 'semplisio-workflow', array($this, 'workflows') );
               
    }

    function resetConnector(){
        update_option( 'semplisio-token', '0' );
        update_option( 'semplisio-connector', '0' );
        update_option( 'semplisio-erp-connector-id', '0' );
        update_option( 'semplisio-gestionale', '0' );
        update_option( 'semplisio-option', '0' );
        update_option( 'semplisio-wc-consumer_key', '0' );
        update_option( 'semplisio-workflows', '0' );
    }

    function home(){
        $tab = sanitize_text_field((isset($_REQUEST['tab']) ? $_REQUEST['tab'] : ''));
        if($tab=='attivazione'){
            $this->attivazione();
        } else if($tab=='diagnostica'){
            $this->diagnostica();
        } else if($tab=='impostazioni'){
            $this->impostazioni();
        } else if($tab=='workflows'){
            $this->workflows();
        } else {
            include Init::$PLUGIN_DIR.'partials/admin/home.php';
        }
    }

    function request($url, $body, $token = null, $method = 'POST'){
        if($token==null){
            $token = get_option( 'semplisio-token', '' );
        }


        $opts = array(
            'method' => $method,
            'headers' => array('Content-Type' =>  'application/json', 'token' => $token)
        );

        if($method=='POST' && !empty($body)){
            $opts['body'] = json_encode($body);
        }

        $result_req = wp_remote_request( Init::$API_URL.$url, $opts );
        
        $result = json_decode($result_req['body']);
        if(!$result){
            $result = new \stdClass();
            $result->status = 'KO';
        }   
        $result->headers = $result_req['headers'];
        return $result;
    }

    function attivazione(){
        if(isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action'])=='salva-attivazione'){
            $token = sanitize_text_field($_REQUEST['semplisio-token']);
            $old_token = get_option('semplisio-token', '');
            $result = $this->request('/api/v2/wppluginapi/check_token', null, $token);
            
            if($result && isset($result->token_valid) && $result->token_valid){
                if($token!=$old_token){
                    $this->resetConnector();
                }
                update_option('semplisio-token', $token);
                update_option('semplisio-option', array(
                    'sync_type_options' => $result->sync_type_options,
                    'sync_time_options' => $result->sync_time_options,
                    'sync_frequency_options' => $result->sync_frequency_options,
                    'allowed_services' => $result->allowed_services
                ));
                $msg = Init::_tne('Token valido!!','semplisio');
                
            } else {
                update_option('semplisio-token', '');
                update_option('semplisio-option', array());
                $msg = Init::_tne('Token non valido. Contatta l\'assistenza','semplisio');
            }
        }
        include Init::$PLUGIN_DIR.'partials/admin/attivazione.php';
    }

    function diagnostica(){

        include Init::$PLUGIN_DIR.'partials/admin/diagnostica.php';
    }

    function diagnosticaCheck(){
        $siteurl = get_option( 'siteurl', '00000' );
        $permalink = get_option( 'permalink_structure', '' )=='/%postname%/';
        $https = substr($siteurl, 0, 5)=='https' /*|| 1==1*/;
        $api = false;

        global $wpdb;
        $keyname         = 'semplisio';
        $consumer_key    = get_option( 'semplisio-wc-consumer_key', null );
        $row             = $wpdb->get_row("select * from ".$wpdb->prefix."woocommerce_api_keys where consumer_key='".wc_api_hash($consumer_key)."'");
        if($row){
            $consumer_secret = $row->consumer_secret;
        } else {
            $user_id         = get_current_user_id();
            $permissions     = 'read_write';
            $consumer_key    = 'ck_' . wc_rand_hash();
            $consumer_secret = 'cs_' . wc_rand_hash();
            update_option( 'semplisio-wc-consumer_key', $consumer_key);
            $wpdb->insert(
                $wpdb->prefix . 'woocommerce_api_keys',
                array(
                    'user_id'         => $user_id,
                    'description'     => $keyname,
                    'permissions'     => $permissions,
                    'consumer_key'    => wc_api_hash( $consumer_key ),
                    'consumer_secret' => $consumer_secret,
                    'truncated_key'   => substr( $consumer_key, -7 ),
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

        $apiori = $this->request('/api/v2/wppluginapi/check_api', array(
                "consumer_secret" => $consumer_secret,
                "consumer_key" => $consumer_key,
                "wp_url" => $siteurl.'/wp-json/',
        ));
        
        $api = $apiori && strtoupper($apiori->status)=='OK';
                
        if($permalink && $https && $api){
            $conn = get_option( 'semplisio-connector', null);
            $conn_id = ($conn ? $conn['connector_id'] : null);
            
            $connector = $this->request('/api/v2/wppluginapi/create_connector', array(
                            'connector_id' => $conn_id,
                            'id_connector' => $conn_id,
                            "consumer_secret" => $consumer_secret,
                            "consumer_key" => $consumer_key,
                            "wp_url" => $siteurl.'/wp-json/'
            ));
            
            // $connector = json_decode( $connector_res);
            if(strtoupper($connector->status)=='OK'){
                update_option( 'semplisio-connector', array(
                    'connector_id' => $connector->connector_id,
                    'webhook_key' => $connector->webhook_key,
                    'webhook_urls' => $connector->webhook_urls
                ) );

                $option = get_option('semplisio-option', null);
                if($option) {
                    $topics = array(
                        'customers_store'  => 'customer.created',
                        'customers_update' => 'customer.updated',
                        'orders_store'     => 'order.created',
                        'orders_update'    => 'order.updated'
                    );
                    foreach($connector->webhook_urls as $key => $url) {
                        if($key=='customers_store' && !$option['allowed_services']->customer_to_erp->store) continue;
                        if($key=='customers_update' && !$option['allowed_services']->customer_to_erp->update) continue;
                        if($key=='orders_store' && !$option['allowed_services']->orders_to_erp->store) continue;
                        if($key=='orders_update' && !$option['allowed_services']->orders_to_erp->update) continue;

                        $row = $wpdb->get_row("select * from ".$wpdb->prefix."wc_webhooks where delivery_url='".$url."' and secret='".$connector->webhook_key."'");
                        if(!$row){
                            $wc_hook = new \WC_Webhook();
                            $topic = $topics[$key];
                            $data = array(
                                'status'           => 'active',
                                'name'             => 'semplisio-'.$key,
                                'user_id'          => get_current_user_id(),
                                'delivery_url'     => $url,
                                'secret'           => $connector->webhook_key,
                                'topic'            => $topic,
                                'date_created'     => date( 'Y-m-d H:i:s' ),
                                'date_created_gmt' => date( 'Y-m-d H:i:s' ),
                                'api_version'      => str_replace('wp_api_v', '', $wc_hook->get_api_version()),
                                'failure_count'    => 0,
                                'pending_delivery' => 0
                            );
                    
                            $wpdb->insert( $wpdb->prefix . 'wc_webhooks', $data ); 
                        }
                    }
                }
            }
        }
        
        die(json_encode( array(
            array('id' => 'permalink', 'status' => $permalink),
            array('id' => 'https', 'status' => $https),
            array('id' => 'api', 'status' => $api),
            array('id' => 'apiori', 'status' => $apiori),
        )));
    }

    function impostazioni(){
        $gestionali = $this->request('/api/v2/wppluginapi/erp_list', null, null, 'GET');
        
        if(isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action'])=='salva-gestionale'){
            $dati = array(
                'g' => sanitize_text_field($_REQUEST['semplisio-gestionale'])
            );
            $connector_fields = array();
            foreach($_REQUEST as $key => $val){
                $val = sanitize_text_field($val);
                if(substr($key, 0, 10)=='semplisio-' && $key!='semplisio-gestionale'){
                    $val = sanitize_text_field($val);
                    $dati[substr($key, 10)] = $val;
                    $connector_fields[] = array('key' => substr($key, 10), 'value' => $val);
                }
            }
            update_option( 'semplisio-gestionale', $dati );
            $erp_connector_id = get_option( 'semplisio-erp-connector-id', null );
            $res = $this->request('/api/v2/wppluginapi/save_erp', array(
                'erp_connector_id' => $erp_connector_id,
                'platform_id' => $dati['g'],
                'connector_fields' => $connector_fields
            ));
            
            if($res && strtoupper($res->status)=='OK' && $res->erp_connector_id){
                update_option( 'semplisio-erp-connector-id', $res->erp_connector_id );
                //$msg = _tne('Salvataggio effettuato!!','semplisio');
                header('Location: /wp-admin/admin.php?page=semplisio&tab=workflows');
            } else {
                $msg = Init::_tne('Si &egrave; verificato un errore durante il salvataggio!!','semplisio');
            }
        } else {
            $dati = get_option( 'semplisio-gestionale', array('g' => '') );
            if(empty($dati)) $dati = array('g' => '');
        }
        include Init::$PLUGIN_DIR.'partials/admin/impostazioni.php';
    }

    function gestionaleCheck(){
        $dati = array(
            'g' => sanitize_text_field($_REQUEST['semplisio-gestionale'])
        );
        $connector_fields = array();
        foreach($_REQUEST as $key => $val){
            $val = sanitize_text_field($val);
            if(substr($key, 0, 10)=='semplisio-' && $key!='semplisio-gestionale'){
                $dati[substr($key, 10)] = $val;
                $connector_fields[] = array('key' => substr($key, 10), 'value' => $val);
            }
        }
        $res = $this->request('/api/v2/wppluginapi/check_api_erp', array(
            'platform_id' => $dati['g'],
            'connector_fields' => $connector_fields
        ));

        if($res && strtoupper($res->status)=='OK' && $res->check_result){
            die('true');
        } else {
            die('false');
        }
    }

    function workflows(){
        $options = get_option( 'semplisio-option', null );
        $dati = get_option( 'semplisio-workflows', array(
                'filtro-prodotti' => '', 
                'all' => false, 
                'partial' => false, 
                'ordini' => false, 
                'ora' => '',
                'products_to_wp_all_ie_id' => null,
                'products_to_wp_filter_ie_id' => null,
                'products_to_wp_filter_wf_id' => null,
                'customer_to_erp_wf_id' => null,
                'orders_to_erp_wf_id' => null
            ) 
        );
        
        if(isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action'])=='salva-workflows'){
            $all = isset($_REQUEST['import-all']);
            $partial = isset($_REQUEST['import-partial']);
            $import_ora = isset($_REQUEST['import-prodotti-ora']) ? sanitize_text_field($_REQUEST['import-prodotti-ora']) : null;
            $ordini = isset($_REQUEST['import-ordini']);
            
            if($partial){
                $filtro = sanitize_text_field($_REQUEST['semplisio-filtro-prodotti']);
            } else {
                $filtro = null;
            }
            
            $conn = get_option( 'semplisio-connector', null);
            $conn_id = ($conn ? $conn['connector_id'] : '');

            $datisemplisio = array(
                'id_connector' => $conn_id,
                'erp_connector_id' => get_option( 'semplisio-erp-connector-id', '' ),
                'products_to_wp_all' => array(
                    'enable' => $all,
                    'sync_type' => $options['sync_type_options'],
                    'sync_time' => ($options['sync_type_options']=='giornaliera' ? $import_ora : null),
                    'sync_frequency' => ($options['sync_type_options']=='oraria' ? $import_ora : null),
                    'ie_id' => (empty($dati['products_to_wp_all_ie_id']) ? null : $dati['products_to_wp_all_ie_id'])
                ),
                'products_to_wp_filter' => array(
                    'enable' => $partial,
                    'filter' => $filtro,
                    'sync_type' => $options['sync_type_options'],
                    'sync_time' => ($options['sync_type_options']=='giornaliera' ? $import_ora : null),
                    'sync_frequency' => ($options['sync_type_options']=='oraria' ? $import_ora : null),
                    'ie_id' => (empty($dati['products_to_wp_filter_ie_id']) ? null : $dati['products_to_wp_filter_ie_id']),
                    'wf_id' => (empty($dati['products_to_wp_filter_wf_id']) ? null : $dati['products_to_wp_filter_wf_id'])
                ),
                'customer_to_erp' => array(
                    'enable' => false,
                    'trigger' => null,
                    'wf_id' => (empty($dati['customer_to_erp_wf_id']) ? null : $dati['customer_to_erp_wf_id'])
                ),
                'orders_to_erp' => array(
                    'enable' => $ordini,
                    'trigger' => null,
                    'wf_id' => (empty($dati['orders_to_erp_wf_id']) ? null : $dati['orders_to_erp_wf_id'])
                )
            );

            
            $res = $this->request('/api/v2/wppluginapi/save_config', $datisemplisio);

            $dati = array(
                'filtro-prodotti' => $filtro, 
                'all' => $all, 
                'partial' => $partial, 
                'ordini' => $ordini, 
                'ora' => $import_ora,
                'products_to_wp_all_ie_id' => $res->products_to_wp_all->ie_id,
                'products_to_wp_filter_ie_id' => $res->products_to_wp_filter->ie_id,
                'products_to_wp_filter_wf_id' => $res->products_to_wp_filter->wf_id,
                'customer_to_erp_wf_id' => $res->customer_to_erp->wf_id,
                'orders_to_erp_wf_id' => $res->orders_to_erp->wf_id,
            );
            update_option( 'semplisio-workflows', $dati );

            if($res && strtoupper($res->status)=='OK'){
                $msg = Init::_tne('Salvataggio effettuato!!','semplisio');
            } else {
                $msg = Init::_tne('Si &egrave; verificato un errore durante il salvataggio!!','semplisio');
            }
        } 
        
        include Init::$PLUGIN_DIR.'partials/admin/workflows.php';
    }
}