<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Expression\Ast\Comparison;

final class LessThan extends Comparison
{
    /**
     * @return bool
     */
    public function eval(): bool
    {
        return $this->a->eval() < $this->b->eval();
    }
}
