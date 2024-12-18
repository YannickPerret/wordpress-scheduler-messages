<?php
function message_periode_shortcode($atts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'message_periode';
    $date_courante = current_time('Y-m-d');

    $periods = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE debut <= %s AND fin >= %s", $date_courante, $date_courante)
    );

    if (empty($periods)) {
        return '';
    }

    $output = '<div class="message-periodique-wrapper">';
    foreach ($periods as $period) {
        $style = '';
        $style .= !empty($period->background_color) ? 'background:' . esc_attr($period->background_color) . ';' : '';
        $style .= !empty($period->text_color) ? 'color:' . esc_attr($period->text_color) . ';' : '';
        $style .= !empty($period->font_size) ? 'font-size:' . esc_attr($period->font_size) . 'px;' : '';
        $style .= !empty($period->border_width) ? 'border-width:' . esc_attr($period->border_width) . 'px;' : '';
        $style .= !empty($period->border_color) ? 'border-color:' . esc_attr($period->border_color) . ';' : '';
        $style .= !empty($period->border_radius) ? 'border-radius:' . esc_attr($period->border_radius) . 'px;' : '';
        $style .= 'border-style: solid;'; // Style par défaut pour les bordures

        $output .= '<div class="message-periodique ' . esc_attr($period->custom_class) . '" style="' . $style . '">';
        if (!empty($period->title)) {
            $output .= '<h3>' . esc_html($period->title) . '</h3>';
        }
        $output .= wp_kses_post($period->message);
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('message_periode', 'message_periode_shortcode');

