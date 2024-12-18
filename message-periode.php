<?php
/**
 * Plugin Name: Scheduler Messages
 * Description: Affiche des messages dynamiques entre deux dates avec une page de configuration.
 * Version: 1.2
 * Author: Yannick Perret
 * License: Apache License 2.0
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';

function message_periode_activate() {
    create_message_periode_table();
}
register_activation_hook(__FILE__, 'message_periode_activate');

function message_periode_enqueue_styles() {
    wp_enqueue_style('message-periode-style', plugin_dir_url(__FILE__) . 'assets/style.css');
}
add_action('wp_enqueue_scripts', 'message_periode_enqueue_styles');
