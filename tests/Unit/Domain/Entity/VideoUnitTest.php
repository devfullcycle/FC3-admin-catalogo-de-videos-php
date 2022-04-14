<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $entity = new Video(
            id: new Uuid($uuid),
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertEquals($uuid, $entity->id());
        $this->assertEquals('new title', $entity->title);
        $this->assertEquals('description', $entity->description);
        $this->assertEquals(2029, $entity->yearLaunched);
        $this->assertEquals(12, $entity->duration);
        $this->assertEquals(true, $entity->opened);
        $this->assertEquals(true, $entity->published);
    }

    public function testId()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );

        $this->assertNotEmpty($entity->id());
    }
}
