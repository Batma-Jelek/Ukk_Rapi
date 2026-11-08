<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccountsImport implements ToCollection, WithHeadingRow
{
    private int $importedCount = 0;

    public function collection(Collection $rows): void
    {
        $validRows = [];
        $errors = [];
        $seenUsernames = [];
        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;
            $row = $row->toArray();

            if (collect($row)->filter(fn (mixed $value): bool => filled($value))->isEmpty()) {
                continue;
            }

            $validator = Validator::make($row, [
                'fullname' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users,username'],
                'password' => ['required', 'string', 'min:6', 'max:255'],
                'role_id' => ['required', 'integer', 'exists:roles,id'],
                'phone' => ['nullable', 'string', 'max:20'],
            ]);

            if ($validator->fails()) {
                $errors['file'][] = "Baris {$rowNumber}: ".implode(' ', $validator->errors()->all());
                continue;
            }

            $validated = $validator->validated();
            $username = $validated['username'];

            if (isset($seenUsernames[$username])) {
                $errors['file'][] = "Baris {$rowNumber}: Username {$username} muncul lebih dari sekali.";
                continue;
            }

            $seenUsernames[$username] = true;
            $validRows[] = $validated;
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        foreach ($validRows as $row) {
            User::create([
                'fullname' => $row['fullname'],
                'username' => $row['username'],
                'password' => $row['password'],
                'role_id' => $row['role_id'],
                'phone_number' => $row['phone'] ?? '',
            ]);

            $this->importedCount++;
        }
    }

    public function importedCount(): int
    {
        return $this->importedCount;
    }
}
