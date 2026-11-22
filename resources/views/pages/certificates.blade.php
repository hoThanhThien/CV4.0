@extends('layouts.pearl')

@section('active_nav', 'credentials')
@section('title', (app()->getLocale() === 'vi' ? 'Bằng cấp, Giấy khen & Brochure | Hồ Thành Thiện' : 'Credentials, Certificates & Brochure | Ho Thanh Thien'))
@section('description', app()->getLocale() === 'vi'
    ? 'Xem và tải về Bằng Cử nhân CNTT, Giải thưởng Thiết kế Logo và Hồ sơ Năng lực (Brochure PDF) chính thức của Kỹ sư Hồ Thành Thiện.'
    : 'View and download official academic degree credentials, competition awards, and the Portfolio Brochure PDF of Software Engineer Ho Thanh Thien.')

@section('styles')
  .cert-modal-backdrop {
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.22s ease;
  }
  .cert-modal-backdrop.active {
    opacity: 1;
    pointer-events: auto;
  }
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';
    $activeCategory = request('category', 'all');

    $counts = $categoryCounts ?? \App\Support\PortfolioCache::certificateCategoryCounts();
    $totalCount = $counts['all'] ?? 0;
    $brochureCount = $counts['brochure'] ?? 0;
    $degreeCount = $counts['degree'] ?? 0;
    $awardCount = $counts['award'] ?? 0;
    $certCount = $counts['certificate'] ?? 0;

    $catFilters = [
        ['key' => 'all', 'icon' => 'layers', 'count' => $totalCount, 'en' => 'All Documents', 'vi' => 'Tất cả Tài liệu', 'url' => route('certificates')],
        ['key' => 'brochure', 'icon' => 'book-open', 'count' => $brochureCount, 'en' => 'Brochure / CV', 'vi' => 'Brochure / Hồ sơ', 'url' => route('certificates', ['category' => 'brochure'])],
        ['key' => 'degree', 'icon' => 'graduation-cap', 'count' => $degreeCount, 'en' => 'Official Degree', 'vi' => 'Bằng cấp chính quy', 'url' => route('certificates', ['category' => 'degree'])],
        ['key' => 'award', 'icon' => 'trophy', 'count' => $awardCount, 'en' => 'Honors & Awards', 'vi' => 'Giải thưởng', 'url' => route('certificates', ['category' => 'award'])],
        ['key' => 'certificate', 'icon' => 'award', 'count' => $certCount, 'en' => 'Certificates', 'vi' => 'Chứng chỉ', 'url' => route('certificates', ['category' => 'certificate'])],
    ];

    $lucideForCategory = static function (string $cat): string {
        return match ($cat) {
            'brochure' => 'book-open',
            'degree' => 'graduation-cap',
            'award' => 'trophy',
            'certificate' => 'award',
            default => 'file-text',
        };
    };

    $labelEnForCategory = static function (string $cat): string {
        return match ($cat) {
            'brochure' => 'Brochure / Portfolio',
            'degree' => 'Degree / Diploma',
            'award' => 'Award / Honor',
            'certificate' => 'Certificate',
            default => 'Document',
        };
    };

    $labelViForCategory = static function (string $cat): string {
        return match ($cat) {
            'brochure' => 'Brochure / Hồ sơ',
            'degree' => 'Bằng cấp chính quy',
            'award' => 'Giải thưởng',
            'certificate' => 'Giấy khen / Chứng chỉ',
            default => 'Tài liệu',
        };
    };
@endphp

<!-- =========================================================================
     SECTION 1: CREDENTIALS HERO BENTO HEADER
========================================================================= -->
<section class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
  <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

    <div class="lg:col-span-8 space-y-4">
      <nav class="flex items-center gap-2 font-mono text-xs text-slate-600 dark:text-slate-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors inline-flex items-center gap-1">
          <i data-lucide="home" class="w-3.5 h-3.5"></i>
          <span data-i18n="breadcrumb_home">{{ $isVi ? 'Trang chủ' : 'Home' }}</span>
        </a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold" data-i18n="nav_credentials">{{ $isVi ? 'Bằng cấp & PDF' : 'Credentials' }}</span>
      </nav>

      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-500/15 border border-amber-200/80 dark:border-amber-500/30 font-mono text-xs font-bold tracking-widest uppercase text-amber-800 dark:text-amber-300">
        <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
        <span data-i18n="cred_kicker">CREDENTIALS // VERIFIED ARCHIVE</span>
      </div>

      <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.75rem] font-extrabold tracking-tight leading-[1.12] text-slate-900 dark:text-white">
        <span data-i18n="cred_title_1">{{ $isVi ? 'Bằng cấp, Giải thưởng &' : 'Degrees, Honors &' }}</span>
        <span class="bg-gradient-to-r from-sky-700 via-indigo-700 to-emerald-700 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-400 bg-clip-text text-transparent" data-i18n="cred_title_highlight">
          {{ $isVi ? 'Hồ sơ Năng lực PDF' : 'Portfolio Brochure' }}
        </span>
      </h1>

      <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed" data-i18n="cred_subtitle">
        {{ $isVi
            ? 'Lưu trữ minh chứng học thuật chính quy tại ĐH GTVT TP.HCM (UTH), giải thưởng thiết kế sáng tạo và bản Hồ sơ Năng lực kỹ thuật dạng PDF sẵn sàng tải về.'
            : 'Verified academic degrees from UTH, UI/UX design honors, and downloadable Engineering Brochure PDF documents for recruiters and engineering teams.' }}
      </p>
    </div>

    <!-- Right Stats & Actions -->
    <div class="lg:col-span-4 space-y-3">
      <div class="grid grid-cols-3 gap-2 sm:gap-2.5">
        <div class="p-2.5 sm:p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
          <div class="font-display text-xl sm:text-2xl font-extrabold text-sky-700 dark:text-sky-400">{{ $totalCount }}</div>
          <div class="font-mono text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 mt-0.5" data-i18n="stat_total_docs">{{ $isVi ? 'Tài liệu' : 'Records' }}</div>
        </div>
        <div class="p-2.5 sm:p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
          <div class="font-display text-xl sm:text-2xl font-extrabold text-emerald-700 dark:text-emerald-400">4.0</div>
          <div class="font-mono text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 mt-0.5">Latest GPA</div>
        </div>
        <div class="p-2.5 sm:p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
          <div class="font-display text-xl sm:text-2xl font-extrabold text-amber-800 dark:text-amber-400">2nd</div>
          <div class="font-mono text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 mt-0.5">UTH Award</div>
        </div>
      </div>

      <div class="flex flex-wrap sm:flex-nowrap gap-2">
        <a href="{{ route('home') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
          <span data-i18n="btn_back_home">{{ $isVi ? 'Trang chủ' : 'Overview' }}</span>
        </a>
        <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" download class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-mono font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm">
          <i data-lucide="download" class="w-3.5 h-3.5"></i>
          <span data-i18n="btn_brochure">Brochure PDF</span>
        </a>
      </div>
    </div>

  </div>
</section>

<!-- =========================================================================
     SECTION 2: CATEGORY FILTER PILLS
========================================================================= -->
<section class="flex flex-wrap items-center gap-2.5">
  @foreach($catFilters as $f)
    @if($f['count'] > 0 || $f['key'] === 'all')
      @php $isActive = $activeCategory === $f['key']; @endphp
      <a href="{{ $f['url'] }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-mono font-bold border transition-all {{ $isActive ? 'bg-sky-700 border-sky-700 text-white shadow-lg shadow-sky-700/20' : 'bg-white/95 dark:bg-slate-900/95 border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-200 hover:border-sky-400' }}">
        <i data-lucide="{{ $f['icon'] }}" class="w-3.5 h-3.5"></i>
        <span data-en="{{ $f['en'] }}" data-vi="{{ $f['vi'] }}">{{ $isVi ? $f['vi'] : $f['en'] }}</span>
        <span class="px-1.5 py-0.5 rounded-md text-xs {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">
          {{ $f['count'] }}
        </span>
      </a>
    @endif
  @endforeach
</section>

<!-- =========================================================================
     SECTION 3: CREDENTIAL BENTO GRID
========================================================================= -->
@if($certificates->count())
  <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($certificates as $cert)
      @php
          $isBrochure = $cert->category === 'brochure';
          $catIcon = $lucideForCategory($cert->category);
          $catEn = $labelEnForCategory($cert->category);
          $catVi = $labelViForCategory($cert->category);

          $viewPayload = [
              'mode' => $isBrochure ? 'file' : 'detail',
              'url' => $cert->file_url,
              'type' => strtolower((string) $cert->file_type) === 'pdf' || $cert->is_pdf ? 'pdf' : 'image',
              'title' => $cert->localized_title,
              'issuer' => $cert->localized_issuer ?: 'University of Transport Ho Chi Minh City (UTH)',
              'description' => $cert->localized_description ?: '',
          ];
      @endphp
      <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm hover:-translate-y-1 transition-all flex flex-col">

        <!-- Preview Specimen Window -->
        <button type="button"
                onclick="openCertModalFromEl(this)"
                data-cert-json="{{ json_encode($viewPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
                class="relative z-10 aspect-[16/10] w-full text-left bg-slate-100 dark:bg-slate-950 border-b border-slate-200/80 dark:border-slate-800 overflow-hidden group cursor-pointer focus:outline-none">
          @if($cert->thumbnail && !$cert->thumbnail_is_pdf)
            <img src="{{ $cert->thumbnail_url }}" width="800" height="500" alt="{{ $cert->localized_title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
              <span class="px-3 py-1 rounded-full bg-white/90 text-slate-900 font-mono text-xs font-bold inline-flex items-center gap-1.5">
                <i data-lucide="eye" class="w-3.5 h-3.5 text-sky-700"></i>
                <span data-i18n="btn_inspect">{{ $isVi ? 'Xem chi tiết' : 'Inspect' }}</span>
              </span>
            </div>
          @elseif($isBrochure)
            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-slate-900 to-sky-950 p-6 flex flex-col justify-between text-white relative">
              <div class="flex items-center justify-between">
                <span class="px-2.5 py-0.5 rounded-full bg-sky-500/20 border border-sky-400/30 font-mono text-xs font-bold tracking-widest uppercase text-sky-300">CV &amp; BROCHURE</span>
                <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-mono text-xs font-bold">PDF</span>
              </div>
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-sky-300 group-hover:scale-110 transition-transform">
                  <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <div class="space-y-0.5">
                  <div class="font-display font-bold text-sm leading-tight">Portfolio Brochure</div>
                  <div class="font-mono text-xs text-slate-300">PDF / {{ $cert->file_size ?: '402.15 KB' }}</div>
                </div>
              </div>
              <div class="font-mono text-xs text-sky-300 inline-flex items-center gap-1">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                <span data-i18n="click_preview_pdf">{{ $isVi ? 'Nhấn để xem trực tuyến' : 'Click to inspect PDF' }}</span>
              </div>
            </div>
          @else
            <div class="w-full h-full bg-gradient-to-br from-sky-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-900 dark:to-indigo-950/60 p-6 flex flex-col justify-between">
              <div class="flex items-center justify-between">
                <span class="px-2.5 py-0.5 rounded-full bg-white/90 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 font-mono text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300" data-en="{{ $catEn }}" data-vi="{{ $catVi }}">
                  {{ $isVi ? $catVi : $catEn }}
                </span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              </div>
              <div class="flex items-center justify-center">
                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-sm flex items-center justify-center text-sky-700 dark:text-sky-400 group-hover:scale-110 transition-transform">
                  <i data-lucide="{{ $catIcon }}" class="w-7 h-7"></i>
                </div>
              </div>
              <div class="font-mono text-xs text-slate-600 dark:text-slate-400 flex items-center justify-between">
                <span data-i18n="verified_record">{{ $isVi ? 'Bản gốc lưu trữ' : 'Verified Record' }}</span>
                <span class="text-sky-700 dark:text-sky-400 font-bold" data-i18n="btn_inspect">{{ $isVi ? 'Xem hồ sơ' : 'View Details' }}</span>
              </div>
            </div>
          @endif
        </button>

        <!-- Card Body -->
        <div class="relative z-10 p-6 flex-1 flex flex-col justify-between gap-5">
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2 flex-wrap font-mono text-xs">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 text-sky-700 dark:text-sky-300 font-bold">
                <i data-lucide="{{ $catIcon }}" class="w-3 h-3"></i>
                <span data-en="{{ $catEn }}" data-vi="{{ $catVi }}">{{ $isVi ? $catVi : $catEn }}</span>
              </span>

              @if($cert->featured)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-300 font-bold">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  <span data-i18n="badge_featured">{{ $isVi ? 'Nổi bật' : 'Featured' }}</span>
                </span>
              @endif
            </div>

            <h2 class="font-display text-lg font-extrabold text-slate-900 dark:text-white leading-snug">
              {{ $cert->localized_title }}
            </h2>

            <div class="space-y-1 text-xs font-mono text-slate-600 dark:text-slate-400">
              @if($cert->localized_issuer)
                <div class="flex items-center gap-1.5">
                  <i data-lucide="building-2" class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400 flex-shrink-0"></i>
                  <span class="truncate">{{ $cert->localized_issuer }}</span>
                </div>
              @endif
              @if($cert->issue_date)
                <div class="flex items-center gap-1.5">
                  <i data-lucide="calendar" class="w-3.5 h-3.5 text-sky-700 dark:text-sky-400 flex-shrink-0"></i>
                  <span>{{ $cert->issue_date }}</span>
                </div>
              @endif
            </div>

            @if($cert->localized_description)
              <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3">
                {{ $cert->localized_description }}
              </p>
            @endif

            @if(count($cert->extra_files_with_urls) > 0)
              <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-2">
                <div class="font-mono text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400" data-i18n="extra_files_label">
                </div>
                <div class="space-y-1.5">
                  @foreach($cert->extra_files_with_urls as $extra)
                    @php
                        $extraPayload = [
                            'mode' => 'file',
                            'url' => $extra['url'],
                            'type' => $extra['is_pdf'] ? 'pdf' : 'image',
                            'title' => $extra['name'],
                        ];
                    @endphp
                      <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-xs">
                      <span class="inline-flex items-center gap-2 min-w-0 font-medium text-slate-700 dark:text-slate-200">
                        <i data-lucide="{{ $extra['is_pdf'] ? 'file-text' : 'image' }}" class="w-3.5 h-3.5 {{ $extra['is_pdf'] ? 'text-rose-700 dark:text-rose-400' : 'text-sky-700 dark:text-sky-400' }} flex-shrink-0"></i>
                        <span class="truncate" title="{{ $extra['name'] }}">{{ $extra['name'] }}</span>
                      </span>
                      <div class="flex items-center gap-1 flex-shrink-0">
                        <button type="button"
                                onclick="openCertModalFromEl(this)"
                                data-cert-json="{{ json_encode($extraPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
                                class="p-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-sky-400 hover:text-sky-700 transition-colors"
                                aria-label="Inspect {{ $extra['name'] }}"
                                title="Inspect">
                          <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </button>
                        <a href="{{ $extra['url'] }}" download class="p-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-sky-400 hover:text-sky-700 transition-colors" aria-label="Download {{ $extra['name'] }}" title="Download">
                          <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>

          <!-- Footer Actions -->
          <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 space-y-3">
            <div class="flex items-center justify-between text-xs font-mono">
              @if($isBrochure)
                <span class="text-slate-600 dark:text-slate-400">PDF / {{ $cert->file_size ?: '402.15 KB' }}</span>
                <span class="text-emerald-700 dark:text-emerald-400 font-bold" data-i18n="status_digital">{{ $isVi ? 'Bản số hóa chính thức' : 'Official Digital Copy' }}</span>
              @else
                <span class="text-slate-600 dark:text-slate-400" data-i18n="status_archive">{{ $isVi ? 'Bản cứng lưu trữ' : 'Physical Archive' }}</span>
                <span class="text-amber-800 dark:text-amber-400 font-bold" data-i18n="status_request">{{ $isVi ? 'Cập nhật theo yêu cầu' : 'Available On Request' }}</span>
              @endif
            </div>

            <div class="grid grid-cols-2 gap-2">
              @if($isBrochure)
                <button type="button"
                        onclick="openCertModalFromEl(this)"
                        data-cert-json="{{ json_encode($viewPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-sky-700 text-white text-xs font-mono font-bold hover:bg-sky-800 transition-all shadow-sm">
                  <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                  <span data-i18n="btn_view_pdf">{{ $isVi ? 'Xem PDF' : 'View PDF' }}</span>
                </button>
                <a href="{{ $cert->file_url }}" download class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
                  <i data-lucide="download" class="w-3.5 h-3.5"></i>
                  <span data-i18n="btn_download">{{ $isVi ? 'Tải xuống' : 'Download' }}</span>
                </a>
              @else
                <button type="button"
                        onclick="openCertModalFromEl(this)"
                        data-cert-json="{{ json_encode($viewPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-mono font-bold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm">
                  <i data-lucide="info" class="w-3.5 h-3.5"></i>
                  <span data-i18n="btn_view_details">{{ $isVi ? 'Xem hồ sơ' : 'View Details' }}</span>
                </button>
                <a href="{{ route('contact') }}?subject={{ urlencode($cert->title) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
                  <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                  <span data-i18n="btn_request_scan">{{ $isVi ? 'Yêu cầu scan' : 'Request Scan' }}</span>
                </a>
              @endif
            </div>
          </div>
        </div>

      </article>
    @endforeach
  </section>
@else
  <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-14 text-center">
    <div class="relative z-10 flex flex-col items-center space-y-3">
      <div class="w-14 h-14 rounded-2xl bg-sky-50 dark:bg-sky-500/15 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center text-sky-700 dark:text-sky-400">
        <i data-lucide="folder-open" class="w-7 h-7"></i>
      </div>
      <h2 class="font-display text-xl font-extrabold text-slate-900 dark:text-white" data-i18n="empty_title">
        {{ $isVi ? 'Không tìm thấy tài liệu trong mục này' : 'No credentials found in this category' }}
      </h2>
      <a href="{{ route('certificates') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-700 text-white text-xs font-mono font-bold hover:bg-sky-800 transition-all">
        <span data-i18n="view_all">{{ $isVi ? 'Xem tất cả' : 'View All' }}</span>
      </a>
    </div>
  </div>
@endif

<!-- =========================================================================
     DOCUMENT VIEWER MODAL (FROSTED PEARL WHITE & DARK SLATE)
========================================================================= -->
<div id="certModal" class="cert-modal-backdrop fixed inset-0 z-[100] bg-slate-900/70 backdrop-blur-md flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="certModalTitleText" onclick="closeCertModal(event)">
  <div class="w-full max-w-4xl max-h-[90vh] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl flex flex-col overflow-hidden" onclick="event.stopPropagation()">

    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4 bg-slate-50/80 dark:bg-slate-950/60">
      <div class="flex items-center gap-2.5 min-w-0">
        <span class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 flex items-center justify-center flex-shrink-0">
          <i data-lucide="file-text" class="w-4 h-4"></i>
        </span>
        <span id="certModalTitleText" class="font-display font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate">Document</span>
      </div>

      <div class="flex items-center gap-2 flex-shrink-0">
        <a id="certModalExternalBtn" href="#" target="_blank" rel="noopener" aria-label="{{ $isVi ? 'Mở chứng nhận ở tab mới' : 'Open certificate in new tab' }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-colors">
          <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
          <span class="hidden sm:inline" data-i18n="modal_new_tab">{{ $isVi ? 'Mở tab mới' : 'New Tab' }}</span>
        </a>
        <a id="certModalDownloadBtn" href="#" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sky-700 text-white text-xs font-mono font-bold hover:bg-sky-800 transition-colors">
          <i data-lucide="download" class="w-3.5 h-3.5"></i>
          <span id="certModalDlText">{{ $isVi ? 'Tải xuống' : 'Download' }}</span>
        </a>
        <button type="button" id="certModalCloseBtn" onclick="closeCertModalDirect()" class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" aria-label="Close">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
    </div>

    <div id="certModalBody" class="flex-1 overflow-y-auto bg-slate-100/70 dark:bg-slate-950/80 min-h-[380px] flex items-center justify-center"></div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  window.PAGE_I18N = {
    en: {
      cred_kicker: 'CREDENTIALS // VERIFIED ARCHIVE',
      cred_title_1: 'Degrees, Honors &',
      cred_title_highlight: 'Portfolio Brochure',
      cred_subtitle: 'Verified academic degrees from UTH, UI/UX design honors, and downloadable Engineering Brochure PDF documents for recruiters and engineering teams.',
      stat_total_docs: 'Records',
      badge_featured: 'Featured',
      btn_inspect: 'Inspect',
      click_preview_pdf: 'Click to inspect PDF',
      verified_record: 'Verified Record',
      extra_files_label: 'Attachments',
      status_digital: 'Official Digital Copy',
      status_archive: 'Physical Archive',
      status_request: 'Available On Request',
      btn_view_pdf: 'View PDF',
      btn_download: 'Download',
      btn_view_details: 'View Details',
      btn_request_scan: 'Request Scan',
      empty_title: 'No credentials found in this category',
      view_all: 'View All',
      modal_new_tab: 'New Tab'
    },
    vi: {
      cred_kicker: 'MINH CHỨNG // HỒ SƠ XÁC THỰC',
      cred_title_1: 'Bằng cấp, Giải thưởng &',
      cred_title_highlight: 'Hồ sơ Năng lực PDF',
      cred_subtitle: 'Lưu trữ minh chứng học thuật chính quy tại ĐH GTVT TP.HCM (UTH), giải thưởng thiết kế sáng tạo và bản Hồ sơ Năng lực kỹ thuật dạng PDF sẵn sàng tải về.',
      stat_total_docs: 'Tài liệu',
      badge_featured: 'Nổi bật',
      btn_inspect: 'Xem chi tiết',
      click_preview_pdf: 'Nhấn để xem trực tuyến',
      verified_record: 'Bản gốc lưu trữ',
      extra_files_label: 'Tài liệu đính kèm',
      status_digital: 'Bản số hóa chính thức',
      status_archive: 'Bản cứng lưu trữ',
      status_request: 'Cập nhật theo yêu cầu',
      btn_view_pdf: 'Xem PDF',
      btn_download: 'Tải xuống',
      btn_view_details: 'Xem hồ sơ',
      btn_request_scan: 'Yêu cầu scan',
      empty_title: 'Không tìm thấy tài liệu trong mục này',
      view_all: 'Xem tất cả',
      modal_new_tab: 'Mở tab mới'
    }
  };

  let lastCertTrigger = null;

  function escapeHtmlText(str) {
    return String(str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function openCertModalFromEl(el) {
    const raw = el.getAttribute('data-cert-json');
    if (!raw) return;
    let payload;
    try {
      payload = JSON.parse(raw);
    } catch (e) {
      return;
    }

    lastCertTrigger = document.activeElement;
    const modal = document.getElementById('certModal');
    const titleTextEl = document.getElementById('certModalTitleText');
    const bodyEl = document.getElementById('certModalBody');
    const extBtn = document.getElementById('certModalExternalBtn');
    const dlBtn = document.getElementById('certModalDownloadBtn');
    const dlTextEl = document.getElementById('certModalDlText');

    if (titleTextEl) titleTextEl.textContent = payload.title || 'Document';

    if (payload.mode === 'detail') {
      if (extBtn) extBtn.style.display = 'none';
      if (dlBtn) {
        dlBtn.href = '{{ route("contact") }}?subject=' + encodeURIComponent(payload.title || '');
        dlBtn.removeAttribute('download');
      }
      if (dlTextEl) {
        dlTextEl.textContent = currentLang === 'vi' ? 'Yêu cầu scan' : 'Request Scan';
      }

      const verifiedLbl = currentLang === 'vi' ? 'Hồ sơ chính quy đã xác thực' : 'Verified Official Record';
      const filingLbl = currentLang === 'vi' ? 'Tình trạng lưu trữ:' : 'Filing status:';
      const filingDesc = currentLang === 'vi'
        ? 'Văn bằng và giải thưởng gốc được lưu giữ tại trường và hồ sơ cá nhân. Nhà tuyển dụng cần bản sao công chứng hoặc scan số hóa độ nét cao vui lòng liên hệ xác nhận trực tiếp.'
        : 'Original degree and award credentials are preserved in official personal archives. Recruiters requiring a notarized digital scan may request one directly.';

      bodyEl.innerHTML = `
        <div class="p-6 sm:p-8 max-w-2xl w-full mx-auto space-y-5 text-left">
          <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 font-mono text-xs font-bold">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            ${escapeHtmlText(verifiedLbl)}
          </span>
          <div class="space-y-1">
            <h3 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">${escapeHtmlText(payload.title)}</h3>
            <div class="font-mono text-xs font-semibold text-sky-600 dark:text-sky-400">${escapeHtmlText(payload.issuer)}</div>
          </div>
          <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed">${escapeHtmlText(payload.description)}</p>
          <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            <strong class="text-slate-900 dark:text-white">${escapeHtmlText(filingLbl)}</strong> ${escapeHtmlText(filingDesc)}
          </div>
        </div>
      `;
    } else {
      if (extBtn) {
        extBtn.style.display = 'inline-flex';
        extBtn.href = payload.url;
      }
      if (dlBtn) {
        dlBtn.href = payload.url;
        dlBtn.setAttribute('download', '');
      }
      if (dlTextEl) {
        dlTextEl.textContent = currentLang === 'vi' ? 'Tải xuống' : 'Download';
      }

      if (payload.type === 'pdf') {
        bodyEl.innerHTML = `<iframe src="${escapeHtmlText(payload.url)}#toolbar=1" title="${escapeHtmlText(payload.title)}" class="w-full h-[68vh] border-0"></iframe>`;
      } else {
        bodyEl.innerHTML = `<img src="${escapeHtmlText(payload.url)}" alt="${escapeHtmlText(payload.title)}" class="max-h-[68vh] w-auto object-contain p-4">`;
      }
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
      const closeBtn = document.getElementById('certModalCloseBtn');
      if (closeBtn) closeBtn.focus();
    }, 50);
  }

  function closeCertModalDirect() {
    const modal = document.getElementById('certModal');
    const bodyEl = document.getElementById('certModalBody');
    if (!modal || !modal.classList.contains('active')) return;
    modal.classList.remove('active');
    setTimeout(() => {
      if (bodyEl && !modal.classList.contains('active')) bodyEl.innerHTML = '';
    }, 240);
    document.body.style.overflow = '';
    if (lastCertTrigger && typeof lastCertTrigger.focus === 'function') {
      lastCertTrigger.focus();
    }
  }

  function closeCertModal(e) {
    if (e.target && e.target.id === 'certModal') {
      closeCertModalDirect();
    }
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeCertModalDirect();
    }
  });
</script>
@endsection
