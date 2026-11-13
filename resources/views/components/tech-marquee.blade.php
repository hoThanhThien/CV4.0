@php
    $techs = [
        ['name' => 'Laravel 11', 'icon' => 'fab fa-laravel', 'color' => '#FF2D20'],
        ['name' => 'Spring Boot', 'icon' => 'fas fa-leaf', 'color' => '#6DB33F'],
        ['name' => 'ReactJS', 'icon' => 'fab fa-react', 'color' => '#61DAFB'],
        ['name' => 'Python / RAG', 'icon' => 'fab fa-python', 'color' => '#3776AB'],
        ['name' => 'FastAPI', 'icon' => 'fas fa-bolt', 'color' => '#009688'],
        ['name' => 'Applied AI / LLM', 'icon' => 'fas fa-robot', 'color' => '#C6F432'],
        ['name' => 'MySQL', 'icon' => 'fas fa-database', 'color' => '#4479A1'],
        ['name' => 'PostgreSQL', 'icon' => 'fas fa-server', 'color' => '#336791'],
        ['name' => 'Docker', 'icon' => 'fab fa-docker', 'color' => '#2496ED'],
        ['name' => 'Odoo ERP', 'icon' => 'fas fa-cubes', 'color' => '#714B67'],
        ['name' => 'Vector Search', 'icon' => 'fas fa-layer-group', 'color' => '#38BDF8'],
        ['name' => 'RESTful & JWT', 'icon' => 'fas fa-shield-halved', 'color' => '#A78BFA'],
    ];
@endphp

<div class="marquee-wrapper" aria-hidden="true">
    <div class="marquee-track">
        @for($i = 0; $i < 2; $i++)
            <div class="marquee-group">
                @foreach($techs as $item)
                    <div class="marquee-pill">
                        <i class="{{ $item['icon'] }}" style="color: {{ $item['color'] }}"></i>
                        <span>{{ $item['name'] }}</span>
                    </div>
                @endforeach
            </div>
        @endfor
    </div>
</div>

