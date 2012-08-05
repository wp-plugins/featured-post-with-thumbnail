<?php
/*==============================================================================
 * Definizioni costanti
 *============================================================================*/

/* definizione costanti non esistenti in worpdress < 2.6
 * http://codex.wordpress.org/Determining_Plugin_and_Content_Directories
 */
if ( !defined('WP_CONTENT_URL') ) {
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}

if ( !defined('WP_CONTENT_DIR') ) {
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}

if ( !defined('WP_PLUGIN_URL') ) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
}

if ( !defined('WP_PLUGIN_DIR') ) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}

//nome localizzazione
define('YIW_TEXT_DOMAIN', 'featured-post');

 /* Aggiunge file per la traduzione
 * add translate language
 */
$language_files_path = dirname(dirname(plugin_basename(__FILE__))) . '/language';
load_plugin_textdomain(YIW_TEXT_DOMAIN, false, $language_files_path);

/**
 * Determina il percorso del plugin
 * Determine plugin path
 */
$featured_post_plugin_path = plugins_url('/', dirname(__FILE__));

/**
 * Add thumbnail support to the theme, if wordpress version is appropriate
 */
if ( function_exists('add_theme_support') ) {
	add_theme_support('post-thumbnails');
}
?>