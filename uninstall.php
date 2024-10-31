<?php

 if(! defined('WP_UNINSTALL_PLUGIN') ){
 	die;
 }

update_option( 'semplisio-token', '0' );
update_option( 'semplisio-connector', '0' );
update_option( 'semplisio-erp-connector-id', '0' );
update_option( 'semplisio-gestionale', '0' );
update_option( 'semplisio-option', '0' );
update_option( 'semplisio-wc-consumer_key', '0' );
update_option( 'semplisio-workflows', '0' );
