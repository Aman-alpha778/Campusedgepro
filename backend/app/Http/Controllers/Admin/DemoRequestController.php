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
    private const DEMO_ACCESS_DAYS = 3;

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = trim((string) $request->query('status'));
        $searchTerms = $search === ''
            ? []
            : preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        $demoRequests = DemoRequest::query()
            ->with('demoUser')
            ->when($searchTerms !== [], function ($query) use ($searchTerms): void {
                foreach ($searchTerms as $term) {
                    $likeTerm = "%{$term}%";

                    $query->where(function ($nested) use ($likeTerm): void {
                        $nested
                            ->where('college_name', 'like', $likeTerm)
                            ->orWhere('admin_name', 'like', $likeTerm)
                            ->orWhere('email', 'like', $likeTerm)
                            ->orWhere('phone', 'like', $likeTerm)
                            ->orWhere('student_strength', 'like', $likeTerm)
                            ->orWhere('requirements', 'like', $likeTerm)
                            ->orWhere('status', 'like', $likeTerm)
                            ->orWhereHas('demoUser', function ($demoUserQuery) use ($likeTerm): void {
                                $demoUserQuery->where('username', 'like', $likeTerm);
                            });
                    });
                }
            })
            ->when(in_array($status, ['Pending', 'Approved', 'Rejected'], true), function ($query) use ($status): void {
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
        $demoUser = $this->createOrRefreshDemoUser($demoRequest, $plainPassword);

        $loginUrl = url('/demo-portal/login');

        try {
            $this->sendApprovedAccessEmail($demoRequest, $demoUser, $plainPassword, $loginUrl);
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

    public function resendAccess(DemoRequest $demoRequest): RedirectResponse
    {
        if ($demoRequest->status !== 'Approved' && ! $demoRequest->demoUser) {
            return back()->with('admin_warning', 'Only approved demo requests can receive access credentials.');
        }

        $plainPassword = Str::password(12, true, true, false, false);
        $demoUser = $this->createOrRefreshDemoUser($demoRequest, $plainPassword);
        $loginUrl = url('/demo-portal/login');

        try {
            $this->sendApprovedAccessEmail($demoRequest, $demoUser, $plainPassword, $loginUrl);
        } catch (\Throwable $exception) {
            Log::error('Demo access resend email failed to send.', [
                'demo_request_id' => $demoRequest->id,
                'email' => $demoRequest->email,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('admin_warning', 'Credentials were refreshed, but the email could not be sent. Demo URL: '.$loginUrl.' | Username: '.$demoUser->username.' | Temporary Password: '.$plainPassword);
        }

        return back()->with('admin_success', 'Demo access credentials resent successfully.');
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

    protected function createOrRefreshDemoUser(DemoRequest $demoRequest, string $plainPassword): DemoUser
    {
        return DB::transaction(function () use ($demoRequest, $plainPassword): DemoUser {
            $demoUser = $demoRequest->demoUser;

            if ($demoUser) {
                $demoUser->update([
                    'password' => Hash::make($plainPassword),
                    'expiry_date' => now()->addDays(self::DEMO_ACCESS_DAYS),
                    'status' => 'Active',
                ]);
            } else {
                $demoUser = DemoUser::create([
                    'request_id' => $demoRequest->id,
                    'username' => $this->generateUniqueUsername($demoRequest->college_name),
                    'password' => Hash::make($plainPassword),
                    'expiry_date' => now()->addDays(self::DEMO_ACCESS_DAYS),
                    'status' => 'Active',
                ]);
            }

            $demoRequest->update(['status' => 'Approved']);

            return $demoUser;
        });
    }

    protected function sendApprovedAccessEmail(
        DemoRequest $demoRequest,
        DemoUser $demoUser,
        string $plainPassword,
        string $loginUrl
    ): void {
        Mail::to($demoRequest->email)->send(
            new DemoAccessApproved($demoRequest->fresh(), $demoUser->fresh(), $plainPassword, $loginUrl)
        );
    }
}
