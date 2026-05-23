<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DemoAccessApproved;
use App\Mail\DemoAccessRejected;
use App\Models\DemoRequest;
use App\Models\DemoUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DemoRequestController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = trim((string) $request->query('status'));

        $demoRequests = DemoRequest::query()
            ->with('demoUser')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('college_name', 'like', "%{$search}%")
                        ->orWhere('admin_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['Pending', 'Approved', 'Rejected', 'Contacted'], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.requests.index', [
            'demoRequests' => $demoRequests,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function approve(DemoRequest $demoRequest): RedirectResponse
    {
        $plainPassword = Str::password(12, true, true, false, false);
        $demoUser = null;

        DB::transaction(function () use ($demoRequest, $plainPassword, &$demoUser): void {
            $username = $this->generateUniqueUsername($demoRequest->college_name);

            $demoUser = DemoUser::updateOrCreate(
                ['request_id' => $demoRequest->id],
                [
                    'username' => $username,
                    'password' => Hash::make($plainPassword),
                    'expiry_date' => now()->addDays(7),
                    'status' => 'Active',
                ]
            );

            $demoRequest->update(['status' => 'Approved']);
        });

        $loginUrl = url('/demo-portal/login');

        try {
            Mail::to($demoRequest->email)->send(
                new DemoAccessApproved($demoRequest->fresh(), $demoUser->fresh(), $plainPassword, $loginUrl)
            );
        } catch (\Throwable $exception) {
            Log::error('Demo approval email failed to send.', [
                'demo_request_id' => $demoRequest->id,
                'email' => $demoRequest->email,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('admin_warning', 'Request approved and credentials created, but the email could not be sent. Demo URL: '.$loginUrl.' | Username: '.$demoUser->username.' | Temporary Password: '.$plainPassword);
        }

        return back()->with('admin_success', 'Demo request approved and credentials sent successfully.');
    }

    public function reject(DemoRequest $demoRequest): RedirectResponse
    {
        $demoRequest->update(['status' => 'Rejected']);

        Mail::to($demoRequest->email)->send(new DemoAccessRejected($demoRequest));

        return back()->with('admin_success', 'Demo request rejected and notification sent.');
    }

    public function markContacted(DemoRequest $demoRequest): RedirectResponse
    {
        $demoRequest->update(['status' => 'Contacted']);

        return back()->with('admin_success', 'Demo request marked as contacted.');
    }

    public function destroy(DemoRequest $demoRequest): RedirectResponse
    {
        $demoRequest->delete();

        return back()->with('admin_success', 'Demo request deleted successfully.');
    }

    protected function generateUniqueUsername(string $collegeName): string
    {
        $base = 'demo_'.Str::of($collegeName)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_');
        $base = Str::limit($base, 24, '');
        $candidate = $base;
        $counter = 1;

        while (DemoUser::where('username', $candidate)->exists()) {
            $candidate = Str::limit($base, 20, '').'_'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
