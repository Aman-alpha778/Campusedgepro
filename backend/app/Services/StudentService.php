<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    public function list(Request $request): LengthAwarePaginator
    {
        return Student::query()
            ->with(['user', 'campus', 'department', 'course'])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($nested) use ($request): void {
                $nested->where('roll_number', 'like', "%{$request->search}%")
                    ->orWhere('registration_number', 'like', "%{$request->search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%"));
            }))
            ->when($request->filled('campus_id'), fn ($query) => $query->where('campus_id', $request->campus_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data): Student {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password'] ?? 'password'),
                'status' => $data['status'] ?? 'active',
            ]);

            if ($role = Role::where('name', 'Student')->first()) {
                $user->assignRole($role);
            }

            return Student::create($this->studentPayload($data, $user->id));
        });
    }

    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data): Student {
            $student->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'status' => $data['status'] ?? $student->status,
            ]);

            $student->update($this->studentPayload($data, $student->user_id));

            return $student->refresh();
        });
    }

    private function studentPayload(array $data, int $userId): array
    {
        return [
            'user_id' => $userId,
            'campus_id' => $data['campus_id'],
            'department_id' => $data['department_id'],
            'course_id' => $data['course_id'],
            'roll_number' => $data['roll_number'],
            'registration_number' => $data['registration_number'],
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'guardian_name' => $data['guardian_name'] ?? null,
            'guardian_phone' => $data['guardian_phone'] ?? null,
            'admission_date' => $data['admission_date'],
            'status' => $data['status'] ?? 'active',
        ];
    }
}
