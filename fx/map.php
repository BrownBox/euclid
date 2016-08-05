<?php
function bb_map($id, $longitude, $latitude, $width='500px', $height='500px', $class="map", $zoom = 14) {
    $map  = '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDtQ66C6jNfGLN8eaQmMzoIauCODxVYTr0"></script>'."\n";
    $map .= '<script type="text/javascript">'."\n";
    $map .= '    function map_'.$id.'() {'."\n";
    $map .= "        var map_canvas = document.getElementById('$id');"."\n";
    $map .= '        var map_options = {'."\n";
    $map .= '            center: new google.maps.LatLng('.$longitude.', '.$latitude.'),'."\n";
    $map .= '            zoom: '.$zoom.','."\n";
    $map .= '            mapTypeId: google.maps.MapTypeId.ROADMAP'."\n";
    $map .= '        }'."\n";
    $map .= '        var map = new google.maps.Map(map_canvas, map_options)'."\n";
    $map .= '        var marker = new google.maps.Marker({'."\n";
    $map .= '            position: new google.maps.LatLng('.$longitude.', '.$latitude.'),'."\n";
    $map .= '            map: map'."\n";
    $map .= '        });'."\n";
    $map .= '    }'."\n";
    $map .= '    google.maps.event.addDomListener(window, "load", map_'.$id.');'."\n";
    $map .= '</script>'."\n";
    $map .= '<style>'."\n";
    $map .= '#'.$id.', #'.$id.'_overlay {'."\n";
    $map .= '    width: '.$width.';'."\n";
    $map .= '    height: '.$height.';'."\n";
    $map .= '}'."\n";
    $map .= '#'.$id.'_overlay {'."\n";
    $map .= '    top: '.$height.';'."\n";
    $map .= '    margin-top: -'.$height.';'."\n";
    $map .= '}'."\n";
    $map .= '</style>'."\n";
    $map .= '<div id="'.$id.'_overlay" class="notouch" onclick="style.pointerEvents=\'none\';"></div>'."\n";
    $map .= '<div id="'.$id.'" class="'.$class.'"></div>'."\n";

    echo $map;
}
