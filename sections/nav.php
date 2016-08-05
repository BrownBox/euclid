<nav class="title-bar hide-for-medium">
<?php
$small_logo = bb_get_theme_mod(ns_.'logo_small');
?>
    <a href="#" class="float-left" type="button" data-open="offCanvasLeft"><i class="fa fa-bars" aria-hidden="true"></i></a>
    <div class="title-bar-title"><a href="<?php echo site_url(); ?>"><img class="logo" id="small-logo" src="<?php echo $small_logo; ?>" alt=""></a></div>
</nav>
<nav class="top-bar show-for-medium" id="top_menu">
    <section class="top-bar-left">
<?php
$logo = bb_get_theme_mod(ns_.'logo_large');
?>
        <a href="/"><img id="logo" src="<?php echo $logo; ?>" alt=""></a>
    </section>
    <section class="top-bar-right">
        <ul class="menu">
<?php bb_menu('main'); ?>
        </ul>
    </section>
</nav>
