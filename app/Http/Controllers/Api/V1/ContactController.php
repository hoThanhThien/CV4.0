<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Submit a contact message via API
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:4000',
        ]);

        $message = ContactMessage::create([
            'name' => strip_tags($validated['name']),
            'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
            'subject' => !empty($validated['subject']) ? strip_tags($validated['subject']) : null,
            'message' => strip_tags($validated['message']),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out! Your message has been received.',
            'data' => [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'created_at' => $message->created_at,
            ],
        ], 201);
    }
}
