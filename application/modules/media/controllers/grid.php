<?php


/**
 * Grid of Options
 */
namespace Application;

return
    /**
     * @privilege Management
     * @return \closure
     */
    function () use ($view) {
        /**
         * @var \Bluz\Application $this
         * @var \Bluz\View\View $view
         */
        $this->getLayout()->setTemplate('dashboard.phtml');
        $this->getLayout()->breadCrumbs(
            [
                $view->ahref('Dashboard', ['dashboard', 'index']),
                __('Media')
            ]
        );
        $grid = new Media\Grid();



        $countCol = $this->getRequest()->getParam('countCol');

        if(isset($countCol)){
            $countCol = $countCol;
            setcookie("countCol", $countCol, time()+3600, '/');
        }else{
            if (isset($_COOKIE["countCol"])){
                $countCol = $_COOKIE["countCol"];
            }else{
                $countCol = 4;
            }
        }

        $lnCol = (integer)(12/$countCol);

        $view->countCol = $countCol;
        $view->col = $lnCol;
        $view->grid = $grid;
    };

