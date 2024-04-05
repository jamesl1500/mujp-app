<?php

namespace Database\Seeders;

use App\Models\InstitutionRole;
use Illuminate\Database\Seeder;

class InstitutionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $institutionRoles = [
            ["name" => InstitutionRole::emptyRecordName()],
            ["name" => "Founder"],
            ["name" => "Supporter"],
        ];

        foreach ($institutionRoles as $institutionRole) {
            InstitutionRole::create($institutionRole);
        }
    }
}
