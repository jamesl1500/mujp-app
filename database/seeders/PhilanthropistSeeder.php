<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\City;
use App\Models\Industry;
use App\Models\Institution;
use App\Models\InstitutionRole;
use App\Models\InstitutionType;
use App\Models\Philanthropist;
use App\Models\PhilanthropistAssociatedPeople;
use App\Models\PhilanthropistInstitution;
use App\Models\PhilanthropistRelation;
use App\Models\RelationType;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class PhilanthropistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $philanthropists = Philanthropist::factory(20)->create();
        foreach ($philanthropists as $philanthropist) {
            //#region Family Relations
            $recordCount = rand(0, 3);
            $selectedRelationIdList = [];
            for ($i = 0; $i < $recordCount; $i++) {
                $dbOrManualDecision = rand(0, 1) == 1;
                $relationTypeId = RelationType::all()->random()->id;
                $philanthropist_relation_values = [
                    'philanthropist_id' => $philanthropist->id,
                    'relation_type_id' => $relationTypeId,
                    'created_by' => 1
                ];
                if ($dbOrManualDecision) {
                    $dbPhilanthropists = Philanthropist::whereNotIn('id', $selectedRelationIdList)->get();
                    if ($dbPhilanthropists->count()) {
                        $philanthropist_relation_values['related_philanthropist_id'] = $dbPhilanthropists->random()->id;
                    } else {
                        $philanthropist_relation_values['firstname'] = ucfirst($faker->firstName());
                        $philanthropist_relation_values['lastname'] = ucfirst($faker->lastName());
                    }
                } else {
                    $philanthropist_relation_values['firstname'] = ucfirst($faker->firstName());
                    $philanthropist_relation_values['lastname'] = ucfirst($faker->lastName());
                }
                PhilanthropistRelation::create($philanthropist_relation_values);
            }
            //#endregion

            //#region Business
            $business_values = [
                'philanthropist_id' => $philanthropist->id,
                'name' => $faker->company(),
                'details' => $faker->sentence(6, true)
            ];

            $dbOrManualDecision = rand(0, 1) == 1;
            if ($dbOrManualDecision) {
                $business_values['industry_id'] = Industry::all()->random()->id;
            } else {
                $business_values['industry_other'] = $faker->text(6);
            }

            Business::create($business_values);
            //#endregion

            //#region Business People Closely Associated With
            $recordCount = rand(0, 5);

            for ($i = 0; $i < $recordCount; $i++){
                $associated_people_values = [
                    'created_by' => 1,
                    'philanthropist_id' => $philanthropist->id
                ];
                $dbOrManualDecision = rand(0, 1);
                if($dbOrManualDecision){
                    $dbPhilanthropists = Philanthropist::whereNotIn('id', $selectedRelationIdList)->get();
                    if ($dbPhilanthropists->count()) {
                        $associated_people_values['associated_philanthropist_id'] = $dbPhilanthropists->random()->id;
                    } else {
                        $associated_people_values['firstname'] = ucfirst($faker->firstName());
                        $associated_people_values['lastname'] = ucfirst($faker->lastName());
                    }
                } else {
                    $associated_people_values['firstname'] = ucfirst($faker->firstName());
                    $associated_people_values['lastname'] = ucfirst($faker->lastName());
                }
                PhilanthropistAssociatedPeople::create($associated_people_values);
            }
            #endregion

            //#region Founders/Supporters

            $recordCount = rand(0,5);
            $institutionIdList = [];
            for ($i = 0; $i < $recordCount; $i++ ){
                $philanthropist_institution_values = [
                    'philanthropist_id' => $philanthropist->id,
                    'city_id' => City::inRandomOrder()->first()->id,
                    'institution_role_id' => InstitutionRole::all()->random()->id,
                    'institution_type_id' => InstitutionType::all()->random()->id,
                    'created_by' => 1
                ];

                $dbOrManualDecision = rand(0, 1) == 1;
                if($dbOrManualDecision) {
                    $institutions = Institution::whereNotIn('id', $institutionIdList)->get();
                    if ($institutions->count()) {
                        $philanthropist_institution_values['institution_id']  = $institutions->random()->id;
                    } else {
                        $philanthropist_institution_values['institution_other'] = ucfirst($faker->company);
                    }
                }else {
                    $philanthropist_institution_values['institution_other'] = ucfirst($faker->company);
                }

                PhilanthropistInstitution::create($philanthropist_institution_values);
            }


            //#endregion

        }
    }
}
