<?php

/*
 * This file is part of the `liip/LiipImagineBundle` project.
 *
 * (c) https://github.com/liip/LiipImagineBundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Liip\ImagineBundle\Tests\File\Metadata;

use Liip\ImagineBundle\File\Guesser\ContentTypeGuesser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 * @covers \Liip\ImagineBundle\File\Guesser\AbstractGuesser
 * @covers \Liip\ImagineBundle\File\Guesser\ContentTypeGuesser
 */
class ContentTypeGuesserTest extends TestCase
{
    public function testConstruction()
    {
        $g = new ContentTypeGuesser(MimeTypeGuesser::getInstance());
        $this->assertSame(1, $g->count());

        $g->register(MimeTypeGuesser::getInstance());
        $this->assertSame(2, $g->count());
    }

    public function testGuess()
    {
        $mock = $this->createMimeTypeGuesserMock();
        $mock
            ->expects($this->once())
            ->method('guess')
            ->with('foobar');

        $inst = new ContentTypeGuesser($mock);
        $inst->guess('foobar');

        $mock = $this->createMimeTypeGuesserMock();
        $mock
            ->expects($this->exactly(5))
            ->method('guess')
            ->with('foobar')
            ->willReturnOnConsecutiveCalls(null, null, null, null, 'baz');

        $inst = new ContentTypeGuesser($mock, $mock, $mock);

        $this->assertNull($inst->guess('foobar'));
        $this->assertSame('baz', $inst->guess('foobar'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|MimeTypeGuesser
     */
    private function createMimeTypeGuesserMock()
    {
        return $this
            ->getMockBuilder(MimeTypeGuesser::class)
            ->setMethods(['guess'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }
}
