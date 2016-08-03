<?php
// ENABLE OUR CREDIT CARD FIELDS
add_action("gform_enable_credit_card_field", "bb_enable_credit_card_field");
function bb_enable_credit_card_field($is_enabled) {
    return true;
}

// ENABLE PASSWORD FIELDS
add_action("gform_enable_password_field", "bb_enable_password_field");
function bb_enable_password_field($is_enabled) {
    return true;
}
