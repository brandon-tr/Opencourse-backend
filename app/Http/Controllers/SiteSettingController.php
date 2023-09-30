<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteSetting\SiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Validation\ValidationException;
use Lang;
use Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', SiteSetting::class);

        return SiteSetting::first();
    }

    public function indexAdmin()
    {
        $this->authorize('adminSiteSettings', SiteSetting::class);

        return SiteSetting::first();
    }

    public function returnMetaInfo()
    {
        return SiteSetting::first()->get(['site_name', 'site_description', 'site_author', 'site_keywords']);
    }

    public function returnSiteLogo()
    {
        $logo = SiteSetting::select('site_logo')->first();
        return response()->json([
            'success' => "Site logo retrieved successfully",
            'site_logo' => $logo->site_logo,
        ], 200);
    }

    public function navbarInfo()
    {
        $logo = SiteSetting::select('site_logo', 'site_name', 'text_over_logo')->first();
        return response()->json([
            'success' => "Site logo retrieved successfully",
            'site_logo' => $logo->site_logo,
            'site_name' => $logo->site_name,
            'text_over_logo' => $logo->text_over_logo,
        ], 200);
    }

    public function store(SiteSettingRequest $request)
    {
        $this->authorize('create', SiteSetting::class);

        return SiteSetting::create($request->validated());
    }

    public function update(SiteSettingRequest $request, SiteSetting $siteSetting)
    {
        $this->authorize('update', $siteSetting);

        $siteSetting = $siteSetting->first();
        $backup = $siteSetting;
        $validatedResponse = $request->validated();
        $tempFileFail = null;
        $oldFile = null;
        $newFile = null;

        if (count($validatedResponse) === 0) {
            throw ValidationException::withMessages([
                'invalid_data' => Lang::get("general.invalid_data")
            ]);
        }

        // Check if file is being uploaded, retrieve name of old file and delete it
        // Then store new file and update database
        if ($request->hasFile('site_logo')) {
            if ($siteSetting->site_logo) {
                $oldFile = str_replace(env("APP_URL") . "/storage/site_logo/", "", $siteSetting->site_logo);
            }
            $fileStored = Storage::put('/site_logo', $request->file('site_logo'));
            if (!$fileStored) {
                throw ValidationException::withMessages([
                    'site_logo' => 'Unable to upload logo',
                ]);
            }
            if ($oldFile) {
                $tempFileFail = Storage::get('public/site_logo/' . $oldFile);
                if (!Storage::disk(env("FILESYSTEM_DISK"))->delete('/site_logo/' . $oldFile)) {
                    throw ValidationException::withMessages([
                        'site_logo' => 'Unable to delete old logo',
                    ]);
                }
            }
            $validatedResponse['site_logo'] = Storage::url($fileStored);
        }
        if (!$siteSetting->update($validatedResponse)) {
            $siteSetting->update($backup->toArray());

            if ($tempFileFail && $oldFile) {
                Storage::put('/site_logo/' . $oldFile, $tempFileFail);
            }

            throw ValidationException::withMessages([
                'invalid_data' => Lang::get("siteSettings.failed_update")
            ]);
        }
        return response()->json(
            [
                'success' => 'Site settings updated successfully',
            ],
            200
        );
    }

    public function destroy(SiteSetting $siteSetting)
    {
        $this->authorize('delete', $siteSetting);

        $siteSetting->delete();

        return response()->json();
    }
}
