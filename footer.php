<?php
bb_theme::section('name=panels-bottom&file=panels-bottom.php&inner_class=row-full');
bb_theme::section('name=footer&file=footer.php&inner_class=row&type=footer');
?>
        				</section>
				    </div><!-- end off-canvas-content -->
				</div><!-- end off-canvas-wrapper-inner -->
			</div><!-- end off-canvas-wrapper -->
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
// Include TypeKit font if configured (in Customizer)
$typekit_id = bb_get_theme_mod('typekit');
if (!empty($typekit_id)) {
    echo '<script src="https://use.typekit.net/'.$typekit_id.'.js"></script><script>try{Typekit.load({ async: true });}catch(e){}</script>'."\n";
}
?>
	</body>
</html>