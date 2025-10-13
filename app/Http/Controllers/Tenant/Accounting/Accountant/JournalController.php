<?php

namespace App\Http\Controllers\Tenant\Accounting\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Accountants\CreateJournalRequest;
use App\Http\Requests\Tenant\Accounting\Accountants\UpdateJournalRequest;
use App\Services\V1\Accounting\JournalService;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function __construct(protected JournalService $service)
    {
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function store(CreateJournalRequest $request)
    {
        return $this->service->store($request);
    }

    public function show(string $id)
    {
        return $this->service->show($id);
    }

    public function update(UpdateJournalRequest $request, string $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(Request $request)
    {
        return $this->service->destroy($request);
    }


}
