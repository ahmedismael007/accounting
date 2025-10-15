<?php

namespace App\Http\Controllers\Tenant\Accounting\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Sales\InvoiceRequest;
use App\Models\Tenant\Accounting\Sales\Invoice;
use App\Services\V1\Accounting\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $service)
    {
    }

    public function index(Request $request)
    {
        return response()->json($this->service->index($request));
    }

    public function store(InvoiceRequest $request)
    {
        return response()->json($this->service->store($request));
    }

    public function show(string $id)
    {
        return response()->json($this->service->show($id));
    }

    public function update(InvoiceRequest $request, string $id)
    {
        return response()->json($this->service->update($request, $id));
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);
        return response()->json($this->service->destroy($ids));
    }
}
