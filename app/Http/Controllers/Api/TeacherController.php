<?php

namespace App\Http\Controllers\Api;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\TeacherCollection;
use App\Http\Requests\TeacherStoreRequest;
use App\Http\Requests\TeacherUpdateRequest;

class TeacherController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Teacher::class);

        $search = $request->get('search', '');

        $teachers = Teacher::search($search)
            ->latest()
            ->paginate();

        return new TeacherCollection($teachers);
    }

    /**
     * @param \App\Http\Requests\TeacherStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherStoreRequest $request)
    {
        $this->authorize('create', Teacher::class);

        $validated = $request->validated();

        $teacher = Teacher::create($validated);

        return new TeacherResource($teacher);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Teacher $teacher)
    {
        $this->authorize('view', $teacher);

        return new TeacherResource($teacher);
    }

    /**
     * @param \App\Http\Requests\TeacherUpdateRequest $request
     * @param \App\Models\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherUpdateRequest $request, Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        $validated = $request->validated();

        $teacher->update($validated);

        return new TeacherResource($teacher);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Teacher $teacher)
    {
        $this->authorize('delete', $teacher);

        $teacher->delete();

        return response()->noContent();
    }
}
