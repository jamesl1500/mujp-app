<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessPeopleCloselyAssociated;
use App\Models\City;
use App\Models\Country;
use App\Models\Favorite;
use App\Models\File;
use App\Models\FileTag;
use App\Models\Foundation;
use App\Models\Industry;
use App\Models\Institution;
use App\Models\InstitutionRole;
use App\Models\InstitutionType;
use App\Models\Philanthropist;
use App\Models\PhilanthropistAssociatedPeople;
use App\Models\PhilanthropistFile;
use App\Models\PhilanthropistInstitution;
use App\Models\PhilanthropistRelation;
use App\Models\RelationType;
use App\Models\State;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Monolog\Logger;
use PhpParser\Builder;
use function App\Helpers\get_fa_icon_by_ext;
use function App\Helpers\readeble_file_size;
use function GuzzleHttp\Psr7\mimetype_from_extension;

class PhilanthropistController extends Controller
{
    public $bladePrefix = 'admin.library.philanthropists.';

    public function index()
    {
        $industries = Industry::orderBy('name')->get();
        $statuses = Philanthropist::getPossibleStatuses();
        $philanthropists = Philanthropist::with(['business'])->orderBy('created_at', 'desc')->get();
        $breadcrumbs = [
            ['link' => route('philanthropists.index'), 'name' => "Philanthropists"], ['name' => "View"]
        ];
        return view($this->bladePrefix . 'philanthropists-index', [
            "industries" => $industries,
            "philanthropists" => $philanthropists,
            "statuses" => $statuses,
            "breadcrumbs" => $breadcrumbs
        ]);
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('philanthropists.index'), 'name' => "Philanthropists"], ['name' => "Add"]
        ];
        $countries = Country::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $institutions = Institution::orderBy('name')->get();
        $institutionRoles = InstitutionRole::orderBy('name')->get();
        $institutionTypes = InstitutionType::orderBy('name')->get();
        $relationTypes = RelationType::orderBy('name')->get();

        return view($this->bladePrefix . 'philanthropists-add', [
            'breadcrumbs' => $breadcrumbs,
            'countries' => $countries,
            'industries' => $industries,
            'institutions' => $institutions,
            'institutionRoles' => $institutionRoles,
            'institutionTypes' => $institutionTypes,
            'relationTypes' => $relationTypes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'year_of_birth' => 'required|integer|min:1700|max:' . date('Y'),
        ]);

        //#region Philanthropist
        $philanthropist_value = [
            'created_by' => Auth::user()->id,
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname') != null ? $request->input('lastname') : 'Unknown',
            'gender' => $request->input('gender'),
            'city_of_birth' => $request->input('city_of_birth'),
            'city_of_birth_other' => $request->input('city_of_birth_other'),
            'country_of_most_lived_in' => $request->input('country_of_most_lived_in'),
            'biography' => $request->input('biography'),
            'state_of_most_lived_in' => $request->input('state_of_most_lived_in'),
            'city_of_most_lived_in' => $request->input('city_of_most_lived_in'),
            'city_of_most_lived_in_other' => $request->input('city_of_most_lived_in_other'),
            'year_of_birth' => $request->input('year_of_birth'),
            'month_of_birth' => $request->input('month_of_birth'),
            'date_of_birth' => $request->input('date_of_birth'),
            'jewish_year_of_birth' => $request->input('jewish_year_of_birth'),
            'jewish_month_of_birth' => $request->input('jewish_month_of_birth'),
            'jewish_date_of_birth' => $request->input('jewish_date_of_birth'),
            'year_of_death' => $request->input('year_of_death'),
            'month_of_death' => $request->input('month_of_death'),
            'date_of_death' => $request->input('date_of_death'),
            'jewish_year_of_death' => $request->input('jewish_year_of_death'),
            'jewish_month_of_death' => $request->input('jewish_month_of_death'),
            'jewish_date_of_death' => $request->input('jewish_date_of_death'),
            'status' => Auth::user()->role_key == 'admin' ? 'active' : 'pending'
        ];
        $philanthropist = Philanthropist::create($philanthropist_value);
        if (!$philanthropist) {
            return redirect()->back()->with('error', 'Something went wrong while adding Philanthropist');
        }
        //#endregion

        //#region Family Relations
        $family_relations = $request->input('group_family_tree');
        foreach ($family_relations as $index => $relation) {
            if ($relation['relation_type'] && ((array_key_exists('family_tree_selected_philanthropist', $relation) && $relation['family_tree_selected_philanthropist']) || ($relation['relation_firstname'] && $relation['relation_lastname']))) {
                if (!RelationType::where('id', '=', $relation['relation_type'])->exists()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'group_family_tree[' . $index . '][relation_type]' => ['Relation type is not valid'],
                    ]);
                }

                $philanthropist_relation_values = [
                    'philanthropist_id' => $philanthropist->id,
                    'relation_type_id' => $relation['relation_type'],
                    'created_by' => Auth::user()->id,
                ];

                if (array_key_exists('family_tree_selected_philanthropist', $relation) && $relation['family_tree_selected_philanthropist']) {
                    $philanthropist_relation_values['related_philanthropist_id'] = $relation['family_tree_selected_philanthropist'];
                } else {
                    $philanthropist_relation_values['firstname'] = ucfirst($relation['relation_firstname']);
                    $philanthropist_relation_values['lastname'] = ucfirst($relation['relation_lastname']);
                }

                $executed = PhilanthropistRelation::create($philanthropist_relation_values);
                if (!$executed) {
                    //TODO handle error
                    dump('Something went wrong while creating PhilanthropistRelation');
                    dump($philanthropist_relation_values);
                }
            }
        }

        //#endregion

        //#region Businesses
        if ($request->business_name && ($request->business_industry || $request->business_industry_other)) {
            $business_values = [
                'philanthropist_id' => $philanthropist->id,
                'name' => $request->business_name,
                'details' => $request->business_details,
            ];

            if ($request->business_industry) {
                $business_values['industry_id'] = $request->business_industry;
                $business_values['industry_other'] = null;
            } else {
                $business_values['industry_id'] = null;
                $business_values['industry_other'] = ucfirst($request->business_industry_other);
            }

            $executed = Business::create($business_values);
            if (!$executed) {
                return redirect()->back()->with('error', 'Something went wrong while adding Business!');
            }
        }
        //#endregion

        //#region Business People Closely Associated With
        foreach ($request->group_business_people as $associatedPeople) {
            if (($associatedPeople['business_people_firstname'] && $associatedPeople['business_people_lastname']) || (array_key_exists('business_people_selected_philanthropist', $associatedPeople) && $associatedPeople['business_people_selected_philanthropist'])) {
                $associated_people_values = [
                    'created_by' => Auth::user()->id,
                    'philanthropist_id' => $philanthropist->id
                ];
                if (array_key_exists('business_people_selected_philanthropist', $associatedPeople) && $associatedPeople['business_people_selected_philanthropist']) {
                    $associated_people_values['associated_philanthropist_id'] = $associatedPeople['business_people_selected_philanthropist'];
                } else if ($associatedPeople['business_people_firstname'] && $associatedPeople['business_people_lastname']) {
                    $associated_people_values['firstname'] = ucfirst($associatedPeople['business_people_firstname']);
                    $associated_people_values['lastname'] = ucfirst($associatedPeople['business_people_lastname']);
                }

                $executed = PhilanthropistAssociatedPeople::create($associated_people_values);
                if (!$executed) {
                    return redirect()->back()->with('error', 'Something went wrong while adding business people closely associated with!');
                }
            }
        }

        //#endregion

        //#region Founders Supporters
        foreach ($request->input('group_founders_supporters') as $index => $institution) {
            //TODO Required fields check
            if ((array_key_exists('founders_supporters_institution_name', $institution) && ($institution['founders_supporters_institution_name']) || (array_key_exists('founders_supporters_institution_other', $institution) && $institution['founders_supporters_institution_other']))
                && $institution['founders_supporters_institution_role']
                && $institution['founders_supporters_institution_type']) {
                $philanthropist_institution_values = [
                    'philanthropist_id' => $philanthropist->id,
                    'city_id' => array_key_exists('founders_supporters_city', $institution) && $institution['founders_supporters_city'] ? $institution['founders_supporters_city'] : null,
                    'institution_role_id' => $institution['founders_supporters_institution_role'],
                    'institution_type_id' => $institution['founders_supporters_institution_type'],
                    'created_by' => Auth::user()->id
                ];

                if ($institution['founders_supporters_institution_name']) {
                    $philanthropist_institution_values['institution_id'] = $institution['founders_supporters_institution_name'];
                } else {
                    $philanthropist_institution_values['institution_other'] = ucfirst($institution['founders_supporters_institution_other']);
                }

                $executed = PhilanthropistInstitution::create($philanthropist_institution_values);
                if (!$executed) {
                    return redirect()->back()->with('error', 'Something went wrong while adding institutions!');
                }
            }
        }
        //#endregion
//        return redirect()->back()->with('success', 'Philanthropist is created successfully');
        return redirect(route('philanthropists.edit', $philanthropist->id))->with('success', 'Philanthropist is created successfully');
    }

    public function edit(Philanthropist $philanthropist, Request $request)
    {
        $breadcrumbs = [
            ['link' => route('philanthropists.index'), 'name' => "Philanthropists"], ['name' => "Edit"]
        ];

        $statuses = Philanthropist::getPossibleStatuses();
        $countries = Country::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $institutions = Institution::orderBy('name')->get();
        $institutionRoles = InstitutionRole::orderBy('name')->get();
        $institutionTypes = InstitutionType::orderBy('name')->get();
        $relationTypes = RelationType::orderBy('name')->get();
        $fileTags = FileTag::where('id','!=' ,FileTag::untaggedRecordId())->orderBy('name')->get();

        $cities = null;
        $states = null;
        $selectedStateId = null;
        $selectedCountryId = null;

        $selectedMostLivedInCountryId = null;
        $selectedMostLivedInStateId = null;
        $selectedMostLivedInCityId = null;
        $mostLivedInCities = null;
        $mostLivedInStates = null;

        $relatedPeoples = null;
        $associatedPeoples = null;
        $philanthropistInstitutions = null;

        $philanthropistFiles = null;

        if ($philanthropist->city_of_birth) {
            $state = $philanthropist->city()->first()->state()->first();
            $selectedStateId = $state->id;
            $states = State::where('country_id', '=', $state->country_id)->orderBy('name')->get();
            $cities = City::where('state_id', '=', $state->id)->orderBy('name')->get();
            $selectedCountryId = $state->country_id;
        }

        if ($philanthropist->country_of_most_lived_in) {
            $selectedMostLivedInCountryId = $philanthropist->country_of_most_lived_in;
            if (!$philanthropist->state_of_most_lived_in) {
                $mostLivedInStates = State::where('country_id', '=', $selectedMostLivedInCountryId)->get();
            }
        }

        if ($philanthropist->state_of_most_lived_in) {
            $selectedMostLivedInStateId = $philanthropist->state_of_most_lived_in;
            $countryId = State::find($selectedMostLivedInStateId)->country_id;
            $mostLivedInStates = State::where('country_id', '=', $countryId)->get();
            if (!$philanthropist->city_of_most_lived_in) {
                $mostLivedInCities = City::where('state_id', '=', $selectedMostLivedInStateId)->get();
            }
        }

        if ($philanthropist->city_of_most_lived_in) {
            $selectedMostLivedInCityId = $philanthropist->city_of_most_lived_in;
            $stateId = City::find($selectedMostLivedInCityId)->state_id;
            $mostLivedInCities = City::where('state_id', '=', $stateId)->get();
        }

        if ($philanthropist->relations()->exists()) {
            $relatedPeoples = $philanthropist->relations()->with('relatedPhilanthropist.business.industry')->get()->toJson();
//            $relatedPeoples = $philanthropist->relations()->with('relatedPhilanthropist.business.industry')->get()->toJson();

        }

        if ($philanthropist->associatedPeoples()->exists()) {
            $associatedPeoples = $philanthropist->associatedPeoples()->with('associatedPhilanthropist.business.industry')->get()->toJson();
        }

        if ($philanthropist->institutions()->exists()) {
            $philanthropistInstitutions = $philanthropist->institutions()->with('city')->get()->toJson();
        }

//        $filesPath = 'public/uploads/philanthropists/' . $philanthropist->id;
//        if (Storage::exists($filesPath)) {
//            $philanthropistFiles = [];
//            $files = Storage::allFiles($filesPath);
//            foreach ($files as $file) {
//                $fileName = pathinfo($file, PATHINFO_FILENAME) . '.' . pathinfo($file, PATHINFO_EXTENSION);
//                $extension = basename(dirname($file));
//                $icon = get_fa_icon_by_ext($extension);
//                $philanthropistFiles[$extension][] = [
//                    'file' => $fileName,
//                    'icon' => $icon,
//                    'path' => str_replace('public', 'storage', asset($file)),
//                    'date' => date("m/d/Y H:i:s", Storage::lastModified($file)),
//                    'size' => readeble_file_size(Storage::size($file))
//                ];
//            }
//        }


        return view($this->bladePrefix . 'philanthropists-edit', [
            'statuses' => $statuses,
            'breadcrumbs' => $breadcrumbs,
            'fileTags' => $fileTags,
            'countries' => $countries,
            'industries' => $industries,
            'states' => $states,
            'cities' => $cities,
            'selectedCountryId' => $selectedCountryId,
            'selectedStateId' => $selectedStateId,
            'selectedMostLivedInCountryId' => $selectedMostLivedInCountryId,
            'mostLivedInStates' => $mostLivedInStates,
            'selectedMostLivedInStateId' => $selectedMostLivedInStateId,
            'mostLivedInCities' => $mostLivedInCities,
            'selectedMostLivedInCityId' => $selectedMostLivedInCityId,
            'institutions' => $institutions,
            'institutionRoles' => $institutionRoles,
            'institutionTypes' => $institutionTypes,
            'relationTypes' => $relationTypes,
            'philanthropist' => $philanthropist,
            'relatedPeoples' => $relatedPeoples,
            'associatedPeoples' => $associatedPeoples,
            'philanthropistInstitutions' => $philanthropistInstitutions,
            'philanthropistFiles' => $this->getPhilanthropistFiles($philanthropist->id)
        ]);
//        dump($philanthropist->business()->first()->name);
    }

    public function update(Philanthropist $philanthropist, Request $request)
    {
        $philanthropistValues = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname != null ? $request->lastname : 'Unknown',
            'gender' => $request->gender,
            'year_of_birth' => $request->year_of_birth,
            'month_of_birth' => $request->month_of_birth,
            'date_of_birth' => $request->date_of_birth,
            'jewish_year_of_birth' => $request->jewish_year_of_birth,
            'jewish_month_of_birth' => $request->jewish_month_of_birth,
            'jewish_date_of_birth' => $request->jewish_date_of_birth,
            'year_of_death' => $request->year_of_death,
            'month_of_death' => $request->month_of_death,
            'date_of_death' => $request->date_of_death,
            'jewish_year_of_death' => $request->jewish_year_of_death,
            'jewish_month_of_death' => $request->jewish_month_of_death,
            'jewish_date_of_death' => $request->jewish_date_of_death,
            'city_of_birth' => $request->city_of_birth,
            'country_of_most_lived_in' => $request->input('country_of_most_lived_in'),
            'biography' => $request->biography,
            'state_of_most_lived_in' => $request->input('state_of_most_lived_in'),
            'city_of_most_lived_in' => $request->input('city_of_most_lived_in'),
            'city_of_most_lived_in_other' => $request->input('city_of_most_lived_in_other'),
            'status' => $request->status
        ];

        $executed = Philanthropist::where('id', '=', $philanthropist->id)->update($philanthropistValues);
        if (!$executed) {
            return redirect()->back()->with('error', 'Something went wrong while uptading philanthropist!');
        }

        //#region Family Relations
        $family_relations = $request->input('group_family_tree');
        foreach ($family_relations as $index => $relation) {
            if ($relation['relation_type'] && ((array_key_exists('family_tree_selected_philanthropist', $relation) && $relation['family_tree_selected_philanthropist']) || ($relation['relation_firstname'] && $relation['relation_lastname']))) {
                if (!RelationType::where('id', '=', $relation['relation_type'])->exists()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'group_family_tree[' . $index . '][relation_type]' => ['Relation type is not valid'],
                    ]);
                }

                $people_values = [
                    'philanthropist_id' => $philanthropist->id,
                    'relation_type_id' => $relation['relation_type'],
                    'created_by' => Auth::user()->id,
                ];

                if (array_key_exists('family_tree_selected_philanthropist', $relation) && $relation['family_tree_selected_philanthropist']) {
                    $people_values['related_philanthropist_id'] = $relation['family_tree_selected_philanthropist'];
                    $people_values['firstname'] = null;
                    $people_values['lastname'] = null;
                } else {
                    $people_values['related_philanthropist_id'] = null;
                    $people_values['firstname'] = ucfirst($relation['relation_firstname']);
                    $people_values['lastname'] = ucfirst($relation['relation_lastname']);
                }

                $executed = false;

                if ($relation['record_id']) {
                    $executed = PhilanthropistRelation::where('id', '=', $relation['record_id'])->update($people_values);
                } else {
                    $people_values['created_by'] = Auth::user()->id;
                    $executed = PhilanthropistRelation::create($people_values);
                }

                if (!$executed) {
                    //TODO: handle error
                    dump('Something went wrong while updating philanthropist family tree!');
                    dump($people_values);
                }
            }
        }

        //Deleting Related Peoples
        if ($request->deleted_related_peoples) {
            $deletedInstitutions = explode(',', $request->deleted_related_peoples);
            foreach ($deletedInstitutions as $relatedPeopleId) {
                $relatedPeople = PhilanthropistRelation::where('id', '=', $relatedPeopleId)
                    ->where('philanthropist_id', '=', $philanthropist->id);
                if ($relatedPeople->exists()) {
                    $relatedPeople->delete();
                }
            }
        }

        //#endregion
        //#region Business & Industry
        if ($request->business_name && ($request->business_industry || $request->business_industry_other)) {
            $business_values = [
                'name' => $request->business_name,
                'details' => $request->business_details,
            ];

            if ($request->business_industry) {
                $business_values['industry_id'] = $request->business_industry;
                $business_values['industry_other'] = null;
            } else {
                $business_values['industry_id'] = null;
                $business_values['industry_other'] = ucfirst($request->business_industry_other);
            }

            $executed = false;
            if ($philanthropist->business()->exists()) {
                $executed = $philanthropist->business()->update($business_values);
            } else {
                $business_values['philanthropist_id'] = $philanthropist->id;
                $executed = Business::create($business_values);
            }
            if (!$executed) {
                return redirect()->back()->with('error', 'Something went wrong while updating philanthropist business!');
            }
        }

        //#region Business People Closely Associated With
        //Handle Associated Peoples
        $associatedPeoples = $request->input('group_business_people');
        foreach ($associatedPeoples as $index => $associatedPeople) {
            if (($associatedPeople['business_people_firstname'] && $associatedPeople['business_people_lastname']) || (array_key_exists('business_people_selected_philanthropist', $associatedPeople) && $associatedPeople['business_people_selected_philanthropist'])) {
                $people_values = [
                    'philanthropist_id' => $philanthropist->id
                ];

                if (array_key_exists('business_people_selected_philanthropist', $associatedPeople) && $associatedPeople['business_people_selected_philanthropist']) {
                    $people_values['associated_philanthropist_id'] = $associatedPeople['business_people_selected_philanthropist'];
                    $people_values['firstname'] = null;
                    $people_values['lastname'] = null;
                } else {
                    $people_values['associated_philanthropist_id'] = null;
                    $people_values['firstname'] = ucfirst($associatedPeople['business_people_firstname']);
                    $people_values['lastname'] = ucfirst($associatedPeople['business_people_lastname']);
                }

                $executed = false;

                if ($associatedPeople['record_id']) {
                    $executed = PhilanthropistAssociatedPeople::where('id', '=', $associatedPeople['record_id'])->update($people_values);
                } else {
                    $people_values['created_by'] = Auth::user()->id;
                    $executed = PhilanthropistAssociatedPeople::create($people_values);
                }

                if (!$executed) {
                    //TODO: handle error
                    dump('Something went wrong while updating philanthropist family tree!');
                    dump($people_values);
                }
            }
        }

        //#region Deleting Associated Peoples
        if ($request->deleted_associated_peoples) {
            $deletedInstitutions = explode(',', $request->deleted_associated_peoples);
            foreach ($deletedInstitutions as $associatedPeopleId) {
                $relatedPeople = PhilanthropistAssociatedPeople::where('id', '=', $associatedPeopleId)
                    ->where('philanthropist_id', '=', $philanthropist->id);
                if ($relatedPeople->exists()) {
                    $relatedPeople->delete();
                }
            }
        }
        //#enregion
        //#endregion

        //#region Institutions (Founders & Supporters)

        $institutionsGroup = $request->input('group_founders_supporters');
        foreach ($institutionsGroup as $index => $institution) {
            //TODO Required fields check
            if ((array_key_exists('founders_supporters_institution_name', $institution) && ($institution['founders_supporters_institution_name']) || (array_key_exists('founders_supporters_institution_other', $institution) && $institution['founders_supporters_institution_other']))
                && array_key_exists('founders_supporters_institution_role', $institution) && $institution['founders_supporters_institution_role']
                && array_key_exists('founders_supporters_institution_type', $institution) && $institution['founders_supporters_institution_type']) {

                $philanthropist_institution_values = [
                    'city_id' => (array_key_exists('founders_supporters_city', $institution) && $institution['founders_supporters_city']) ? $institution['founders_supporters_city'] : null,
                    'institution_role_id' => (array_key_exists('founders_supporters_institution_role', $institution) && $institution['founders_supporters_institution_role']) ? $institution['founders_supporters_institution_role'] : null,
                    'institution_type_id' => (array_key_exists('founders_supporters_institution_type', $institution) && $institution['founders_supporters_institution_type']) ? $institution['founders_supporters_institution_type'] : null,
                ];

                if ($institution['founders_supporters_institution_name']) {
                    $philanthropist_institution_values['institution_id'] = $institution['founders_supporters_institution_name'];
                    $philanthropist_institution_values['institution_other'] = null;

                } else {
                    $philanthropist_institution_values['institution_id'] = null;
                    $philanthropist_institution_values['institution_other'] = ucfirst($institution['founders_supporters_institution_other']);
                }

                if (array_key_exists('record_id', $institution) && $institution['record_id']) {
                    $philanthropistInstitution = PhilanthropistInstitution::where('id', '=', $institution['record_id'])
                        ->where('philanthropist_id', '=', $philanthropist->id);
                    if ($philanthropistInstitution->exists()) {
                        $executed = $philanthropistInstitution->update($philanthropist_institution_values);
                    }
                } else {
                    $philanthropist_institution_values['philanthropist_id'] = $philanthropist->id;
                    $philanthropist_institution_values['created_by'] = Auth::user()->id;
                    $executed = PhilanthropistInstitution::create($philanthropist_institution_values);
                }

                if (!$executed) {
                    //TODO: handle error
                    dump('Something went wrong while updating philanthropist family tree!');
                    dump($people_values);
                }
            }
        }

        //#region Deleting Institutions
        if ($request->deleted_institutions) {
            $deletedInstitutions = explode(',', $request->deleted_institutions);
            foreach ($deletedInstitutions as $currInstitution) {
                $record = PhilanthropistInstitution::where('id', '=', $currInstitution)
                    ->where('philanthropist_id', '=', $philanthropist->id);
                if ($record->exists()) {
                    $record->delete();
                }
            }
        }
        //#enregion


        //#endregion


        return redirect()->back()->with('success', 'Philanthropist updated successfully.');
    }

    public function getPhilanthropistFiles($philanthropistId)
    {
        $accesibleFiles = [];

        $pFiles = PhilanthropistFile::with('file', 'file.fileTag')->where('philanthropist_id', '=', $philanthropistId)->orderBy('created_at', 'desc');
        if (!$pFiles->exists()) {
            return [];
        }

        $pFiles = $pFiles->get();

        foreach ($pFiles as $pfile) {
            if (!Storage::exists($pfile->file->path)) {
                continue;
            }
//            $type = explode("/", $pfile->file->type)[1];
//            $type = pathinfo($pfile->file->path, PATHINFO_EXTENSION);
            $type = basename(dirname($pfile->file->path));

            $filePath = $pfile->file->path;
            $fileTag = $pfile->file->fileTag->name;
            $fileTagId = $pfile->file->fileTag->id;

            $accesibleFiles[$type][] = [
                'file' => $pfile->file->name,
                'icon' => get_fa_icon_by_ext($type),
                'path' => str_replace('public', 'storage', asset($filePath)),
                'date' => date("m/d/Y H:i:s", Storage::lastModified($filePath)),
                'size' => readeble_file_size(Storage::size($filePath)),
                'tag' => $fileTag,
                'tagId' => $fileTagId,
                'phiFileId' => $pfile->id,
                'caption' => $pfile->file->caption
            ];
        }

        return $accesibleFiles;


        $philanthropistFiles = [];
        $filesPath = 'public/uploads/philanthropists/' . $philanthropistId;
        if (Storage::exists($filesPath)) {
            $files = Storage::allFiles($filesPath);
            foreach ($files as $file) {
                $fileName = pathinfo($file, PATHINFO_FILENAME) . '.' . pathinfo($file, PATHINFO_EXTENSION);
                $extension = basename(dirname($file));
                $icon = get_fa_icon_by_ext($extension);
                $philanthropistFiles[$extension][] = [
                    'file' => $fileName,
                    'icon' => $icon,
                    'path' => str_replace('public', 'storage', asset($file)),
                    'date' => date("m/d/Y H:i:s", Storage::lastModified($file)),
                    'size' => readeble_file_size(Storage::size($file))
                ];
            }
        }

        return $philanthropistFiles;
    }

//    public function removeFile(Request $request, Philanthropist $philanthropist)
//    {
//        try {
//            $result = false;
//            $fileUrl = $request->fileUrl;
//            $startIndex = strpos($fileUrl, '/uploads/');
//            $filePath = substr($fileUrl, $startIndex, strlen($fileUrl) - $startIndex);
//            $filePath = 'public' . $filePath;
//
//            if (Storage::exists($filePath)) {
//                $result = Storage::delete($filePath);
//            }
//            if ($result) {
//                return response()->json('File deleted successfully!', 200);
//            }
//            return response()->json('File not deleted.', 412);
//        } catch (\Exception $ex) {
//            return response()->json('An error occured while deleting file.', 500);
//        }
//    }


    public function destroy(Philanthropist $philanthropist)
    {
        $philanthropistId = $philanthropist->id;
        if ($philanthropist->delete()) {
            //#region Deleting Philanthropist Related Records
            $associatedPeopleRecords = PhilanthropistAssociatedPeople::withTrashed()->where('associated_philanthropist_id', '=', $philanthropistId);
            if ($associatedPeopleRecords->exists()) {
                $associatedPeopleRecords->delete();
            }
            $relatedPeoples = PhilanthropistRelation::withTrashed()->where('related_philanthropist_id', '=', $philanthropistId);
            if ($relatedPeoples->exists()) {
                $relatedPeoples->delete();
            }
            $businesses = Business::withTrashed()->where('philanthropist_id', '=', $philanthropistId);
            if ($businesses->exists()) {
                $businesses->delete();
            }
            $favorites = Favorite::withTrashed()->where('philanthropist_id', '=', $philanthropistId);
            if ($favorites->exists()) {
                $favorites->delete();
            }
            //#endregion
            return redirect()->back()->with('success', 'Philanthropist deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while deleting philanthropist!');
    }

    public function destroyAll(Request $request)
    {
        $philanthropistIds = $request->input('philanthropistIds');
        if (Philanthropist::destroy($philanthropistIds)) {
            foreach($philanthropistIds as $philanthropistId){
                //#region Deleting Philanthropist Related Records
                $associatedPeopleRecords = PhilanthropistAssociatedPeople::withTrashed()->where('associated_philanthropist_id', '=', $philanthropistId);
                if ($associatedPeopleRecords->exists()) {
                    $associatedPeopleRecords->delete();
                }
                $relatedPeoples = PhilanthropistRelation::withTrashed()->where('related_philanthropist_id', '=', $philanthropistId);
                if ($relatedPeoples->exists()) {
                    $relatedPeoples->delete();
                }
                $businesses = Business::withTrashed()->where('philanthropist_id', '=', $philanthropistId);
                if ($businesses->exists()) {
                    $businesses->delete();
                }
                $favorites = Favorite::withTrashed()->where('philanthropist_id', '=', $philanthropistId);
                if ($favorites->exists()) {
                    $favorites->delete();
                }
                //#endregion
            }
            return response()->json('Philanthropists deleted successfully!', 200);
        }
        return response()->json('Something went wrong while deleting philanthropists!', 412);
    }

    public function search(Request $request)
    {
        $name = $request->input('name');
        $birth_year = $request->input('birth_year');
        $death_year = $request->input('death_year');
        $status = $request->input('status');
        $industry_id = $request->input('industry');
        $business_name = $request->input('business_name');
        $institution_name = $request->input('institution_name');

        /** @var Philanthropist $philanthropists */
        $philanthropists = null;
        if (strlen(trim($name)) > 0) {
            $philanthropists = Philanthropist::where(DB::raw("concat(firstname, ' ', lastname)"), 'like', $name . '%')->orderBy(DB::raw("concat(firstname, ' ', lastname)"))->get();
            $foundedIds = $philanthropists->pluck('id')->toArray();

            $philanthropistsByLastname = Philanthropist::where(DB::raw("concat(firstname, ' ', lastname)"), 'like', '%' . $name . '%')
                ->whereNotIn('id', $foundedIds)
                ->orderBy(DB::raw("concat(firstname, ' ', lastname)"))
                ->orderBy(DB::raw('firstname + lastname'))
                ->get();

            $philanthropists = $philanthropists->merge($philanthropistsByLastname);
        } else {
            $philanthropists = Philanthropist::orderBy('created_at', 'desc')->get();
        }

        if ($status) {
            $philanthropists = $philanthropists->where('status', '=', $status);
        }

        if ($birth_year) {
            $philanthropists = $philanthropists->where('year_of_birth', '=', $birth_year);
        }

        if ($death_year) {
            $philanthropists = $philanthropists->where('year_of_death', '=', $death_year);
        }

        if ($industry_id) {
//            /** @var Business $business */
//            $business =Industry::find($industy_id)->businesses()->first();
//            $ph = $business->philanthropist()->first();

            $philanthropistsIdList = Business::where('industry_id', '=', $industry_id)->get()->pluck('philanthropist_id');
            $philanthropists = $philanthropists->whereIn('id', $philanthropistsIdList);
        }

        if ($business_name) {
            $philanthropistsIdList = Business::where('name', 'like', '%' . $business_name . '%')->get()->pluck('philanthropist_id');
            $philanthropists = $philanthropists->whereIn('id', $philanthropistsIdList);
        }

        if ($institution_name) {
            $query = "SELECT DISTINCT philanthropist_id FROM philanthropist_institutions
LEFT JOIN institutions on philanthropist_institutions.institution_id = institutions.id
WHERE institutions.name LIKE '%" . $institution_name . "%' OR philanthropist_institutions.institution_other LIKE '%" . $institution_name . "%'";
            $philanthropistsIdList = Arr::pluck(DB::select(DB::raw($query)), 'philanthropist_id');
            $philanthropists = $philanthropists->whereIn('id', $philanthropistsIdList);
        }


//        $philanthropists = Philanthropist::all();
//        return response()->json(['result' => $philanthropists]);
        if (Auth::user()->role_key == 'admin'){
            return view($this->bladePrefix . 'philanthropists-search-result', compact('philanthropists'))->render();
        }
        else {
            return redirect(route('member.submissions'));
        }
    }

    public function searchForOption(Request $request)
    {
        //Abraham Jones (1850 - 1940) - ACME FURNITURES (Furniture Industry)
        $template = '$firstname $lastname ($birth - $death)';

        $result = [
            'items' => []
        ];

        $philanthropists = Philanthropist::where(DB::raw("concat(firstname, ' ', lastname)"), 'like', $request->keyword . '%')->orderBy(DB::raw("concat(firstname, ' ', lastname)"))->get();
        $foundedIds = $philanthropists->pluck('id')->toArray();

        $philanthropistsByLastname = Philanthropist::where(DB::raw("concat(firstname, ' ', lastname)"), 'like', '%' . $request->keyword . '%')->orderBy(DB::raw("concat(firstname, ' ', lastname)"))
            ->whereNotIn('id', $foundedIds)
            ->orderBy(DB::raw('firstname + lastname'))
            ->get();

        $philanthropists = $philanthropists->merge($philanthropistsByLastname);

        foreach ($philanthropists as $philanthropist) {
            $currentTemplate = $template;
            $record = [
                '$firstname' => $philanthropist->firstname,
                '$lastname' => $philanthropist->lastname,
                '$birth' => $philanthropist->year_of_birth ? $philanthropist->year_of_birth : 'Unknown',
                '$death' => $philanthropist->year_of_death ? $philanthropist->year_of_death : 'Unknown',
            ];

            if ($philanthropist->business()->first()) {
                $currentTemplate = $template . ' - $business_name ($business_industry)';
                $record['$business_name'] = strtoupper($philanthropist->business()->first()->name);
                if ($philanthropist->business()->first()->industry()->first()) {
                    $record['$business_industry'] = $philanthropist->business()->first()->industry()->first()->name;
                } else if ($philanthropist->business()->first()->industry_other) {
                    $record['$business_industry'] = $philanthropist->business()->first()->industry_other;
                }
            }

            $result['items'][] = [
                'id' => $philanthropist->id,
                'text' => strtr($currentTemplate, $record)
            ];
        }
        return response()->json($result);
    }

}
