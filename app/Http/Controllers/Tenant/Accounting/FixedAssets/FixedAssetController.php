<?php

namespace App\Http\Controllers\Tenant\Accounting\FixedAssets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\FixedAssets\CreateFixedAssetRequest;
use App\Http\Requests\Tenant\Accounting\FixedAssets\UpdateFixedAssetRequest;
use App\Services\V1\Accounting\FixedAssetService;
use Illuminate\Http\Request;

class FixedAssetController extends Controller
{
    public function __construct(
        protected FixedAssetService $service
    )
    {
    }

    public function index(Request $request)
    {
        $data = $this->service->index($request);
        return response()->json($data, 200);
    }

    public function store(CreateFixedAssetRequest $request)
    {
        $this->service->store($request->validated());
        return response()->json(['message' => trans('crud.created')], 201);
    }

    public function show(string $id)
    {
        $data = $this->service->show($id);
        return response()->json(['data' => $data], 200);
    }

    public function update(UpdateFixedAssetRequest $request, string $id)
    {
        $this->service->update($request->validated(), $id);
        return response()->json(['message' => trans('crud.updated')], 200);
    }

    public function destroy(Request $request)
    {
        $this->service->destroy($request->input('ids'));
        return response()->json(['message' => trans('crud.deleted')]);
    }
}
