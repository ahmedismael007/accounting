<?php

namespace App\Services\v1;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Spatie\MediaLibrary\HasMedia;

class PdfService
{
    public function generate_PDF(
        string $view,
        array $data,
        string $file_type,
        string $id,
        ?HasMedia $model = null,
        string $collection
    ): void {
        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'tajawal',
                'margin_top' => 10,
                'margin_bottom' => 20,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            $footerHtml = '
            <table width="100%" style="color: #94a3b8; font-size: 10pt;">
                <tr>
                    <td width="50%">{PAGENO} / {nbpg}</td>
                    <td width="50%" align="left">' . htmlspecialchars($file_type) . ' - ' . htmlspecialchars($id) . '</td>
                </tr>
            </table>';

            $mpdf->SetFooter($footerHtml);


            $html = view($view, $data)->render();
            $mpdf->WriteHTML($html);

            $filename = $this->generate_file_name($data['company']['name'], $file_type, $id);
            $tempPath = storage_path('app/private/temp/' . $data['company']['id'] . '/' . $filename);

            Storage::makeDirectory(storage_path('app/private/temp/' . $data['company']['id']));

            $directory = dirname($tempPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $mpdf->Output($tempPath, 'F');

            $model->addMedia($tempPath)
                ->usingFileName($filename)
                ->toMediaCollection($collection);
        } catch (\Exception $e) {
            Log::error('PDF Generation Failed: ' . $e->getMessage());

            abort(422, 'تعذر توليد الملف.');
        }
    }

    public function generate_file_name(string $company_name, string $file_type, string $id)
    {
        $timestamp = now()->format('Ymd_His');
        $file_name =  $company_name . '_' . $file_type . '_' . $id . $timestamp . '.pdf';

        return $file_name;
    }
}
