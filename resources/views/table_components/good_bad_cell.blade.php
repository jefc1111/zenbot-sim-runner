<td 
    @if($value != 0)
    class="text-{{ $value < 0 ? 'danger' : 'success' }}"
    @endif
>
    {{ $content }}
</td>