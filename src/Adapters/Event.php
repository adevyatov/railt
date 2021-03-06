<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

/**
 * Interface Event
 */
interface Event
{
    public const DISPATCHING = 'dispatching';
    public const RESOLVING   = 'resolving';
    public const RESOLVED    = 'resolved';
}
