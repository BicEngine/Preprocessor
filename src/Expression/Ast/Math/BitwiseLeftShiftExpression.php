<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Expression\Ast\Math;

use Bic\Preprocessor\Expression\Ast\BinaryExpression;

class BitwiseLeftShiftExpression extends BinaryExpression
{
    /**
     * @return int
     */
    public function eval(): int
    {
        return $this->a->eval() << $this->b->eval();
    }
}
