<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Compiler;

use Railgun\Reflection\Abstraction\CalleeDirectiveInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Reflection\Abstraction\ScalarTypeInterface;
use Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Railgun\Reflection\Common\HasDefinitions;

/**
 * Class Stdlib
 * @package Railgun\Compiler
 */
final class Stdlib implements DocumentTypeInterface
{
    use HasDefinitions;

    /**
     * GraphQLStandard constructor.
     * @param Dictionary $dictionary
     * @throws \Railgun\Exceptions\SemanticException
     */
    public function __construct(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary
            ->register($this->scalar('ID'))
            ->register($this->scalar('Int'))
            ->register($this->scalar('Float'))
            ->register($this->scalar('String'))
            ->register($this->scalar('Boolean'))
            ->register($this->scalar('Any'));
    }

    /**
     * @param string $name
     * @return ScalarTypeInterface
     */
    private function scalar(string $name): ScalarTypeInterface
    {
        return new Stdlib\Scalar($this, $name);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'GraphQL Standard';
    }

    /**
     * @return DocumentTypeInterface
     */
    public function getDocument(): DocumentTypeInterface
    {
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFileName(): string
    {
        return 'GraphQL::StdLib';
    }

    /**
     * @return null|SchemaTypeInterface
     */
    public function getSchema(): ?SchemaTypeInterface
    {
        return null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return 0;
    }

    /**
     * @return bool
     */
    public function isStdlib(): bool
    {
        return true;
    }
}
