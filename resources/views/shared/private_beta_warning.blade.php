<div class="alert alert-danger" role="alert">
    <strong>Important Warning - limited / unstable beta release</strong>    
    <button 
        type="button" 
        class="btn btn-sm btn-success" 
        data-placement="bottom" 
        data-toggle="popover" 
        title="Beta warning" 
        data-content="
        This platform is still in rapid & early development. Expect bugs, DB wipes and unexpected / unpredictable behaviour.
        Do not risk your real money (by live trading) based on the results of this platform. If you do, you do so entirely at your own risk.        
        <br />
        <strong>Note:</strong> sim time will not be deducted for sim runs that fail part way through.
        <br />
        <strong>Note:</strong> currently only Binance is enabled. 
        "
    >
        More info
    </button>
    <br>
    Visit <a target="_blank" href="https://discord.gg/Xa5FQtXt">Discord</a> to discuss anything to do with this platform. 
</div> 

<script>
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({
            html: true
        })
    });
</script>