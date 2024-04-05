<?php

namespace Database\Seeders;

use App\Models\RelationType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Seeder;

class RelationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relations = [
            ['name' => RelationType::emptyRecordName()],
            ["name" => "Mother"],
            ["name" => "Father"],
            ["name" => "GrandMother"],
            ["name" => "GrandFather"],
            ["name" => "Brother"],
            ["name" => "Sister"],
            ["name" => "Uncle"],
            ["name" => "Aunt"],
        ];

        foreach ($relations as $relation) {
            RelationType::create($relation);
        }
    }
}
