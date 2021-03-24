<x-layout>    
    <h2>Create sim run batch</h2>
    <form method="post" action="/sim-run-batches/create/select-strategies">
        @csrf
        <div class="mb-3 row"> 
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input value="{{ $initial_name }}" type="text" id="name" name="name" class="form-control" >
            </div>    
        </div>            
        <div class="mb-3 row">
            <label for="details" class="col-sm-2 col-form-label">Notes</label>
            <div class="col-sm-10">
                <textarea id="notes" name="notes" class="form-control" ></textarea>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="exchange_id" class="col-sm-2 col-form-label">Exchange</label>
            <div class="col-sm-10">
                <select name="exchange_id" id="exchange_id" class="form-control">
                    @foreach($exchanges as $exchange)
                    <option value="{{ $exchange->id }}">{{ $exchange->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="product_id" class="col-sm-2 col-form-label">Product</label>
            <div class="col-sm-10">
                <select name="product_id" id="product_id" class="form-control">
                    @if(! $exchanges->isEmpty())                
                    @foreach($exchanges->first()->products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>        
        <div class="mb-3 row">            
            <label for="start" class="col-sm-2 col-form-label">Start date</label>
            <div class="col-sm-10">
                <input value="{{ $initial_start_date }}" type="text" id="start" name="start" class="form-control">            
            </div>
        </div>
        <div class="mb-3 row">
            <label for="end" class="col-sm-2 col-form-label">End date</label>
            <div class="col-sm-10">
                <input value="{{ $initial_end_date }}" type="text" id="end" name="end" class="form-control">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="start" class="col-sm-2 col-form-label">Buy %</label>
            <div class="col-sm-10">
                <input value="75" type="text" id="buy_pct" name="buy_pct" class="form-control">            
            </div>
        </div>
        <div class="mb-3 row">
            <label for="end" class="col-sm-2 col-form-label"">Sell % </label>
            <div class="col-sm-10">
                <input value="75" type="text" id="sell_pct" name="sell_pct" class="form-control">
            </div>    
        </div>
        <input type="submit" value="Select strategies">
    </form>    

    <script>
        $(document).ready(function() {
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

            $("select#exchange_id").change(function() {
                const exchangeId = $(this).val();

                $.get(`/exchanges/${exchangeId}`, function(res) {
                    $("select#product_id").replaceOptions(res.products.map((p) => {
                        return {
                            text: p.name,
                            value: p.id
                        };
                    }));
                });
            });

            const datepickerOptions = {
                format: "yyyy-mm-dd"
            };

            $('#start').datepicker({
                ...datepickerOptions
            });

            $('#end').datepicker({
                ...datepickerOptions
            });
        });
    </script>
</x-layout>