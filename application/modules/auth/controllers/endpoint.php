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
     * @param int $id User UID
     * @param string $code
     * @return \closure
     */
    function () {

        include PATH_VENDOR."/"."hybridauth/hybridauth/hybridauth/index.php";
    };
