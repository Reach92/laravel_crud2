<?php

namespace App\Http\Controllers\Api;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentCollection;

class SubjectStudentsController extends Controller
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

        $students = $subject
            ->students()
            ->search($search)
            ->latest()
            ->paginate();

        return new StudentCollection($students);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Subject $subject)
    {
        $this->authorize('create', Student::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email'],
            'major' => ['required', 'max:255', 'string'],
        ]);

        $student = $subject->students()->create($validated);

        return new StudentResource($student);
    }
}
