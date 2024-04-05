<?php

namespace Database\Seeders;

use App\Models\Foundation;
use App\Models\Institution;
use App\Models\Philanthropist;
use App\Models\RelationType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        User::factory(10)->create();
        Institution::factory(10)->create();
        $this->call(IndustrySeeder::class);
        $this->call(FoundationSeeder::class);
        $this->call(RelationTypeSeeder::class);
        $this->call(InstitutionRoleSeeder::class);
        $this->call(InstitutionTypeSeeder::class);
        $this->call(PhilanthropistSeeder::class);
//        Philanthropist::factory(10)->create();
    }
}
