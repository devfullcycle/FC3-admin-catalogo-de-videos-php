<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Core\Domain\Enum\CastMemberType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CastMember>
 */
class CastMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $types = [CastMemberType::ACTOR, CastMemberType::DIRECTOR];
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->name(),
            'type' => $types[array_rand($types)],
        ];
    }
}
