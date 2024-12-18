<?php
class MessagePeriodeWidget extends WP_Widget {
    public function __construct() {
        parent::__construct('message_periode_widget', __('Message Période', 'message-periode'));
    }

    public function widget($args, $instance) {
        echo do_shortcode('[message_periode]');
    }
}

function register_message_periode_widget() {
    register_widget('MessagePeriodeWidget');
}
add_action('widgets_init', 'register_message_periode_widget');
