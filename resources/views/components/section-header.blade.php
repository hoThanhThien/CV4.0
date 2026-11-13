@props([
    'number' => '01',
    'kicker' => 'SECTION',
    'title' => '',
    'subtitle' => '',
    'action' => null
])

<div class="section-head" data-reveal>
    <div class="section-head-left">
        <div class="section-kicker">
            <span class="mono-idx">{{ $number }} //</span>
            <span>{{ $kicker }}</span>
        </div>
        <h2 class="display-heading">{!! $title !!}</h2>
        @if($subtitle)
            <p class="section-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @if($action)
        <div class="section-head-action">
            {!! $action !!}
        </div>
    @endif
</div>
