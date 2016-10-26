<?php
/**
 * Rearrange an array into columns (e.g. for alphabetically sorting items down columns instead of across)
 * @param array $list Items to arrange
 * @param integer $cols Number of columns
 * @return array Multi-dimensional array of columns, where each column contains an array of items for that column
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

/**
 * Re-sort an array for putting into columns (e.g. for alphabetically sorting items down columns instead of across)
 * @param array $list
 * @param integer $cols
 * @return multitype:NULL
 */
function bb_sort_array_for_columns(array $list, $cols) {
    $partition = bb_array_to_columns($list, $cols);
    $sorted_list = array();
    for ($i = 0; $i < count($partition[0]); $i++) {
        for ($j = 0; $j < $cols; $j++) {
            if (isset($partition[$j][$i])) {
                $sorted_list[] = $partition[$j][$i];
            }
        }
    }

    return $sorted_list;
}
