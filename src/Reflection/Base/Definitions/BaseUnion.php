<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Definitions;

use Railt\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\UnionDefinition;
use Railt\Reflection\Contracts\Type;

/**
 * Class BaseUnion
 */
abstract class BaseUnion extends BaseTypeDefinition implements UnionDefinition
{
    use BaseDirectivesContainer;

    /**
     * Type definition
     */
    protected const TYPE_NAME = Type::UNION;

    /**
     * @var array|Definition[]
     */
    protected $types = [];

    /**
     * @return iterable|Definition[]
     */
    public function getTypes(): iterable
    {
        return \array_values($this->types);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return \array_key_exists($name, $this->types);
    }

    /**
     * @param string $name
     * @return null|Definition
     */
    public function getType(string $name): ?Definition
    {
        return $this->types[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfTypes(): int
    {
        return \count($this->types);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'types',
        ]);
    }
}
