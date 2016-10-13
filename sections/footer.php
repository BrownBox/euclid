<?php
$logo_footer = bb_get_theme_mod(ns_.'logo_footer');
$email = bb_get_theme_mod(ns_.'contact_email');
$phone = bb_get_theme_mod(ns_.'contact_phone');
$address = bb_get_theme_mod(ns_.'contact_address');
?>
<div class="small-24 medium-6 column hide-for-print" data-swiftype-index="false">
    <img class="logo" src="<?php echo $logo_footer; ?>" alt="">
</div>
<div class="small-24 medium-18 column">
    <h2>Contact Us</h2>
    <p><?php echo $address; ?></p>
    <p>Email: <?php echo $email; ?></p>
    <p>Phone: <?php echo $phone; ?></p>
</div>
