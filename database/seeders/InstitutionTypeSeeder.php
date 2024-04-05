<?php

namespace Database\Seeders;

use App\Models\InstitutionType;
use Illuminate\Database\Seeder;

class InstitutionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $institutionTypes = [
            ["name" => InstitutionType::emptyRecordName()],
            ["name" => "Jewish"],
            ["name" => "Civic"],
        ];

        foreach ($institutionTypes as $institutionType) {
            InstitutionType::create($institutionType);
        }
    }
}
