<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Environment;

use Bic\Preprocessor\PreprocessorInterface;
use Bic\Version\Version;

final class PhpEnvironment implements EnvironmentInterface
{
    /**
     * @var string[]
     */
    private const EXPORT_DIRECTIVE_NAMES = [
        'PHP_',
        'ZEND_',
    ];

    /**
     * @param PreprocessorInterface $pre
     */
    public function applyTo(PreprocessorInterface $pre): void
    {
        foreach (self::EXPORT_DIRECTIVE_NAMES as $prefix) {
            foreach ($this->getCoreConstants() as $constant => $value) {
                $isValidType = \is_scalar($value) || $value === null;

                if (! $isValidType || ! \str_starts_with($constant, $prefix)) {
                    continue;
                }

                $this->define($pre, $constant, $value);
            }
        }

        foreach ($this->getAdditionalDefines() as $constant => $value) {
            $this->define($pre, $constant, $value);
        }
    }

    /**
     * @return array
     */
    private function getCoreConstants(): array
    {
        $constants = \get_defined_constants(true);

        return (array)($constants['Core'] ?? $constants['core'] ?? []);
    }

    /**
     * @param PreprocessorInterface $pre
     * @param string $name
     * @param mixed $value
     */
    private function define(PreprocessorInterface $pre, string $name, $value): void
    {
        $pre->define('__' . $name . '__', $this->toCLiteral($value));
    }

    /**
     * @return iterable
     */
    private function getAdditionalDefines(): iterable
    {
        yield 'PHP_VERSION' => Version::fromString(\PHP_VERSION)->toInt();
        yield 'ZEND_VERSION' => Version::fromString(\zend_version())->toInt();
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function toCLiteral($value): string
    {
        switch (true) {
            case \is_string($value) && \strlen($value) === 1:
                return "'" . \addcslashes($value, "'") . "'";

            case \is_string($value):
                return '"' . \addcslashes($value, '"') . '"';

            case \is_float($value):
                return \sprintf('%g', $value);

            case \is_int($value):
                return (string)$value;

            case \is_bool($value):
                return $value ? '1' : '0';

            case $value === null:
                return 'NULL';

            default:
                throw new \LogicException('Non-serializable C literal of PHP type ' . \get_debug_type($value));
        }
    }
}
