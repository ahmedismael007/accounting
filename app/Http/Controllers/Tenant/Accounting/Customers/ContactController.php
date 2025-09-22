<?php

namespace App\Http\Controllers\Tenant\Accounting\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Customers\ContactRequest;
use App\Models\Tenant\Accounting\Customers\Contact;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();
        $data = $this->applyQuery($request, $query);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        Contact::create($request->validated());
        return response()->json(['message' => 'تم إضافة جهة الاتصال بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);

        return response()->json(['data' => $contact], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, string $id)
    {
        $contact = Contact::find($id);

        $contact->update($request->validated());

        return response()->json(['message' => 'تم تعديل جهة الاتصال بنجاح.'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        Contact::destroy($ids);

        return response()->json(['message' => 'تم حذف جهة الاتصال بنجاح.']);
    }
}
