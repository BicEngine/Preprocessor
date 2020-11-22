<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor;

use Bic\Preprocessor\Directives\RepositoryProviderInterface as DirectivesRepositoryInterface;
use Bic\Preprocessor\IncludeDirectories\RepositoryProviderInterface as IncludeDirectoriesRepositoryInterface;

interface ContextInterface
{
    /**
     * @return DirectivesRepositoryInterface
     */
    public function getDirectives(): DirectivesRepositoryInterface;

    /**
     * @return IncludeDirectoriesRepositoryInterface
     */
    public function getIncludeDirectories(): IncludeDirectoriesRepositoryInterface;
}
