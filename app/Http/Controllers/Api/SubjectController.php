<?php

namespace App\Http\Controllers\Api;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\SubjectCollection;
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
            ->paginate();

        return new SubjectCollection($subjects);
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

        return new SubjectResource($subject);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subject $subject)
    {
        $this->authorize('view', $subject);

        return new SubjectResource($subject);
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

        return new SubjectResource($subject);
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

        return response()->noContent();
    }
}
