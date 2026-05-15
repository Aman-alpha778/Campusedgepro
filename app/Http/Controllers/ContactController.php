<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Form submitted successfully'
        ]);
    }
}