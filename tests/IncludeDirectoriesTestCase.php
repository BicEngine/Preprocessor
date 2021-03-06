<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Tests;

use Bic\Preprocessor\IncludeDirectories\Repository;
use Bic\Preprocessor\Tests\TestCase;

class IncludeDirectoriesTestCase extends TestCase
{
    /**
     * @return void
     */
    public function testDefaultState(): void
    {
        $includes = new Repository();

        $this->assertCount(0, $includes);
        $this->assertSame([], $includes->toArray());
    }

    /**
     * @return void
     */
    public function testAddition(): void
    {
        $includes = new Repository();

        $includes->include(__DIR__);
        $this->assertCount(1, $includes);
        $this->assertSame($this->normalize([__DIR__]), $includes->toArray());
    }

    /**
     * @param string[] $directories
     * @return string[]
     */
    private function normalize(iterable $directories): array
    {
        $result = [];

        foreach ($directories as $directory) {
            $result[] = \str_replace('\\', '/', $directory);
        }

        return $result;
    }

    /**
     * @return void
     */
    public function testRemoving(): void
    {
        $includes = new Repository();

        $includes->include(__DIR__);
        $this->assertCount(1, $includes);

        $includes->exclude(__DIR__);
        $this->assertCount(0, $includes);
    }

    /**
     * @return void
     */
    public function testParentRemoving(): void
    {
        $includes = new Repository();

        // Add 2 directories
        $includes->include(__DIR__);
        $includes->include(\realpath(__DIR__ . '/../src'));
        $this->assertCount(2, $includes);

        // Remove both
        $includes->exclude(\dirname(__DIR__));
        $this->assertCount(0, $includes);
    }

    /**
     * @return void
     */
    public function testInitialization(): void
    {
        $includes = new Repository([__DIR__]);

        $this->assertCount(1, $includes);
        $this->assertSame($this->normalize([__DIR__]), $includes->toArray());
    }

    /**
     * @return void
     */
    public function testIteration(): void
    {
        $includes = new Repository([__DIR__, __DIR__ . '/../src']);

        $this->assertCount(2, $includes);
        $this->assertSame($includes->toArray(), \iterator_to_array($includes));
    }
}
