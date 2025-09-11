<?php

namespace App\Http\Controllers\Tenant\Accounting\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Projects\ProjectRequest;
use App\Models\Tenant\Accounting\Projects\Project;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();
        $data = $this->applyQuery($request, $query);

        return response()->json($data, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        Project::create($request->validated());
        return response()->json(['message' => 'تم إضافة المشروع بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);

        return response()->json(['data' => $project], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, string $id)
    {
        $project = Project::find($id);

        $project->update($request->validated());

        return response()->json(['message' => 'تم تعديل المشروع بنجاح.'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        Project::destroy($ids);

        return response()->json(['message' => 'تم حذف المشروع بنجاح.']);
    }
}
