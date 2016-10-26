<?php
/**
 * Generate and display a Google Map
 * @param string $id ID to be used for wrapper div
 * @param float $longitude Longitude for map centre
 * @param float $latitude Latitude for map centre
 * @param array $markers Optional. List of markers in format array('lng' => $longitude, 'lat' => $latitude). If empty a single marker will be placed at map centre.
 * @param string $width Optional. Map width (default '500px').
 * @param string $height Optional. Map height (default '500px').
 * @param string $class Optional. Additional CSS class to be added to wrapper div (default 'map').
 * @param integer $zoom Optional. Zoom level (default 14).
 */
function bb_map($id, $longitude, $latitude, $markers = array(), $width='500px', $height='500px', $class="map", $zoom = 14) {
    if (empty($markers)) {
        $markers = array(
                array(
                        'lng' => $longitude,
                        'lat' => $latitude,
                )
        );
    }
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
    $i = 1;
    foreach ($markers as $marker) {
        $map .= '        var marker'.$i.' = new google.maps.Marker({'."\n";
        $map .= '            position: new google.maps.LatLng('.$marker['lng'].', '.$marker['lat'].'),'."\n";
        $map .= '            map: map'."\n";
        $map .= '        });'."\n";
        $i++;
    }
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
