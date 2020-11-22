<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Expression\Ast\Value;

/**
 * @internal
 */
final class CharValue extends Value
{
    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        parent::__construct($value);
    }

    /**
     * @return int
     */
    public function eval(): int
    {
        return parent::eval();
    }

    /**
     * @param string $value
     * @return int
     */
    protected static function parse(string $value): int
    {
        $value = \substr($value, 1, -1);

        return (int)\ord($value);
    }
}
