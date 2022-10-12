<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
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
            ->paginate(5)
            ->withQueryString();

        return view('app.teachers.index', compact('teachers', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Teacher::class);

        $subjects = Subject::pluck('name', 'id');

        return view('app.teachers.create', compact('subjects'));
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

        return redirect()
            ->route('teachers.edit', $teacher)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Teacher $teacher)
    {
        $this->authorize('view', $teacher);

        return view('app.teachers.show', compact('teacher'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        $subjects = Subject::pluck('name', 'id');

        return view('app.teachers.edit', compact('teacher', 'subjects'));
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

        return redirect()
            ->route('teachers.edit', $teacher)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('teachers.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
