<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Expression\Ast;

use Phplrt\Contracts\Ast\NodeInterface;

interface ExpressionInterface extends NodeInterface
{
    /**
     * @return mixed
     */
    public function eval();
}
