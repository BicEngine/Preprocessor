<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Expression\Ast\Value;

use Phplrt\Contracts\Lexer\TokenInterface;
use Bic\Preprocessor\Expression\Ast\Expression;

/**
 * @internal
 */
abstract class Value extends Expression
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return string
     */
    protected static function parse(string $value)
    {
        return $value;
    }

    /**
     * @param TokenInterface $token
     * @return static
     */
    public static function fromToken(TokenInterface $token): self
    {
        return new static(static::parse($token->getValue()));
    }

    /**
     * @return mixed
     */
    public function eval()
    {
        return $this->value;
    }
}
