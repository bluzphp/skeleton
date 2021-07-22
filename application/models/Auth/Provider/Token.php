<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth\Provider;

use Application\Auth\Table;

/**
 * Token Provider
 *
 * @package  Application\Auth\Provider
 */
class Token extends AbstractToken
{
    public const PROVIDER = Table::PROVIDER_TOKEN;
}
