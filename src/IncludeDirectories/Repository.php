<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\IncludeDirectories;

/**
 * @internal
 */
final class Repository implements RepositoryProviderInterface
{
    use RepositoryTrait;

    /**
     * @param string[] $directories
     */
    public function __construct(iterable $directories = [])
    {
        foreach ($directories as $directory) {
            $this->include($directory);
        }
    }
}
