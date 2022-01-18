<div id="live-log">
    <h4>Fetching log...</h4>
    <div>
        <code>
            <ul class="list-unstyled">
            </ul>
        </code>
    </div>
    <p>Last {{ config('zenbot.log_lines_to_keep') }} lines</p>
</div>

<style>
    div#live-log > div {
        font-size: 0.95em;
        height: 480px;
        overflow-y: scroll;
        display: flex;
        flex-direction: column-reverse;
        color: white;
        background: #000;
        padding: 2px;
        border-radius: 5px;
    }

    div#live-log > div ul li span {
        padding: 2px;
        border-radius: 2px;
    }
</style>