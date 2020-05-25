<?php declare(strict_types=1);

namespace Mathias\ParserCombinators;

use Mathias\ParserCombinators\Infra\Parser;
use Mathias\ParserCombinators\Infra\ParseResult;

/**
 * Parse something, strip it from the remaining string, but do not return anything
 */
function ignore(Parser $parser): Parser
{
    return $parser->ignore();
}

/**
 * Optionally parse something, but still succeed if the thing is not there
 */
function optional(Parser $parser): Parser
{
    return $parser->optional();
}

/**
 * Parse something, then follow by something else.
 */
function seq(Parser $first, Parser $second): Parser
{
   return $first->seq($second);
}

/**
 * Either parse the first thing or the second thing
 */
function either(Parser $first, Parser $second): Parser
{
    return $first->or($second);
}

function collect(Parser $first, Parser $second) : Parser {
    // @TODO ignoring failures for now
    return parser(function (string $input) use ($first, $second) : ParseResult {
            $r1 = $first($input);
            $r2 = $second($r1->remaining());
            return succeed([$r1->parsed(), $r2->parsed()], $r2->remaining());
    });
}

/**
 * Transform the parsed string into something else using a callable.
 */
function into1(Parser $parser, callable $transform): Parser
{
    return $parser->into1($transform);
}

/**
 * Transform the parsed string into an object of type $className
 */
function intoNew1(Parser $parser, string $className): Parser
{
    return $parser->intoNew1($className);
}
