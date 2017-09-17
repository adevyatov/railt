<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Contracts\InterfaceTypeInterface;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\Fields;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class InterfaceDefinition
 * @package Railt\Reflection\Reflection
 */
class InterfaceDefinition extends Definition implements
    InterfaceTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Fields;
    use Directives;
    use LinkingStage;
    use HasDescription;

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        return $ast;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Interface';
    }
}