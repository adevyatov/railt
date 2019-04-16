<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Definitions;

use Railt\Component\SDL\Base\Dependent\Field\BaseFieldsContainer;
use Railt\Component\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Component\SDL\Contracts\Definitions\InterfaceDefinition;
use Railt\Component\SDL\Contracts\Type;

/**
 * Class BaseInterface
 */
abstract class BaseInterface extends BaseTypeDefinition implements InterfaceDefinition
{
    use BaseFieldsContainer;
    use BaseDirectivesContainer;

    /**
     * Base type name
     */
    protected const TYPE_NAME = Type::INTERFACE;

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // trait HasFields
            'fields',

            // trait HasDirectives
            'directives',
        ]);
    }
}