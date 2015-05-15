<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 05.05.15
 * Time: 17:30
 */

namespace Application;

use Application\Users;

return
    /**
     * @return \closure
     */
    function () {

        \Hybrid_Endpoint::process();
    };
