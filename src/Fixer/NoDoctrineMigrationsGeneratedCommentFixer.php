<?php

declare(strict_types = 1);

namespace PhpCsFixerCustomFixers\Fixer;

use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerCustomFixers\CommentRemover;

final class NoDoctrineMigrationsGeneratedCommentFixer extends AbstractFixer
{
    public function getDefinition() : FixerDefinition
    {
        return new FixerDefinition(
            'There must be no comment generated by Doctrine Migrations.',
            [new CodeSample('<?php
namespace Migrations;
use Doctrine\DBAL\Schema\Schema;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180609123456 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE t1 SET col1 = col1 + 1");
    }
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE t1 SET col1 = col1 - 1");
    }
}
')]
        );
    }

    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound([T_COMMENT, T_DOC_COMMENT]);
    }

    public function isRisky() : bool
    {
        return false;
    }

    public function fix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind([T_COMMENT, T_DOC_COMMENT])) {
                continue;
            }

            if (\strpos($token->getContent(), 'Auto-generated Migration: Please modify to your needs!') === false
                && \strpos($token->getContent(), 'this up() migration is auto-generated, please modify it to your needs') === false
                && \strpos($token->getContent(), 'this down() migration is auto-generated, please modify it to your needs') === false
            ) {
                continue;
            }

            CommentRemover::removeCommentWithLinesIfPossible($tokens, $index);
        }
    }

    public function getPriority() : int
    {
        return 0;
    }
}