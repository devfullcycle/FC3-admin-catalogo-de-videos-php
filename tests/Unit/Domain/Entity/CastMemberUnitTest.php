<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class CastMemberUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $castMember = new CastMember(
            id: new Uuid($uuid),
            name: 'Name',
            type: CastMemberType::ACTOR,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );
    }
}
