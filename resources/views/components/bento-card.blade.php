@props([
    'class' => '',
    'id' => null,
    'spotlight' => true,
    'style' => ''
])

<div {{ $id ? 'id="'.$id.'"' : '' }} class="bento-card {{ $spotlight ? 'has-spotlight' : '' }} {{ $class }}" style="{{ $style }}">
    <div class="bento-card-border" aria-hidden="true"></div>
    <div class="bento-card-content">
        {{ $slot }}
    </div>
</div>

