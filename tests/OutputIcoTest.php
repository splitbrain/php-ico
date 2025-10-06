<?php
declare (strict_types=1);

namespace splitbrain\phpico\Tests;

use splitbrain\phpico\PhpIco;
use PHPUnit\Framework\TestCase;

class OutputIcoTest extends TestCase
{

    public function goodAddImageSingleProvider()
    {
        return [
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.gif',
                [],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.jpg',
                [],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.png',
                [],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.gif',
                [16,16],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.jpg',
                [16, 16],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.png',
                [16, 16],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.gif',
                [
                    [16,16],
                ],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.jpg',
                [
                    [16,16],
                ],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.png',
                [
                    [16,16],
                ],
            ],
        ];
    }

    public function badAddImageSingleProvider_invalidFile()
    {
        return [
            [
                null,
                [],
            ],
            [
                false,
                [],
            ],
            [
                true,
                [],
            ],
            [
                1,
                [],
            ],
            [
                '',
                [],
            ],
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'test-ico-1.xcf',
                [],
            ],
        ];
    }

    public function badSaveIcoProvider_GetIcoDataReturnsFalse()
    {
        return [
            [
                [],
            ],
        ];
    }

    /**
    * @dataProvider goodAddImageSingleProvider
    */
    public function testConstructorOnly($file, $sizes) {
        $this->assertTrue(is_file($file));
        $this->assertTrue(is_readable($file));
        $this->assertTrue(is_file($file . '.ico'));
        $this->assertTrue(is_readable($file . '.ico'));

        $ico = new PhpIco($file, $sizes);
        $outputToHere = tempnam(sys_get_temp_dir(), 'PHP_ICO_tests');
        $this->assertTrue($ico->saveIco($outputToHere));
        $this->assertSame(sha1_file($file . '.ico'), sha1_file($outputToHere));
        unlink($outputToHere);
    }

    /**
    * @dataProvider badAddImageSingleProvider_invalidFile
    */
    public function testAddImageBadFiles($file, $sizes) {
        $ico = new PhpIco();
        $this->assertFalse($ico->addImage($file, $sizes));
    }

    /**
    * @dataProvider badSaveIcoProvider_GetIcoDataReturnsFalse
    */
    public function testSaveIcoBadData($arrayOfFilesAndSizes)
    {
        $ico = new PhpIco();
        foreach ($arrayOfFilesAndSizes as $file => $sizes)
        {
            $ico->addImage($file, $sizes);
        }
        $outputToHere = tempnam(sys_get_temp_dir(), 'PHP_ICO_tests');
        $this->assertFalse($ico->saveIco($outputToHere));
        unlink($outputToHere);
    }
}
