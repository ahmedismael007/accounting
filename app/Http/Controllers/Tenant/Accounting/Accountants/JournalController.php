<?php

namespace App\Http\Controllers\Tenant\Accounting\Accountants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Accountants\JournalRequest;
use App\Models\Tenant\Accounting\Accountants\Journal;
use App\Services\v1\PdfService;
use App\Services\v1\ReferenceValueFormatterService;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Journal::where('type', 'manual')->with('journal_line_items');
        $data = $this->applyQuery($request, $query);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JournalRequest $request, PdfService $pdf_service, ReferenceValueFormatterService $formatter)
    {
        $user = Auth::user();
        $user_with_email = $user['first_name'] . ' ' . $user['last_name'] . ' - ' . $user['email'];

        DB::beginTransaction();

        try {
            $journal = Journal::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'notes' => $request->notes,
            ]);

            $lineItems = collect($request->journal_line_items)->map(function ($item) use ($user_with_email, $journal) {
                return [
                    'user'           => $user_with_email,
                    'account_id'     => $item['account_id'],
                    'description'    => $item['description'],
                    'currency'       => $item['currency'],
                    'exchange_rate'  => $item['exchange_rate'] ?? 1,
                    'debit'          => $item['debit'],
                    'credit'         => $item['credit'],
                    'debit_dc'       => $item['debit_dc'],
                    'credit_dc'      => $item['credit_dc'],
                    'tax_rate_id'       => $item['tax_rate_id'] ?? 0,
                    'contact_id'     => $item['contact_id'] ?? null,
                    'project_id'     => $item['project_id'] ?? null,
                    'branch_id'      => $item['branch_id'] ?? null,
                    'cost_center_id' => $item['cost_center_id'] ?? null,
                    'source_type'    => 'قيد يدوي',
                    'source_id'      => $journal['id'],
                ];
            });

            $totalDebit = $lineItems->sum('debit');
            $totalCredit = $lineItems->sum('credit');

            if ($totalDebit !== $totalCredit) {
                return response()->json([
                    'message' => 'يجب أن يكون مجموع المدين والدائن متساويًا.',
                ], 422);
            }

            $journal->journal_line_items()->createMany($lineItems);

            $company = tenant();

            $formatted_line_items = $formatter->format($lineItems);

            $total_debit = collect($lineItems)->sum('debit_dc');
            $total_credit = collect($lineItems)->sum('credit_dc');

            $pdf_service->generate_PDF('pdf.journal', ['company' => $company, 'notes' => $request->input('notes'), 'line_items' => $formatted_line_items, 'date' => $request->input('date'), 'id' => $journal['id'], 'total_debit' => $total_debit, 'total_credit' => $total_credit], 'Manual_Journal', $journal['id'], $journal, 'manual_journal');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'تعذر إنشاء القيد في الوقت الحالي.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $journal = Journal::with(
            'journal_line_items',
            'media'
        )->findOrFail($id);

        return response()->json(['data' => $journal], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(JournalRequest $request, string $id)
    {
        $user = Auth::user();
        $user_with_email = $user['first_name'] . ' ' . $user['last_name'] . ' - ' . $user['email'];

        DB::beginTransaction();

        try {
            $journal = Journal::findOrFail($id);

            $journal->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'notes' => $request->notes,
            ]);

            $journal->journal_line_items()->delete();

            $lineItems = collect($request->journal_line_items)->map(function ($item) use ($user_with_email, $journal) {
                return [
                    'user'           => $user_with_email,
                    'account_id'     => $item['account_id'],
                    'description'    => $item['description'],
                    'currency'       => $item['currency'],
                    'exchange_rate'  => $item['exchange_rate'] ?? 1,
                    'debit'          => $item['debit'],
                    'credit'         => $item['credit'],
                    'debit_dc'       => $item['debit_dc'],
                    'credit_dc'      => $item['credit_dc'],
                    'tax_rate_id'       => $item['tax_rate_id'] ?? 0,
                    'contact_id'     => $item['contact_id'] ?? null,
                    'project_id'     => $item['project_id'] ?? null,
                    'branch_id'      => $item['branch_id'] ?? null,
                    'cost_center_id' => $item['cost_center_id'] ?? null,
                    'source_type'    => 'قيد يدوي',
                    'source_id'      => $journal->id,
                ];
            });

            $totalDebit = $lineItems->sum('debit');
            $totalCredit = $lineItems->sum('credit');

            if ($totalDebit !== $totalCredit) {
                DB::rollBack();
                return response()->json([
                    'message' => 'يجب أن يكون مجموع المدين والدائن متساويًا.',
                ], 422);
            }

            $journal->journal_line_items()->createMany($lineItems);

            DB::commit();

            return response()->json([
                'message' => 'تم تحديث القيد بنجاح.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'تعذر تحديث القيد في الوقت الحالي.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['error' => 'تنسيق الطلب غير صالح أو القائمة فارغة.'], 422);
        }

        $journals = Journal::whereIn('id', $ids)->get();

        foreach ($journals as $journal) {
            if ($journal->type === 'auto') {
                return response()->json(['message' => 'يوجد قيد خاص بالنظام ولا يمكن حذفه.'], 422);
            }
        }

        Journal::destroy($ids);

        return response()->json(['message' => 'تم حذف القيود بنجاح.']);
    }
}
