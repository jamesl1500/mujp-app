<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\FileTag;
use App\Models\Industry;
use App\Models\Institution;
use App\Models\InstitutionRole;
use App\Models\InstitutionType;
use App\Models\Philanthropist;
use App\Models\PhilanthropistFile;
use App\Models\RelationType;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function App\Helpers\get_fa_icon_by_ext;
use function App\Helpers\readeble_file_size;

class SubmissionsController extends Controller
{
    public $bladePrefix = 'member.submissions.';

    public function index()
    {
        $submissions = Philanthropist::where('created_by', '=', auth()->id())->orderBy('created_at', 'DESC')->get();
        return view($this->bladePrefix . 'submissions-index', [
            'submissions' => $submissions
        ]);
    }

    public function view(Philanthropist $philanthropist, Request $request)
    {
        $breadcrumbs = [
            ['link' => route('member.submissions'), 'name' => "My Submissions"], ['name' => "View"]
        ];

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


        return view('member.submissions.submissions_view', [
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
                'phiFileId' => $pfile->id
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

}
