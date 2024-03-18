<?php

/**
 * This file is part of the package magicsunday/webtrees-pedigree-chart.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagicSunday\Webtrees\PedigreeChart\Test;

use MagicSunday\Webtrees\PedigreeChart\Processor\NameProcessor;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * MultiByteTest.
 *
 * @author  Rico Sonntag <mail@ricosonntag.de>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License v3.0
 * @link    https://github.com/magicsunday/webtrees-pedigree-chart/
 */
class MultiByteTest extends TestCase
{
    /**
     * @return string[][]
     */
    public static function convertToHtmlEntitiesDataProvider(): array
    {
        // [ input, expected ]
        return [
            // German umlauts
            [
                '<div>abc <span>äöü</span> <p>&#228;&#246;&#252;</p></div>',
                '<div>abc <span>&#228;&#246;&#252;</span> <p>&#228;&#246;&#252;</p></div>',
            ],
            [
                '<div>abc <span>&auml;&ouml;&uuml;</span> <p>&#228;&#246;&#252;</p></div>',
                '<div>abc <span>&auml;&ouml;&uuml;</span> <p>&#228;&#246;&#252;</p></div>',
            ],

            // Euro sign
            [
                '€ &euro; &#8364;',
                '&#8364; &euro; &#8364;',
            ],

            // Korean
            [
                '박성욱',
                '&#48149;&#49457;&#50865;',
            ],
            [
                '<span><span>&#48149;</span>&#49457;&#50865;</span>',
                '<span><span>&#48149;</span>&#49457;&#50865;</span>',
            ],
        ];
    }

    /**
     * Tests conversion of UTF-8 characters to HTML entities.
     *
     * @test
     *
     * @dataProvider convertToHtmlEntitiesDataProvider
     *
     * @param string $input
     * @param string $expected
     *
     * @return void
     */
    public function convertToHtmlEntities(string $input, string $expected): void
    {
        $nameProcessorMock = $this->createMock(NameProcessor::class);

        $reflection = new ReflectionClass(NameProcessor::class);
        $method     = $reflection->getMethod('convertToHtmlEntities');
        $method->setAccessible(true);

        $result = $method->invokeArgs($nameProcessorMock, [$input]);

        self::assertSame($expected, $result);
    }
}
