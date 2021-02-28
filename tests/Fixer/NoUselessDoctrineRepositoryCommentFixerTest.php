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

namespace Tests\Fixer;

/**
 * @internal
 *
 * @covers \PhpCsFixerCustomFixers\Fixer\NoUselessDoctrineRepositoryCommentFixer
 */
final class NoUselessDoctrineRepositoryCommentFixerTest extends AbstractFixerTestCase
{
    public function testIsRisky(): void
    {
        self::assertFalse($this->fixer->isRisky());
    }

    /**
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<array{0: string, 1?: string}>
     */
    public static function provideFixCases(): iterable
    {
        yield [
            '<?php
class FooRepository extends EntityRepository {}
',
            '<?php
/**
 * FooRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FooRepository extends EntityRepository {}
',
        ];

        yield [
            '<?php
/**
 * FooRepository
 *
 * This class was not generated by the Doctrine ORM.
 */
class FooRepository extends EntityRepository {}
',
        ];

        yield [
            '<?php
class FooRepository extends EntityRepository {
    /**
     * @return array
     */
     public function foo() {}
}
',
            '<?php
/**
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FooRepository extends EntityRepository {
    /**
     * @return array
     */
     public function foo() {}
}
',
        ];
    }
}
