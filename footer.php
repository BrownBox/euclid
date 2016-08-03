<?php
bb_theme::section('name=panels-bottom&file=panels-bottom.php&inner_class=row-full');
bb_theme::section('name=footer&file=footer.php&inner_class=row&type=footer');
?>
				<!-- content goes here -->
				</section>
			</div>
		</div><!-- end everything -->
		<?php wp_footer(); ?>
        <script>
            var zurb = jQuery.noConflict();
            zurb(document).foundation();
        </script>
        <script>
        	var $buoop = {c:2};
        	function $buo_f() {
            	var e = document.createElement("script");
            	e.src = "//browser-update.org/update.js";
            	document.body.appendChild(e);
        	}
        	try {
            	document.addEventListener("DOMContentLoaded", $buo_f, false);
        	} catch(e) {
            	window.attachEvent("onload", $buo_f);
        	}
        </script>
<?php
// Include TypeKit font if configured (under Appearance -> Fonts)
$theme_fonts = get_option('theme_fonts');
if (!empty($theme_fonts[ns_.'typekit'])) {
    echo '<script src="https://use.typekit.net/'.$theme_fonts[ns_.'typekit'].'.js"></script><script>try{Typekit.load({ async: true });}catch(e){}</script>'."\n";
}
?>
	</body>
</html>