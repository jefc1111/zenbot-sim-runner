<li class="nav-item">
    <a 
        id="{{ $id }}-tab" 
        data-toggle="tab" 
        class="nav-link {{ isset($active) && $active == true ? 'active' : null }}" 
        href="#{{ $id }}"
    >
        {{ $label }}
    </a>
</li>