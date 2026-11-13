@php
    $isVi = app()->getLocale() === 'vi';
@endphp

<!-- Floating AI Trigger Pill -->
<button type="button" id="aiChatTrigger" class="ai-chat-trigger" onclick="toggleAiChatDrawer()" aria-label="{{ $isVi ? 'Hỏi trợ lý AI về Hồ Thành Thiện' : 'Ask AI about Ho Thanh Thien' }}" title="{{ $isVi ? 'Trợ lý AI: Hỏi về năng lực & kinh nghiệm của Thiện' : 'AI Assistant: Inquire about Thien\'s background & projects' }}">
    <span class="ai-pulse-dot" aria-hidden="true"></span>
    <span class="ai-trigger-icon"><i class="fas fa-sparkles"></i></span>
    <span class="ai-trigger-text">Ask Thien</span>
    <span class="ai-trigger-badge">AI</span>
</button>

<!-- Slide-out AI Drawer -->
<div id="aiChatDrawer" class="ai-drawer-backdrop" role="dialog" aria-modal="true" aria-label="AI Assistant" style="display: none;" onclick="closeAiChatOnBackdrop(event)">
    <div class="ai-drawer-container" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="ai-drawer-header">
            <div class="ai-header-profile">
                <div class="ai-avatar-wrap">
                    <span class="ai-avatar-initials">&lt;T/&gt;</span>
                    <span class="ai-live-indicator" title="Online"></span>
                </div>
                <div>
                    <div class="ai-header-title">
                        <span>Ask Thien</span>
                        <span class="ai-model-tag">Portfolio RAG Agent</span>
                    </div>
                    <div class="ai-header-subtitle">
                        {{ $isVi ? 'Hỏi đáp trực tiếp về kinh nghiệm, stack & dự án của Thiện' : 'Instant Q&A about Thien\'s experience, stack & projects' }}
                    </div>
                </div>
            </div>
            <button type="button" class="ai-btn-close" onclick="closeAiChatDrawer()" aria-label="{{ $isVi ? 'Đóng hội thoại' : 'Close' }}">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <!-- Suggestion Chips Bar -->
        <div class="ai-chips-bar">
            <div class="ai-chips-label">{{ $isVi ? 'Gợi ý câu hỏi nhanh:' : 'Suggested inquiries:' }}</div>
            <div class="ai-chips-scroll">
                <button type="button" class="ai-chip" onclick="askAiPreset('{{ $isVi ? 'Thiện có kinh nghiệm thực tế gì?' : 'What is Thien\'s work experience?' }}')">
                    <i class="fas fa-briefcase"></i> {{ $isVi ? 'Kinh nghiệm làm việc' : 'Work experience' }}
                </button>
                <button type="button" class="ai-chip" onclick="askAiPreset('{{ $isVi ? 'Dự án AI/RAG TourRist giải quyết bài toán gì?' : 'What does the RAG TourRist project do?' }}')">
                    <i class="fas fa-robot"></i> {{ $isVi ? 'Dự án AI TourRist' : 'RAG TourRist Project' }}
                </button>
                <button type="button" class="ai-chip" onclick="askAiPreset('{{ $isVi ? 'Trình độ học vấn và GPA của Thiện?' : 'What is Thien\'s education and GPA?' }}')">
                    <i class="fas fa-graduation-cap"></i> {{ $isVi ? 'Học vấn & GPA UTH' : 'Education & GPA' }}
                </button>
                <button type="button" class="ai-chip" onclick="askAiPreset('{{ $isVi ? 'Tech stack và kỹ năng cốt lõi của Thiện?' : 'What is Thien\'s core tech stack?' }}')">
                    <i class="fas fa-code"></i> {{ $isVi ? 'Tech Stack cốt lõi' : 'Core Tech Stack' }}
                </button>
                <button type="button" class="ai-chip" onclick="askAiPreset('{{ $isVi ? 'Làm sao để liên hệ phỏng vấn Thiện?' : 'How to contact Thien for an interview?' }}')">
                    <i class="fas fa-envelope"></i> {{ $isVi ? 'Liên hệ tuyển dụng' : 'Contact for hire' }}
                </button>
            </div>
        </div>

        <!-- Messages Stream -->
        <div class="ai-chat-body" id="aiChatMessages">
            <!-- Bot Greeting -->
            <div class="ai-message bot">
                <div class="ai-message-avatar">&lt;T/&gt;</div>
                <div class="ai-message-bubble">
                    <p>
                        @if($isVi)
                            Xin chào! Tôi là trợ lý AI được tích hợp trực tiếp trên portfolio của <strong>Hồ Thành Thiện</strong>. Bạn có thể hỏi tôi bất kỳ điều gì về kinh nghiệm dự án, tech stack (Laravel, Spring Boot, React, RAG), thành tích học tập UTH hoặc thông tin tuyển dụng!
                        @else
                            Hello! I am the portfolio AI agent for <strong>Ho Thanh Thien</strong>. Feel free to ask me anything about his projects, tech stack (Laravel, Spring Boot, React, RAG), UTH academic credentials, or hire inquiry!
                        @endif
                    </p>
                    <div class="ai-message-time">{{ $isVi ? 'Vừa xong' : 'Just now' }}</div>
                </div>
            </div>
        </div>

        <!-- Input Bar -->
        <form class="ai-chat-input-bar" onsubmit="handleAiSubmit(event)">
            <input type="text" id="aiUserInput" class="ai-chat-input" placeholder="{{ $isVi ? 'Nhập câu hỏi cho trợ lý AI...' : 'Ask a question about Thien...' }}" autocomplete="off">
            <button type="submit" id="aiSendBtn" class="ai-chat-send" aria-label="{{ $isVi ? 'Gửi' : 'Send' }}">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

