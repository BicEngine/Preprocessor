<?php

/**
 * This file is part of Preprocessor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Preprocessor\Runtime;

use Phplrt\Contracts\Lexer\LexerInterface;
use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Lexer\Lexer as Runtime;
use Phplrt\Lexer\Token\Token;

/**
 * @internal
 */
final class Lexer implements LexerInterface
{
    /**
     * @var string
     */
    public const T_BLOCK_COMMENT = 'T_BLOCK_COMMENT';

    /**
     * @var string
     */
    public const T_COMMENT = 'T_COMMENT';

    /**
     * @var string
     */
    public const T_QUOTED_INCLUDE = 'T_QUOTED_INCLUDE';

    /**
     * @var string
     */
    public const T_ANGLE_BRACKET_INCLUDE = 'T_ANGLE_BRACKET_INCLUDE';

    /**
     * @var string
     */
    public const T_FUNCTION_MACRO = 'T_FUNCTION_MACRO';

    /**
     * @var string
     */
    public const T_OBJECT_MACRO = 'T_OBJECT_MACRO';

    /**
     * @var string
     */
    public const T_UNDEF = 'T_UNDEF';

    /**
     * @var string
     */
    public const T_IFDEF = 'T_IFDEF';

    /**
     * @var string
     */
    public const T_IFNDEF = 'T_IFNDEF';

    /**
     * @var string
     */
    public const T_ENDIF = 'T_ENDIF';

    /**
     * @var string
     */
    public const T_IF = 'T_IF';

    /**
     * @var string
     */
    public const T_ELSE_IF = 'T_ELSE_IF';

    /**
     * @var string
     */
    public const T_ELSE = 'T_ELSE';

    /**
     * @var string
     */
    public const T_ERROR = 'T_ERROR';

    /**
     * @var string
     */
    public const T_WARNING = 'T_WARNING';

    /**
     * @var string
     */
    public const T_SOURCE = 'T_SOURCE';

    /**
     * @var string[]
     */
    private const LEXEMES = [
        self::T_BLOCK_COMMENT         => '\h*/\\*.*?\\*/',
        self::T_COMMENT               => '\\h*//[^\\n]*(?=\\n)',
        self::T_QUOTED_INCLUDE        => '^\\h*#\\h*include\\h+"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"',
        self::T_ANGLE_BRACKET_INCLUDE => '^\\h*#\\h*include\\h+<\\h*([^\\n]+)\\h*>',
        self::T_FUNCTION_MACRO        => '^\\h*#\\h*define\\h+(\\w+)\\(([^\\n]+)\\)\\h*((?:\\\\s|\\\\\\n|[^\\n])+)?$',
        self::T_OBJECT_MACRO          => '^\\h*#\\h*define\\h+(\\w+)\\h*((?:\\\\s|\\\\\\n|[^\\n])+)?$',
        self::T_UNDEF                 => '^\\h*#\\h*undef\\h+(\\w+)$',
        self::T_IFDEF                 => '^\\h*#\\h*ifdef\\b\\h*((?:\\\\s|\\\\\\n|[^\\n])+)',
        self::T_IFNDEF                => '^\\h*#\\h*ifndef\\b\\h*((?:\\\\s|\\\\\\n|[^\\n])+)',
        self::T_ENDIF                 => '^\\h*#\\h*endif\\b\\h*',
        self::T_IF                    => '^\\h*#\\h*if\\b\\h*((?:\\\\s|\\\\\\n|[^\\n])+)',
        self::T_ELSE_IF               => '^\\h*#\\h*elif\\b\\h*((?:\\\\s|\\\\\\n|[^\\n])+)',
        self::T_ELSE                  => '^\\h*#\\h*else',
        self::T_ERROR                 => '^\\h*#\\h*error\\h+((?:\\\\s|\\\\\\n|[^\\n])+)',
        self::T_WARNING               => '^\\h*#\\h*warning\\h+((?:\\\\s|\\\\\\n|[^\\n])+)',
        self::T_SOURCE                => '[^\\n]+|\\n+',
    ];

    /**
     * @var string[]
     */
    private const MERGE = [
        self::T_SOURCE,
        self::T_COMMENT,
    ];

    /**
     * @var LexerInterface
     */
    private LexerInterface $runtime;

    /**
     * Lexer constructor.
     */
    public function __construct()
    {
        $this->runtime = new Runtime(self::LEXEMES, [
            Token::END_OF_INPUT,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function lex($source, int $offset = 0): iterable
    {
        $stream = $this->runtime->lex($source, $offset);
        $previous = null;

        foreach ($stream as $token) {
            // Should be merged
            foreach (self::MERGE as $merge) {
                if ($update = $this->merge($merge, $previous, $token)) {
                    $previous = $update;
                    continue 2;
                }
            }

            if ($previous) {
                yield $previous;
            }

            $previous = $token;
        }

        if ($previous) {
            yield $previous;
        }
    }

    /**
     * @param string $name
     * @param TokenInterface|null $prev
     * @param TokenInterface $current
     * @return TokenInterface|null
     */
    private function merge(string $name, ?TokenInterface $prev, TokenInterface $current): ?TokenInterface
    {
        if ($prev && $prev->getName() === $name && $current->getName() === $name) {
            $body = $prev->getValue() . $current->getValue();

            return new Token($name, $body, $prev->getOffset());
        }

        return null;
    }
}
