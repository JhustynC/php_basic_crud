<?php
/*
Plugin Name: Gestión de Usuarios Personalizada
Description: Muestra una interfaz de gestión de usuarios como en tu app PHP.
Version: 2.0
Author: Jhustyn Carvajal
*/


function gestion_usuarios_shortcode() {
    ob_start();
    require_once plugin_dir_path(__FILE__) . 'src/index.php';
    return ob_get_clean();
}

add_shortcode('gestion_usuarios', 'gestion_usuarios_shortcode');
