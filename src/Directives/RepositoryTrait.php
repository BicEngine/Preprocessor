<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Directives;

use Bic\Preprocessor\Directives\Directive\DirectiveInterface;
use Bic\Preprocessor\Directives\Directive\FunctionDirective;
use Bic\Preprocessor\Directives\Directive\ObjectLikeDirective;

/**
 * @mixin RepositoryProviderInterface
 * @internal
 */
trait RepositoryTrait
{
    /**
     * @var array|callable[]
     */
    protected array $defines = [];

    /**
     * {@inheritDoc}
     * @throws \ReflectionException
     */
    public function define(string $directive, $value = ''): void
    {
        $this->defines[$directive] = $this->cast($value);
    }

    /**
     * @param string|callable|DirectiveInterface $define
     * @return DirectiveInterface
     * @throws \ReflectionException
     */
    private function cast($define): DirectiveInterface
    {
        switch (true) {
            case $define instanceof DirectiveInterface:
                return $define;

            case \is_callable($define):
                return new FunctionDirective($define);

            case \is_scalar($define) || $define instanceof \Stringable:
                return new ObjectLikeDirective((string)$define);

            default:
                return $define;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function undef(string $directive): void
    {
        unset($this->defines[$directive]);
    }

    /**
     * {@inheritDoc}
     */
    public function defined(string $directive): bool
    {
        return isset($this->defines[$directive]) ||
            \array_key_exists($directive, $this->defines)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->defines);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->defines);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->defines;
    }
}
