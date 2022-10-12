<?php

namespace App\Http\Controllers\Api;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\TeacherCollection;

class SubjectTeachersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Subject $subject)
    {
        $this->authorize('view', $subject);

        $search = $request->get('search', '');

        $teachers = $subject
            ->teachers()
            ->search($search)
            ->latest()
            ->paginate();

        return new TeacherCollection($teachers);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Subject $subject)
    {
        $this->authorize('create', Teacher::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email'],
            'job' => ['required', 'max:255', 'string'],
        ]);

        $teacher = $subject->teachers()->create($validated);

        return new TeacherResource($teacher);
    }
}
