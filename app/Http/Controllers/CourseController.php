<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Exception;
use Illuminate\Validation\ValidationException;
use Log;
use Storage;

class CourseController extends Controller
{
    public function index()
    {
        return Course::select(['id', 'title', 'slug', 'image', 'description'])->get();
    }

    public function store(CourseRequest $request)
    {
        try {
            $data = $request->validated();
            $fileStored = Storage::put('/courses/' . $data['slug'] . '/', $request->file('image'));
            $data['image'] = Storage::url($fileStored);
            return Course::create($data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw ValidationException::withMessages([
                'error' => ['An error has occured, please report this and try again later, please view the error in Log files'],
            ]);
        }
    }

    public function show(Course $course)
    {
        return $course;
    }

    public function show_create(Course $course): bool
    {
        return true;
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return $course;
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json();
    }
}
