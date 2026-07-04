<?php

namespace Elmasry\StarterKit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Elmasry\StarterKit\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        Contact::create($validated);

        return redirect()->back()->with('success', __('messages.success'));
    }
}
