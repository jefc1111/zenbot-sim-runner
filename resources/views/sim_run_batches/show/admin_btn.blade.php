<div class="row">    
    @if ($method === 'get')
    <a onClick="return confirm('Are you sure you want to {{ strtolower($btn_text) }} this batch?')" style="margin: 3px; " type="button" class="btn btn-block btn-secondary col-md-3" href="{{ $href }}">
        {{ $btn_text }}
    </a>
    @else
    <form action="{{ $href }}" method="post">
        <input onClick="return confirm('Are you sure you want to {{ strtolower($btn_text) }} this batch?')" style="margin: 3px; " class="btn btn-block btn-secondary col-md-3" type="submit" value="Delete" />
        @method($method)
        @csrf
    </form>
    @endif
</div>
<div class="row">
    <span>
        {{ $description }}
    </span>
</div>
<br>