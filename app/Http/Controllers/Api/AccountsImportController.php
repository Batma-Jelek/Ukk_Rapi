<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\AccountsImport;
use App\Exports\AccountsTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class AccountsImportController extends Controller
{
    public function downloadTemplate(Request $request)
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat mengunduh template akun.');

        return Excel::download(new AccountsTemplateExport(), 'template-import-akun.xlsx');
    }

    public function __invoke(Request $request): JsonResponse
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat mengimpor akun.');

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $import = new AccountsImport();

        DB::transaction(function () use ($import, $validated): void {
            Excel::import($import, $validated['file']);

            if ($import->importedCount() === 0) {
                throw ValidationException::withMessages([
                    'file' => 'File tidak berisi data akun.',
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil diimpor.',
            'imported_count' => $import->importedCount(),
        ]);
    }
}
