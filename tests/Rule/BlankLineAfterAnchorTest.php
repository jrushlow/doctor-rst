<?php

declare(strict_types=1);

/*
 * This file is part of DOCtor-RST.
 *
 * (c) Oskar Stark <oskarstark@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Rule;

use App\Rule\BlankLineAfterAnchor;
use App\Tests\RstSample;
use App\Value\NullViolation;
use App\Value\ViolationInterface;

final class BlankLineAfterAnchorTest extends \App\Tests\UnitTestCase
{
    /**
     * @test
     *
     * @dataProvider checkProvider
     */
    public function check(ViolationInterface $expected, RstSample $sample): void
    {
        static::assertEquals(
            $expected,
            (new BlankLineAfterAnchor())->check($sample->lines(), $sample->lineNumber(), 'filename')
        );
    }

    /**
     * @return \Generator<array{0: ViolationInterface, 1: RstSample}>
     */
    public function checkProvider(): \Generator
    {
        yield [
            NullViolation::create(),
            new RstSample('temp'),
        ];

        yield [
            NullViolation::create(),
            new RstSample([
                '',
                '.. _env-var-processors:',
                '',
                'Environment Variable Processors',
                '===============================',
                '',
            ], 1),
        ];

        yield [
            NullViolation::create(),
            new RstSample([
                '.. _env-var-processors:',
                '',
                'Environment Variable Processors',
                '===============================',
                '',
            ]),
        ];

        yield [
            NullViolation::create(),
            new RstSample([
                '',
                '.. _env-var-processors:',
                '.. _`special-env-var-processors`:',
                '',
                'Environment Variable Processors',
                '===============================',
                '',
            ], 1),
        ];

        yield [
            NullViolation::create(),
            new RstSample([
                '',
                '.. _env-var-processors:',
                '.. _`special-env-var-processors`:',
                '.. _`super-special-env-var-processors`:',
                '',
                'Environment Variable Processors',
                '===============================',
                '',
            ], 1),
        ];

        yield [
            NullViolation::create(),
            new RstSample([
                '.. index::',
                '    single: Deployment; Deployment tools',
                '',
                '.. _how-to-deploy-a-symfony2-application:',
                '',
                'How to Deploy a Symfony Application',
                '===================================',
                '',
            ], 3),
        ];

        yield [
            NullViolation::create(),
            new RstSample([
                '.. index::',
                '    single: Deployment; Deployment tools',
                '',
                '.. _`how-to-deploy-a-symfony2-application`:',
                '',
                'How to Deploy a Symfony Application',
                '===================================',
                '',
            ], 3),
        ];
    }
}
