<?php
/**
 * Example of API method
 *
 * @author   Anton Shevchuk
 * @created  15.10.12 15:22
 */

/**
 * @param integer $num
 * @return \closure
 */
return function($num) {
    return $num*$num;
};