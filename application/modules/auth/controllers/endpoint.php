<?php
/**
 * Auth end-point controller
 *
 * @author yuklia
 * @created  05.05.15 17:30
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
