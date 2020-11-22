<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor;

use Phplrt\Contracts\Source\ReadableInterface;
use Bic\Preprocessor\Directives\DirectivesProviderInterface;
use Bic\Preprocessor\Environment\EnvironmentProviderInterface;
use Bic\Preprocessor\Exception\PreprocessorException;
use Bic\Preprocessor\IncludeDirectories\DirectoriesProviderInterface;
use Bic\Preprocessor\IncludeDirectories\FilesProviderInterface;

/**
 * @psalm-type SourceEntry = string|resource|ReadableInterface|\SplFileInfo
 *
 * @link ReadableInterface
 * @link StreamInterface
 */
interface PreprocessorInterface extends
    FilesProviderInterface,
    DirectivesProviderInterface,
    DirectoriesProviderInterface,
    EnvironmentProviderInterface
{
    /**
     * @psalm-param SourceEntry $source
     *
     * @param mixed $source
     * @param int $options
     * @return ResultInterface|string[]
     * @throws PreprocessorException
     */
    public function process($source, int $options = 0): ResultInterface;
}
