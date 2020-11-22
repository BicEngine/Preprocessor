<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor;

use Phplrt\Source\File;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Bic\Preprocessor\Directives\Repository as DirectivesRepository;
use Bic\Preprocessor\Directives\RepositoryProviderInterface as DirectivesRepositoryInterface;
use Bic\Preprocessor\Environment\EnvironmentInterface;
use Bic\Preprocessor\Environment\PhpEnvironment;
use Bic\Preprocessor\Environment\StandardEnvironment;
use Bic\Preprocessor\IncludeDirectories\Repository as IncludeDirectoriesRepository;
use Bic\Preprocessor\IncludeDirectories\RepositoryProviderInterface as IncludeDirectoriesRepositoryInterface;
use Bic\Preprocessor\Runtime\Executor;

class Preprocessor implements PreprocessorInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var DirectivesRepositoryInterface
     */
    private DirectivesRepositoryInterface $directives;

    /**
     * @var IncludeDirectoriesRepositoryInterface
     */
    private IncludeDirectoriesRepositoryInterface $includes;

    /**
     * @psalm-var array<array-key, class-string<EnvironmentInterface>>
     * @var array|EnvironmentInterface[]
     */
    private array $environments = [
        PhpEnvironment::class,
        StandardEnvironment::class
    ];

    /**
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->directives = new DirectivesRepository();
        $this->includes = new IncludeDirectoriesRepository();
        $this->logger = $logger ?? new NullLogger();

        $this->bootEnvironment();
    }

    /**
     * @return void
     */
    protected function bootEnvironment(): void
    {
        foreach ($this->environments as $environment) {
            $this->load(new $environment());
        }
    }

    /**
     * @param EnvironmentInterface $env
     */
    public function load(EnvironmentInterface $env): void
    {
        $env->applyTo($this);
    }

    /**
     * {@inheritDoc}
     */
    public function define(string $directive, $value = self::DEFAULT_VALUE): void
    {
        $this->directives->define($directive, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function undef(string $directive): void
    {
        $this->directives->undef($directive);
    }

    /**
     * {@inheritDoc}
     */
    public function defined(string $directive): bool
    {
        return $this->directives->defined($directive);
    }

    /**
     * {@inheritDoc}
     */
    public function include(string $directory): void
    {
        $this->includes->include($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function exclude(string $directory): void
    {
        $this->includes->exclude($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function getIncludedDirectories(): iterable
    {
        return $this->includes->getIncludedDirectories();
    }

    /**
     * {@inheritDoc}
     */
    public function add($source, string $name): void
    {
        $this->includes->add($source, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(string $file): void
    {
        $this->includes->remove($file);
    }

    /**
     * {@inheritDoc}
     */
    public function getIncludedFiles(): iterable
    {
        return $this->includes->getIncludedFiles();
    }

    /**
     * {@inheritDoc}
     */
    public function process($source, int $options = 0): ResultInterface
    {
        $includes = clone $this->includes;
        $directives = clone $this->directives;

        $context = new Executor($options, $directives, $includes);
        $context->setLogger($this->logger);

        $stream = $context->execute(File::new($source));

        return new Result($stream, $directives, $includes, $options);
    }
}
