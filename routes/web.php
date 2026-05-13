<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$pages = [
    '/' => 'index',
    'index.html' => 'index',
    'about' => 'about',
    'about.html' => 'about',
    'ads' => 'ads',
    'ads.html' => 'ads',
    'blog' => 'blog',
    'blog.html' => 'blog',
    'contact' => 'contact',
    'contact.html' => 'contact',
    'demo' => 'demo',
    'demo.html' => 'demo',
    'features' => 'features',
    'features.html' => 'features',
    'landing' => 'landing',
    'landing.html' => 'landing',
    'modules' => 'modules',
    'modules.html' => 'modules',
    'pricing' => 'pricing',
    'pricing.html' => 'pricing',
    'blog/benefits-of-college-erp' => 'blog.benefits-of-college-erp',
    'blog/benefits-of-college-erp.html' => 'blog.benefits-of-college-erp',
    'blog/best-erp-for-colleges-in-india' => 'blog.best-erp-for-colleges-in-india',
    'blog/best-erp-for-colleges-in-india.html' => 'blog.best-erp-for-colleges-in-india',
    'blog/manage-attendance-digitally' => 'blog.manage-attendance-digitally',
    'blog/manage-attendance-digitally.html' => 'blog.manage-attendance-digitally',
    'docs' => 'docs.index',
    'docs/index.html' => 'docs.index',
    'docs/admin-guide' => 'docs.admin-guide',
    'docs/admin-guide.html' => 'docs.admin-guide',
    'docs/api' => 'docs.api',
    'docs/api.html' => 'docs.api',
    'docs/crm-admissions' => 'docs.crm-admissions',
    'docs/crm-admissions.html' => 'docs.crm-admissions',
    'docs/faq' => 'docs.faq',
    'docs/faq.html' => 'docs.faq',
    'docs/fee-management' => 'docs.fee-management',
    'docs/fee-management.html' => 'docs.fee-management',
    'docs/student-module' => 'docs.student-module',
    'docs/student-module.html' => 'docs.student-module',
    'modules/attendance' => 'modules.attendance',
    'modules/attendance.html' => 'modules.attendance',
    'modules/exams' => 'modules.exams',
    'modules/exams.html' => 'modules.exams',
    'modules/fees' => 'modules.fees',
    'modules/fees.html' => 'modules.fees',
    'modules/reports' => 'modules.reports',
    'modules/reports.html' => 'modules.reports',
    'modules/staff-management' => 'modules.staff-management',
    'modules/staff-management.html' => 'modules.staff-management',
    'modules/student-management' => 'modules.student-management',
    'modules/student-management.html' => 'modules.student-management',
];

foreach ($pages as $uri => $view) {
    Route::view($uri, $view);
}

Route::get('/checkout', function (Request $request) {
    $plans = [
        'basic' => [
            'name' => 'Basic',
            'price' => '25,000',
            'students' => 'Up to 1,000 students',
            'accent' => 'checkout-plan-basic',
            'summary' => 'Best for colleges starting their first digital administration rollout.',
            'features' => [
                'Student records',
                'Attendance tracking',
                'Basic fee reports',
                'Standard onboarding',
            ],
        ],
        'professional' => [
            'name' => 'Professional',
            'price' => '50,000',
            'students' => 'Up to 3,000 students',
            'accent' => 'checkout-plan-professional',
            'summary' => 'Designed for growing institutes that need admissions, finance, and academic visibility in one platform.',
            'features' => [
                'Admissions CRM',
                'Exams and results',
                'Advanced reports',
                'Priority onboarding',
            ],
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price' => '90,000',
            'students' => 'Multi-campus scale',
            'accent' => 'checkout-plan-enterprise',
            'summary' => 'Built for complex institutions that need integrations, custom roles, and dedicated success support.',
            'features' => [
                'API access',
                'Custom workflows',
                'Multi-campus support',
                'Dedicated success manager',
            ],
        ],
    ];

    $selectedPlan = strtolower((string) $request->query('plan', 'professional'));
    $plan = $plans[$selectedPlan] ?? $plans['professional'];

    return view('checkout', [
        'plan' => $plan,
        'planKey' => array_key_exists($selectedPlan, $plans) ? $selectedPlan : 'professional',
        'plans' => $plans,
    ]);
})->name('checkout');

Route::get('/payment-gateway', fn (Request $request) => redirect()->route('checkout', ['plan' => $request->query('plan', 'professional')]));

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
