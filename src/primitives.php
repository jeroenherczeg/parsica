<?php declare(strict_types=1);

namespace Mathias\ParserCombinator;

use Mathias\ParserCombinator\ParseResult\ParseResult;
use function Mathias\ParserCombinator\ParseResult\{fail, parser, succeed};

/**
 * A parser that satisfies a predicate. Useful as a building block for writing things like char(), digit()...
 *
 * @param callable(string) : bool $predicate
 * @param string $expected
 *
 * @return Parser<string>
 */
function satisfy(callable $predicate, string $expected = "satisfy(predicate)"): Parser
{
    return parser(function (string $input) use ($predicate, $expected) : ParseResult {
        if (mb_strlen($input) === 0) {
            return fail($expected, "EOF");
        }
        $token = mb_substr($input, 0, 1);
        return $predicate($token)
            ? succeed($token, mb_substr($input, 1))
            : fail($expected, $token);
    });
}

/**
 * Parse a single character of anything
 *
 * @return Parser<string>
 */
function anything(): Parser
{
    return satisfy(fn(string $_) => true, 'anything');
}

/**
 * Parse nothing, but still succeed.
 */
function nothing(): Parser
{
    return parser(fn(string $input) => succeed("", $input));
}

/**
 * Parse everything; that is, consume the rest of the input until the end.
 */
function everything(): Parser
{
    return parser(fn(string $input) => succeed($input, ""));
}


/**
 * Parse the end of the input
 *
 * @return Parser<string>
 */
function eof(): Parser
{
    return parser(fn(string $input): ParseResult => mb_strlen($input) === 0
        ? succeed("", "")
        : fail("eof", $input)
    );
}