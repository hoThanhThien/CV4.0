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
        return view('pages.contact');
    }

    /**
     * Generate dynamic visual SVG Captcha and store in session.
     */
    public function captcha()
    {
        // 5 random clear characters (avoiding ambiguous 0/O, 1/I)
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        session(['contact_captcha' => $code]);

        // Generate stylized SVG with noise lines and skewed text
        $width = 160;
        $height = 48;
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">';
        $svg .= '<defs>';
        $svg .= '<linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">';
        $svg .= '<stop offset="0%" stop-color="#f8fafc"/>';
        $svg .= '<stop offset="100%" stop-color="#e2e8f0"/>';
        $svg .= '</linearGradient>';
        $svg .= '</defs>';
        
        // Background
        $svg .= '<rect width="'.$width.'" height="'.$height.'" rx="8" fill="url(#bgGrad)"/>';
        $svg .= '<rect width="'.($width-2).'" height="'.($height-2).'" x="1" y="1" rx="7" fill="none" stroke="#cbd5e1" stroke-width="1"/>';

        // Noise lines
        for ($i = 0; $i < 4; $i++) {
            $x1 = random_int(5, 40);
            $y1 = random_int(5, $height - 5);
            $x2 = random_int($width - 40, $width - 5);
            $y2 = random_int(5, $height - 5);
            $color = ['#c7d2fe', '#a5b4fc', '#bae6fd', '#cbd5e1'][random_int(0, 3)];
            $svg .= '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="'.random_int(1, 2).'"/>';
        }

        // Noise dots
        for ($i = 0; $i < 25; $i++) {
            $cx = random_int(5, $width - 5);
            $cy = random_int(5, $height - 5);
            $r = random_int(1, 2);
            $svg .= '<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$r.'" fill="#94a3b8" opacity="0.4"/>';
        }

        // Render characters
        $colors = ['#4338ca', '#6366f1', '#0284c7', '#0f172a', '#7c3aed'];
        $charX = 18;
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            $charColor = $colors[$i % count($colors)];
            $rotate = random_int(-15, 15);
            $charY = random_int(30, 35);
            
            $svg .= '<text x="'.$charX.'" y="'.$charY.'" fill="'.$charColor.'" font-family="JetBrains Mono, monospace, sans-serif" font-weight="900" font-size="24" transform="rotate('.$rotate.', '.$charX.', '.$charY.')" letter-spacing="3">';
            $svg .= $char;
            $svg .= '</text>';
            $charX += 26;
        }

        $svg .= '</svg>';

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Handle incoming contact message submission.
     */
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:4000',
            'captcha' => 'required|string',
        ], [
            'name.required' => __('Vui lòng nhập họ và tên.'),
            'email.required' => __('Vui lòng nhập địa chỉ email.'),
            'email.email' => __('Địa chỉ email không đúng định dạng.'),
            'message.required' => __('Vui lòng nhập nội dung tin nhắn.'),
            'message.min' => __('Nội dung tin nhắn cần tối thiểu 10 ký tự.'),
            'captcha.required' => __('Vui lòng nhập mã bảo vệ (Captcha).'),
        ]);

        // Verify captcha
        $sessionCaptcha = session('contact_captcha');
        if (!$sessionCaptcha || strtoupper(trim($request->captcha)) !== strtoupper(trim($sessionCaptcha))) {
            return back()->withInput()->withErrors([
                'captcha' => __('Mã bảo vệ (Captcha) không chính xác. Vui lòng thử lại.')
            ]);
        }

        // Clear captcha once validated
        session()->forget('contact_captcha');

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
