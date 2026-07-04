<?php

namespace Elmasry\StarterKit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Elmasry\StarterKit\Models\Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletters,email'],
        ]);

        Newsletter::create($validated);

        return redirect()->back()->with('success', __('messages.subscribe'));
    }
}
