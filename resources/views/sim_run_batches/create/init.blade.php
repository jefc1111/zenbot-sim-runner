<x-layout>    
    <h2>Create sim run batch</h2>
    <form method="post" action="/sim-run-batches/create/select-strategies">
        @csrf
        <fieldset>    
            <label for="name">Name</label><br>
            <input value="{{ $initial_name }}" type="text" id="name" name="name"><br>

            <label for="details">Notes</label><br>
            <textarea id="notes" name="notes"></textarea><br>
        </fieldset>
        <fieldset>
            <label for="exchange_id">Exchange</label><br>
            <select name="exchange_id" id="exchange_id">
                @foreach($exchanges as $exchange)
                <option value="{{ $exchange->id }}">{{ $exchange->name }}</option>
                @endforeach
            </select><br>

            <label for="product_id">Product</label><br>
            <select name="product_id" id="product_id">
                @if(! $exchanges->isEmpty())                
                @foreach($exchanges->first()->products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
                @endif
            </select><br>
        </fieldset>
        <fieldset>            
            <label for="start">Start date</label><br>
            <input value="{{ $initial_start_date }}" type="text" id="start" name="start"><br>
            
            <label for="end">End date</label><br>
            <input value="{{ $initial_end_date }}" type="text" id="end" name="end"><br>
        </fieldset>
        <fieldset>            
            <label for="start">Buy %</label><br>
            <input value="75" type="text" id="buy_pct" name="buy_pct"><br>
            
            <label for="end">Sell % </label><br>
            <input value="75" type="text" id="sell_pct" name="sell_pct"><br>
        </fieldset>
        <input type="submit" value="Select strategies">
    </form>    

    <script>
        // https//stackoverflow.com/a/16654226
        (function($, window) {
            $.fn.replaceOptions = function(options) {
                var self, $option;

                this.empty();
                self = this;

                $.each(options, function(index, option) {
                $option = $("<option></option>")
                    .attr("value", option.value)
                    .text(option.text);
                self.append($option);
                });
            };
        })($, window);

        $("select#exchange").change(function() {
            const exchangeId = $(this).val();

            $.get(`/exchanges/${exchangeId}`, function(res) {
                $("select#product").replaceOptions(res.products.map((p) => {
                    return {
                        text p.name,
                        value p.id
                    };
                }));
            });
        });

        const datepickerOptions = {
            dateFormat "yy-mm-dd"
        };

        $('#start').datepicker({
            ...datepickerOptions
        });

        $('#end').datepicker({
            ...datepickerOptions
        });
    </script>
</x-layout>