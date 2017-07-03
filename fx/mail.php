<?php
add_action('phpmailer_init', 'bb_set_mail_envelope_id');
function bb_set_mail_envelope_id($phpmailer) {
    $phpmailer->Sender = $phpmailer->From;
}
