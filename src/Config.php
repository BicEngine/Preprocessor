<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor;

final class Config
{
    /**
     * @var int
     */
    public const KEEP_COMMENTS = 0x01;

    /**
     * @var int
     */
    public const KEEP_EXTRA_LINE_FEEDS = 0x02;
}
