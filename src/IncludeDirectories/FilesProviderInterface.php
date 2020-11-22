<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\IncludeDirectories;

use Bic\Preprocessor\PreprocessorInterface;

/**
 * @psalm-import-type SourceEntry from PreprocessorInterface
 * @link PreprocessorInterface
 */
interface FilesProviderInterface
{
    /**
     * @psalm-param SourceEntry $source
     * @param mixed $source
     * @param string $name
     */
    public function add($source, string $name): void;

    /**
     * @param string $file
     */
    public function remove(string $file): void;

    /**
     * @psalm-return iterable<string, SourceEntry>
     * @return iterable
     */
    public function getIncludedFiles(): iterable;
}
