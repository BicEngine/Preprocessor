<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Directives\Directive;

use Bic\Preprocessor\Directives\RepositoryProviderInterface;

abstract class Directive implements DirectiveInterface
{
    /**
     * @var string
     */
    public const DEFAULT_VALUE = RepositoryProviderInterface::DEFAULT_VALUE;

    /**
     * @var string
     */
    protected const ERROR_TOO_MANY_ARGUMENTS = 'Too many arguments when macro directive is called';

    /**
     * @var string
     */
    protected const ERROR_TOO_FEW_ARGUMENTS = 'Too few arguments when macro directive is called';

    /**
     * @var int
     */
    protected int $minArgumentsCount = 0;

    /**
     * @var int
     */
    protected int $maxArgumentsCount = 0;

    /**
     * @param string $body
     * @return string
     */
    protected function normalizeBody(string $body): string
    {
        return \str_replace("\\\n", "\n", $body);
    }

    /**
     * @param array|string[] $arguments
     * @return void
     */
    protected function assertArgumentsCount(array $arguments): void
    {
        $haystack = \count($arguments);

        if ($haystack > $this->getMaxArgumentsCount()) {
            throw new \ArgumentCountError(static::ERROR_TOO_MANY_ARGUMENTS);
        }

        if ($haystack < $this->getMinArgumentsCount()) {
            throw new \ArgumentCountError(static::ERROR_TOO_FEW_ARGUMENTS);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxArgumentsCount(): int
    {
        return $this->maxArgumentsCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinArgumentsCount(): int
    {
        return $this->minArgumentsCount;
    }
}
