<?php
function message_periode_admin_menu() {
    add_menu_page(
        'Configuration Message Période',
        'Message Période',
        'manage_options',
        'message-periode',
        'message_periode_admin_page'
    );
}
add_action('admin_menu', 'message_periode_admin_menu');

function message_periode_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'message_periode';

    if (isset($_POST['save_period'])) {
        $id             = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $title          = sanitize_text_field($_POST['title']);
        $debut          = sanitize_text_field($_POST['debut']);
        $fin            = sanitize_text_field($_POST['fin']);
        $message        = wp_kses_post($_POST['message']);
        $background_color = sanitize_hex_color($_POST['background_color']);
        $text_color     = sanitize_hex_color($_POST['text_color']);
        $font_size      = sanitize_text_field($_POST['font_size']);
        $custom_class   = sanitize_text_field($_POST['custom_class']);

        $overlap_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE debut <= %s AND fin >= %s AND id != %d",
            $fin, $debut, $id
        ));

        if ($overlap_check > 0) {
            echo '<div class="notice notice-error"><p>Erreur : La période chevauche une autre période existante.</p></div>';
        } else {
            $data = [
                'title'            => $title,
                'debut'            => $debut,
                'fin'              => $fin,
                'message'          => $message,
                'background_color' => $background_color,
                'text_color'       => $text_color,
                'font_size'        => $font_size,
                'custom_class'     => $custom_class,
            ];

            if ($id) {
                $wpdb->update($table_name, $data, ['id' => $id]);
            } else {
                $wpdb->insert($table_name, $data);
            }
            echo '<div class="notice notice-success"><p>Période enregistrée avec succès.</p></div>';
        }
    }

    if (isset($_POST['delete_period'])) {
        $wpdb->delete($table_name, ['id' => intval($_POST['id'])]);
    }

    $periods = $wpdb->get_results("SELECT * FROM $table_name ORDER BY debut ASC");
    ?>
    <div class="wrap">
        <h1>Configuration des Périodes</h1>
        <p><strong>Shortcode :</strong> <code>[message_periode]</code></p>
        <form method="POST">
            <input type="hidden" name="id" id="period_id">
            <p><label>Titre :</label><input type="text" name="title" id="title" class="regular-text"></p>
            <p><label>Date de début :</label><input type="date" name="debut" id="debut" required></p>
            <p><label>Date de fin :</label><input type="date" name="fin" id="fin" required></p>
            <p><label>Message :</label><textarea name="message" id="message" rows="4" class="large-text"></textarea></p>
            <p><label>Couleur de fond (RGBA) :</label>
                <input type="text" name="background_color" id="background_color" placeholder="rgba(255, 255, 255, 0.8)">
             </p>
             <p><label>Couleur du texte :</label>
                <input type="color" name="text_color" id="text_color" value="">
             </p>
             <p><label>Taille de la police (px) :</label>
                <input type="number" name="font_size" id="font_size" min="10" max="50">
             </p>
             <p><label>Épaisseur de la bordure (px) :</label>
                <input type="number" name="border_width" id="border_width" min="0" max="10">
             </p>
             <p><label>Couleur de la bordure :</label>
                <input type="color" name="border_color" id="border_color" value="">
             </p>
             <p><label>Radius de la bordure (px) :</label>
                <input type="number" name="border_radius" id="border_radius" min="0" max="50">
             </p>
             <p><label>Classe CSS personnalisée :</label>
                <input type="text" name="custom_class" id="custom_class" value="" class="regular-text">
             </p>
            <p><input type="submit" name="save_period" class="button button-primary" value="Enregistrer"></p>
        </form>

        <h2>Périodes existantes</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($periods as $period) : ?>
                    <tr>
                        <td><?php echo esc_html($period->title); ?></td>
                        <td><?php echo esc_html($period->debut); ?></td>
                        <td><?php echo esc_html($period->fin); ?></td>
                        <td>
                            <button class="button edit-period" data-id="<?php echo $period->id; ?>"
                                data-title="<?php echo esc_attr($period->title); ?>"
                                data-debut="<?php echo esc_attr($period->debut); ?>"
                                data-fin="<?php echo esc_attr($period->fin); ?>"
                                data-message="<?php echo esc_textarea($period->message); ?>"
                                data-background_color="<?php echo esc_attr($period->background_color); ?>"
                                data-text_color="<?php echo esc_attr($period->text_color); ?>"
                                data-font_size="<?php echo esc_attr($period->font_size); ?>"
                                data-custom_class="<?php echo esc_attr($period->custom_class); ?>">Modifier</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $period->id; ?>">
                                <input type="submit" name="delete_period" class="button button-secondary" value="Supprimer">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        (function($) {
            const today = new Date().toISOString().split('T')[0];
            $('#debut').attr('min', today);
            $('#fin').attr('min', today);

            $('#debut, #fin').on('change', function() {
                const startDate = $('#debut').val();
                const endDate = $('#fin').val();

                if (startDate && endDate && startDate > endDate) {
                    alert('Erreur : La date de début ne peut pas être après la date de fin.');
                    $('#fin').val('');
                }
            });

            $('.edit-period').on('click', function() {
                $('#period_id').val($(this).data('id'));
                $('#title').val($(this).data('title'));
                $('#debut').val($(this).data('debut'));
                $('#fin').val($(this).data('fin'));
                $('#message').val($(this).data('message'));
                $('#background_color').val($(this).data('background_color'));
                $('#text_color').val($(this).data('text_color'));
                $('#font_size').val($(this).data('font_size'));
                $('#custom_class').val($(this).data('custom_class'));
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })(jQuery);
    </script>

<?php }
