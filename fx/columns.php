<?php
/**
 * Rearrange an array into columns (e.g. for alphabetically sorting items down columns instead of across)
 * @param array $list Items to arrange
 * @param integer $cols Number of columns
 * @return array
 */
function bb_array_to_columns(array $list, $cols) {
    $listlen = count($list);
    $partlen = floor($listlen / $cols);
    $partrem = $listlen % $cols;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $cols; $px++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice($list, $mark, $incr);
        $mark += $incr;
    }
    return $partition;
}
