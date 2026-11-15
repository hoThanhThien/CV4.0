@php
    $isVi = app()->getLocale() === 'vi';
@endphp

<div id="cmdPalette" class="cmd-palette-backdrop" role="dialog" aria-modal="true" aria-label="Command Palette" style="display: none;">
    <div class="cmd-palette-box">
        <div class="cmd-palette-header">
            <i class="fas fa-search cmd-search-icon" aria-hidden="true"></i>
            <input type="text" id="cmdInput" class="cmd-input" placeholder="{{ $isVi ? 'Tìm kiếm trang, dự án, hành động hoặc gõ lệnh...' : 'Search sections, projects, actions or type a command...' }}" autocomplete="off" spellcheck="false">
            <span class="cmd-badge">ESC</span>
        </div>

        <div class="cmd-palette-body" id="cmdList">
            <!-- Navigation Group -->
            <div class="cmd-group" data-group="navigation">
                <div class="cmd-group-title">{{ $isVi ? 'ĐIỀU HƯỚNG NHANH' : 'NAVIGATION' }}</div>
                <a href="{{ route('home') }}#hero" class="cmd-item" data-tokens="home trang chu hero">
                    <span class="cmd-item-icon"><i class="fas fa-home"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Trang chủ // Hero' : 'Home // Hero' }}</span>
                    <span class="cmd-item-hint">#hero</span>
                </a>
                <a href="{{ route('projects.index') }}" class="cmd-item" data-tokens="projects du an selected works case study">
                    <span class="cmd-item-icon"><i class="fas fa-code-branch"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Dự án nổi bật // Case Studies' : 'Featured Projects // Case Studies' }}</span>
                    <span class="cmd-item-hint">/projects</span>
                </a>
                <a href="{{ route('home') }}#experience" class="cmd-item" data-tokens="experience kinh nghiem lam viec timeline career enmasys aipower">
                    <span class="cmd-item-icon"><i class="fas fa-timeline"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Kinh nghiệm nghề nghiệp // Career Timeline' : 'Work Experience // Career Timeline' }}</span>
                    <span class="cmd-item-hint">#experience</span>
                </a>
                <a href="{{ route('home') }}#skills" class="cmd-item" data-tokens="skills ky nang tech stack backend frontend devops ai rag">
                    <span class="cmd-item-icon"><i class="fas fa-layer-group"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Kỹ năng chuyên môn // Tech Stack' : 'Technical Skills // Tech Stack' }}</span>
                    <span class="cmd-item-hint">#skills</span>
                </a>
                <a href="{{ route('home') }}#education" class="cmd-item" data-tokens="education hoc van dai hoc uth gpa thanh tich giai thuong">
                    <span class="cmd-item-icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Học vấn & Bảng thành tích // GPA 3.6 UTH' : 'Education & Achievements // GPA 3.6 UTH' }}</span>
                    <span class="cmd-item-hint">#education</span>
                </a>
                <a href="{{ route('certificates') }}" class="cmd-item" data-tokens="certificates brochure bang cap chung chi giay khen pdf">
                    <span class="cmd-item-icon"><i class="fas fa-award"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Brochure & Hồ sơ xác thực // Credentials' : 'Brochure & Credentials // Official Documents' }}</span>
                    <span class="cmd-item-hint">/certificates</span>
                </a>
                <a href="{{ route('blog.index') }}" class="cmd-item" data-tokens="blog bai viet tap chi engineering articles">
                    <span class="cmd-item-icon"><i class="fas fa-newspaper"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Tạp chí kỹ thuật // Engineering Blog' : 'Engineering Journal // Blog' }}</span>
                    <span class="cmd-item-hint">/blog</span>
                </a>
                <a href="{{ route('contact') }}" class="cmd-item" data-tokens="contact lien he hop tac email recruit hire">
                    <span class="cmd-item-icon"><i class="fas fa-envelope"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Liên hệ tuyển dụng // Contact' : 'Get in Touch // Contact' }}</span>
                    <span class="cmd-item-hint">/contact</span>
                </a>
            </div>

            <!-- Actions Group -->
            <div class="cmd-group" data-group="actions">
                <div class="cmd-group-title">{{ $isVi ? 'HÀNH ĐỘNG NHANH' : 'QUICK ACTIONS' }}</div>
                <button type="button" class="cmd-item" data-tokens="ask ai chat tro ly hoi thien chatbot" onclick="openAiChatDrawer(); closeCmdPalette();">
                    <span class="cmd-item-icon" style="color:var(--accent-lime);"><i class="fas fa-robot"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Mở trợ lý AI "Ask Thien"' : 'Open "Ask Thien" AI Assistant' }}</span>
                    <span class="cmd-item-hint">AI Agent</span>
                </button>
                <button type="button" class="cmd-item" data-tokens="copy email sao chep thu dien tu contact" onclick="copyContactEmail(); closeCmdPalette();">
                    <span class="cmd-item-icon"><i class="fas fa-copy"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Sao chép Email tuyển dụng (hothanthien119@gmail.com)' : 'Copy Recruiter Email (hothanthien119@gmail.com)' }}</span>
                    <span class="cmd-item-hint">1-Click</span>
                </button>
                @if($isVi)
                    <a href="{{ route('lang.switch', 'en') }}" class="cmd-item" data-tokens="switch english ngon ngu tieng anh language">
                        <span class="cmd-item-icon"><i class="fas fa-globe"></i></span>
                        <span class="cmd-item-label">Switch to English version</span>
                        <span class="cmd-item-hint">EN</span>
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'vi') }}" class="cmd-item" data-tokens="chuyen tieng viet ngon ngu language">
                        <span class="cmd-item-icon"><i class="fas fa-globe"></i></span>
                        <span class="cmd-item-label">Chuyển sang giao diện Tiếng Việt</span>
                        <span class="cmd-item-hint">VI</span>
                    </a>
                @endif
                <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" download class="cmd-item" data-tokens="download resume cv brochure pdf ho so">
                    <span class="cmd-item-icon"><i class="fas fa-file-arrow-down"></i></span>
                    <span class="cmd-item-label">{{ $isVi ? 'Tải Brochure / CV Hồ Thành Thiện (PDF 402 KB)' : 'Download Portfolio Brochure (PDF 402 KB)' }}</span>
                    <span class="cmd-item-hint">.PDF</span>
                </a>
                <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="cmd-item" data-tokens="github ma nguon source code repo">
                    <span class="cmd-item-icon"><i class="fab fa-github"></i></span>
                    <span class="cmd-item-label">GitHub @hoThanhThien</span>
                    <span class="cmd-item-hint">External</span>
                </a>
                <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="cmd-item" data-tokens="linkedin profile network">
                    <span class="cmd-item-icon"><i class="fab fa-linkedin"></i></span>
                    <span class="cmd-item-label">LinkedIn @httblackhat</span>
                    <span class="cmd-item-hint">External</span>
                </a>
            </div>

            <!-- Empty Search State -->
            <div id="cmdEmpty" class="cmd-empty-state" style="display: none;">
                <i class="fas fa-terminal"></i>
                <div>{{ $isVi ? 'Không tìm thấy kết quả phù hợp' : 'No matching commands or links found' }}</div>
            </div>
        </div>

        <div class="cmd-palette-footer">
            <span class="cmd-footer-tip"><kbd>&uarr;</kbd><kbd>&darr;</kbd> {{ $isVi ? 'Di chuyển' : 'Navigate' }}</span>
            <span class="cmd-footer-tip"><kbd>&crarr;</kbd> {{ $isVi ? 'Chọn' : 'Select' }}</span>
            <span class="cmd-footer-tip"><kbd>ESC</kbd> {{ $isVi ? 'Đóng' : 'Close' }}</span>
        </div>
    </div>
</div>

