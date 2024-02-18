<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class MembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::factory()->count(5)->create();

        $members = array(
            array(
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'birth_date' => '1990-01-01'
            ),
            array(
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'birth_date' => '1992-05-15'
            ),
            array(
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'email' => 'michael.johnson@example.com',
                'birth_date' => '1985-09-20'
            ),
            array(
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'email' => 'emily.brown@example.com',
                'birth_date' => '1988-11-10'
            ),
            array(
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'email' => 'david.wilson@example.com',
                'birth_date' => '1979-03-25'
            )
        );

        foreach ($members as $member) {
            $newMember = \App\Models\Member::factory()
                ->create($member);
            $tags = Tag::inRandomOrder()->limit(rand(0, 3))->get();
            $newMember->tags()->attach($tags);
        }
    }
}
