<x-layout>
    <div id="invoice-container">        
        <iframe frameBorder="0" height="960" width="400" src="{{ $invoice_url }}" title="Invoice"></iframe>
    </div>
</x-layout>

<style>
    div#invoice-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>