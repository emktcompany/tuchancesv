<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportRequest;
use App\Http\Requests\Admin\Programs\CreateRequest;
use App\Http\Requests\Admin\Programs\UpdateRequest;
use App\Http\Resources\ProgramResource;
use App\Imports\ImportProgramParticipants;
use App\TuChance\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramsController extends Controller
{
    /**
     * Program model
     * @var \App\TuChance\Models\Program
     */
    protected $programs;

    /**
     * Create a new controller instance
     * @param  \App\TuChance\Models\Program $programs
     * @return void
     */
    public function __construct(Program $programs)
    {
        $this->programs = $programs;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ResourceCollection
     */
    public function index(Request $request)
    {
        $query = $this->programs->withCount('participants');

        if ($request->has('term')) {
            $query->search($request->get('term'), null, true, true);
        } else {
            $query->orderBy('name');
        }

        $programs = $query->paginate(10);

        return ProgramResource::collection($programs);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Http\Requests\Admin\Programs\CreateRequest  $request
     * @return \App\Http\Resources\ProgramResource
     */
    public function store(CreateRequest $request)
    {
        $program = $this->programs->newInstance();
        $program->fill($request->all());
        $program->save();

        return new ProgramResource($program->fresh());
    }

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \App\Http\Resources\ProgramResource
     */
    public function show(Request $request, $id)
    {
        $program = $this->programs->with('participants')->findOrFail($id);
        return new ProgramResource($program);
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\Admin\Programs\UpdateRequest  $request
     * @param  int                                             $id
     * @return \App\Http\Resources\ProgramResource
     */
    public function update(UpdateRequest $request, $id)
    {
        $program = $this->programs->findOrFail($id);
        $program->fill($request->all());
        $program->save();

        return new ProgramResource($program->fresh());
    }

    /**
     * Remove the specified resource from storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \App\Http\Resources\ProgramResource
     */
    public function destroy(Request $request, $id)
    {
        $program = $this->programs->findOrFail($id);
        $program->delete();

        return new ProgramResource($program);
    }

    /**
     * Import given file to database
     * @param  \App\Http\Requests\Admin\ImportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import($id, ImportRequest $request)
    {
        $program = $this->programs->findOrFail($id);

        $job = new ImportProgramParticipants($program);
        $job->import($request->file('file'));

        return new JsonResponse([
            'success' => $job->wasSuccessful(),
            'message' => $job->getResult(),
        ], $job->wasSuccessful() ? 200 : 422);
    }

    /**
     * Toggle resource visibility
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \App\Http\Resources\EmailTemplateResource
     */
    public function toggle(Request $request, $id)
    {
        $program             = $this->programs->findOrFail($id);
        $program->is_active  = !$program->is_active;
        $program->timestamps = false;
        $program->save();

        return new ProgramResource($program->fresh());
    }
}
