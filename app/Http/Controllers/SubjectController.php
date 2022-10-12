<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Requests\SubjectStoreRequest;
use App\Http\Requests\SubjectUpdateRequest;

class SubjectController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Subject::class);

        $search = $request->get('search', '');

        $subjects = Subject::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.subjects.index', compact('subjects', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Subject::class);

        return view('app.subjects.create');
    }

    /**
     * @param \App\Http\Requests\SubjectStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectStoreRequest $request)
    {
        $this->authorize('create', Subject::class);

        $validated = $request->validated();

        $subject = Subject::create($validated);

        return redirect()
            ->route('subjects.edit', $subject)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subject $subject)
    {
        $this->authorize('view', $subject);

        return view('app.subjects.show', compact('subject'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Subject $subject)
    {
        $this->authorize('update', $subject);

        return view('app.subjects.edit', compact('subject'));
    }

    /**
     * @param \App\Http\Requests\SubjectUpdateRequest $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectUpdateRequest $request, Subject $subject)
    {
        $this->authorize('update', $subject);

        $validated = $request->validated();

        $subject->update($validated);

        return redirect()
            ->route('subjects.edit', $subject)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Subject $subject)
    {
        $this->authorize('delete', $subject);

        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
