<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Invocations;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Invocations\BaseArgumentInvocation;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Builder\Process\ValueBuilder;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Reflection\Exceptions\TypeNotFoundException;

/**
 * Class ArgumentInvocationBuilder
 */
class ArgumentInvocationBuilder extends BaseArgumentInvocation implements Compilable
{
    use Compiler;

    /**
     * ArgumentBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param DirectiveInvocation $parent
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, DirectiveInvocation $parent)
    {
        $this->parent = $parent;
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Value') {
            $this->value = ValueBuilder::parse($ast->getChild(0));
        }

        return false;
    }

    /**
     * @return ArgumentDefinition
     * @throws TypeNotFoundException
     */
    public function getDefinition(): ArgumentDefinition
    {
        $argument = $this->parent->getDefinition()->getArgument($this->getName());

        if ($argument === null) {
            $error = 'Argument %s was specified at the @%s calling, but it absent in the directive itself.';
            throw new TypeNotFoundException(\sprintf($error, $this->getName(), $this->getParent()->getName()));
        }

        return $argument;
    }
}
