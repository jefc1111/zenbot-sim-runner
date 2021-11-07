<div class="row">    
    <a onclick="return confirm('Are you sure you want to {{ strtolower($btn_text) }} this batch?')" style="margin: 3px; " type="button" class="btn btn-block btn-secondary col-md-3" href="{{ $href }}">
        {{ $btn_text }}
    </a>
</div>
<div class="row">
    <span>
        {{ $description }}
    </span>
</div>
<br>