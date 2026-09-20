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
        session([
            'recaptcha_loaded_at' => microtime(true),
        ]);

        return view('pages.contact');
    }

    /**
     * Handle incoming contact message submission with reCAPTCHA v3.
     */
    public function send(Request $request)
    {
        // 1. Anti-spam Honeypot: bots fill hidden fields, humans never do
        if (!empty($request->website_hp_check)) {
            // Silently drop bot submission with success message
            return back()->with('success', __('Cảm ơn bạn đã liên hệ! Tin nhắn của bạn đã được gửi thành công, tôi sẽ phản hồi trong thời gian sớm nhất.'));
        }

        // 2. Validate form inputs
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:4000',
        ], [
            'name.required' => __('Vui lòng nhập họ và tên.'),
            'email.required' => __('Vui lòng nhập địa chỉ email.'),
            'email.email' => __('Địa chỉ email không đúng định dạng.'),
            'message.required' => __('Vui lòng nhập nội dung tin nhắn.'),
            'message.min' => __('Nội dung tin nhắn cần tối thiểu 10 ký tự.'),
        ]);

        // 3. reCAPTCHA v3 Verification
        $secretKey = config('services.recaptcha.secret_key');
        $recaptchaToken = $request->input('g-recaptcha-response');

        if (!empty($secretKey)) {
            // Verify via official Google reCAPTCHA v3 API
            if (empty($recaptchaToken)) {
                return back()->withInput()->withErrors([
                    'recaptcha' => __('Spam detection triggered. Please try again.')
                ]);
            }

            try {
                $response = \Illuminate\Support\Facades\Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $recaptchaToken,
                    'remoteip' => $request->ip(),
                ]);

                $result = $response->json();
                $minScore = (float) config('services.recaptcha.min_score', 0.5);

                if (!isset($result['success']) || !$result['success'] || ($result['score'] ?? 0) < $minScore) {
                    return back()->withInput()->withErrors([
                        'recaptcha' => __('Spam detection triggered. Please try again.')
                    ]);
                }
            } catch (\Exception $e) {
                // If Google service times out or errors, proceed safely with honeypot fallback
                \Illuminate\Support\Facades\Log::warning('reCAPTCHA v3 verification error: ' . $e->getMessage());
            }
        } else {
            // Local / Standalone mode: Timing-based anti-bot protection (must take > 1.0s to fill form)
            $loadedAt = session('recaptcha_loaded_at');
            if ($loadedAt && (microtime(true) - $loadedAt) < 1.0) {
                return back()->withInput()->withErrors([
                    'recaptcha' => __('Spam detection triggered. Please try again.')
                ]);
            }
        }

        // Reset loaded time
        session()->forget('recaptcha_loaded_at');

        // 4. Store contact message
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
