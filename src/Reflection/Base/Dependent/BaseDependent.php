<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Dependent;

use Railt\Reflection\Base\Definitions\BaseTypeDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Class BaseDependentType
 */
abstract class BaseDependent extends BaseTypeDefinition implements DependentDefinition
{
    /**
     * @var TypeDefinition|mixed
     */
    protected $parent;

    /**
     * @return TypeDefinition
     */
    public function getParent(): TypeDefinition
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'parent',
        ]);
    }
}
