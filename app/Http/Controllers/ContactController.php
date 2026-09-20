<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        // Seed unique session token for reCAPTCHA challenge
        session([
            'recaptcha_seed' => bin2hex(random_bytes(16)),
            'recaptcha_loaded_at' => microtime(true),
        ]);

        return view('pages.contact');
    }

    /**
     * Verify reCAPTCHA via AJAX when user clicks checkbox.
     */
    public function verifyRecaptcha(Request $request)
    {
        $seed = session('recaptcha_seed');
        if (!$seed) {
            $seed = bin2hex(random_bytes(16));
            session(['recaptcha_seed' => $seed]);
        }

        // Generate verified token tied to current session and application secret key
        $token = hash_hmac('sha256', session()->getId() . '|' . $seed, config('app.key'));
        
        session([
            'recaptcha_verified_token' => $token,
            'recaptcha_verified_at' => microtime(true),
        ]);

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }

    /**
     * Handle incoming contact message submission.
     */
    public function send(Request $request)
    {
        // Anti-spam Honeypot trap: bots fill hidden fields, humans never do
        if (!empty($request->website_hp_check)) {
            return back()->with('success', __('Cảm ơn bạn đã liên hệ! Tin nhắn của bạn đã được gửi thành công, tôi sẽ phản hồi trong thời gian sớm nhất.'));
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:4000',
            'recaptcha_token' => 'required|string',
        ], [
            'name.required' => __('Vui lòng nhập họ và tên.'),
            'email.required' => __('Vui lòng nhập địa chỉ email.'),
            'email.email' => __('Địa chỉ email không đúng định dạng.'),
            'message.required' => __('Vui lòng nhập nội dung tin nhắn.'),
            'message.min' => __('Nội dung tin nhắn cần tối thiểu 10 ký tự.'),
            'recaptcha_token.required' => __('Please verify that you are not a robot.'),
        ]);

        // Verify reCAPTCHA token against session
        $validToken = session('recaptcha_verified_token');
        if (!$validToken || $request->recaptcha_token !== $validToken) {
            return back()->withInput()->withErrors([
                'recaptcha_token' => __('Please verify that you are not a robot.')
            ]);
        }

        // Anti-bot rapid submission check (must take at least 1.0s to fill form)
        $loadedAt = session('recaptcha_loaded_at');
        if ($loadedAt && (microtime(true) - $loadedAt) < 1.0) {
            return back()->withInput()->withErrors([
                'recaptcha_token' => __('Please verify that you are not a robot.')
            ]);
        }

        // Clean reCAPTCHA session keys
        session()->forget(['recaptcha_verified_token', 'recaptcha_seed', 'recaptcha_loaded_at']);

        // Store contact message
        ContactMessage::create([
            'name' => strip_tags($request->name),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'subject' => $request->subject ? strip_tags($request->subject) : null,
            'message' => strip_tags($request->message),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', __('Cảm ơn bạn đã liên hệ! Tin nhắn của bạn đã được gửi thành công, tôi sẽ phản hồi trong thời gian sớm nhất.'));
    }
}
