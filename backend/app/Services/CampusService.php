<?php

namespace App\Services;

use App\Models\Campus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CampusService
{
    public function list(Request $request): LengthAwarePaginator
    {
        return Campus::query()
            ->when($request->filled('search'), fn ($query) => $query->where(function ($nested) use ($request): void {
                $nested->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%")
                    ->orWhere('city', 'like', "%{$request->search}%");
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->withCount(['departments', 'students'])
            ->orderBy($request->query('sort', 'created_at'), $request->query('direction', 'desc'))
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Campus
    {
        return Campus::create($data);
    }

    public function update(Campus $campus, array $data): Campus
    {
        $campus->update($data);

        return $campus;
    }

    public function toggleStatus(Campus $campus): Campus
    {
        $campus->update(['status' => $campus->status === 'active' ? 'inactive' : 'active']);

        return $campus;
    }
}
