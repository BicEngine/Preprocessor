<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Expression\Ast\Literal;

final class BinIntegerLiteral extends IntegerLiteral
{
    /**
     * @param string $value
     * @param string $suffix
     */
    public function __construct(string $value, string $suffix)
    {
        parent::__construct(\bindec($value), $suffix);
    }
}
