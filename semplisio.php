<?php
/**
* @package Semplisio
*/
/**
*Plugin Name: Semplisio
*Plugin URI: https://www.wordpress.org/plugins/semplisio
*Description: <p>Collega automaticamente Woocommerce al tuo gestionale con *Semplisio</p>
*Version: 1.0
*Author: HQuadro S.r.l.
*Author URI:  https://www.hquadro.it
*License: GPL v2 or later
*License URI: https://www.gnu.org/licenses/gpl-2.0.html
*Text Domain: semplisio
*/

/*
Semplisio is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Semplisio is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Semplisio. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

defined('ABSPATH')  or die;

if(file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}




\Semplisio\Init::$PLUGIN = plugin_basename( __FILE__ );
\Semplisio\Init::$PLUGIN_DIR = plugin_dir_path( __FILE__ );
\Semplisio\Init::$PLUGIN_URL = plugin_dir_url( __FILE__ );
\Semplisio\Init::$STATIC_URL = plugin_dir_url( __FILE__ ) . "/external/";

register_activation_hook(__FILE__, array('\Semplisio\Init', 'register'));
register_deactivation_hook(__FILE__, array('\Semplisio\Init', 'unregister'));

\Semplisio\Init::register();
?>
