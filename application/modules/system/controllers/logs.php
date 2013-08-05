<?php
/**
 * @author   Petr Marchenko
 * @created  31.07.13 09:20
 */
namespace Application;

use Bluz;

return
/**
 * @privilege Info
 *
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */

    /**
     * Optional parameters.
     * Set the filtering period.     *
     */
    $startData = strtotime('2013-01-09'); //1373241600;
    $finishData = strtotime('2013-09-10'); //1373241600;

    /**
     * function to filter the files.
     * @param $var
     * @return bool
     */
    $filterByDate = function ($var) use ($startData, $finishData) {
        if(is_file(PATH_DATA . '/logs/' . $var) and fnmatch("*.log", $var)){
            $data = strtotime(substr($var, 0, -4));
            if (!(empty($fromData) and empty($toData))){
                if(($data >= $fromData) and ($data <= $toData)){
                    return true;
                }
            }else{
                return true;
            }
        }
    };

    $dir    = PATH_DATA .'/logs';
    $files = scandir($dir, 1);
    $files = array_filter(
        $files,
        $filterByDate
    );

    $view->files = $files;

};
