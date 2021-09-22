<?php

/*
 * This file is part of PHP CS Fixer: custom fixers.
 *
 * (c) 2018 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpCsFixerCustomFixers\Fixer;

use PhpCsFixer\DocBlock\DocBlock;
use PhpCsFixer\DocBlock\Line;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class PhpdocArrayStyleFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Generic array style should be used in PHPDoc.',
            [
                new CodeSample(
                    '<?php
/**
 * @return int[]
 */
 function foo() { return [1, 2]; }
'
                ),
            ]
        );
    }

    /**
     * Must run before PhpdocAlignFixer.
     */
    public function getPriority(): int
    {
        return 0;
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(\T_DOC_COMMENT);
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function fix(\SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = $tokens->count() - 1; $index > 0; $index--) {
            /** @var Token $token */
            $token = $tokens[$index];

            if (!$token->isGivenKind([\T_DOC_COMMENT])) {
                continue;
            }

            $docBlock = new DocBlock($token->getContent());

            foreach ($docBlock->getAnnotations() as $annotation) {
                if (!$annotation->supportTypes()) {
                    continue;
                }

                /** @var Line $line */
                $line = $docBlock->getLine($annotation->getStart());

                $content = $line->getContent();
                $newContent = $this->fixType($content);

                /** @var Line $line */
                $line = $docBlock->getLine($annotation->getStart());
                $line->setContent($newContent);
            }

            $newContent = $docBlock->getContent();
            if ($newContent === $token->getContent()) {
                continue;
            }

            $tokens[$index] = new Token([\T_DOC_COMMENT, $newContent]);
        }
    }

    private function fixType(string $type): string
    {
        do {
            /** @var string $type */
            $type = Preg::replace('/([\\\\a-zA-Z0-9>]+)\[\]/', 'array<$1>', $type, -1, $count);
        } while ($count > 0);

        return $type;
    }
}