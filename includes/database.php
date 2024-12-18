<?php
function create_message_periode_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'message_periode';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NULL,
        debut DATE NOT NULL,
        fin DATE NOT NULL,
        message TEXT NOT NULL,
       background_color VARCHAR(20) NULL,
       text_color VARCHAR(7) NULL,
       font_size VARCHAR(10) NULL,
       border_width VARCHAR(5) NULL,
       border_color VARCHAR(7) NULL,
       border_radius VARCHAR(5) NULL,
       custom_class VARCHAR(255) NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

