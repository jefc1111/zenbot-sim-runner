<x-layout>
    @include('sim_run_batches.create.progress_bar', ['progress_pct' => 25])
    @include('sim_run_batches.metadata_snippet')
    <h2>Select strategies</h2>
    <form method="post" action="/sim-run-batches/create/refine-strategies">
        @csrf  
        <table class="table table-sm table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Include</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="3"></td>
                    <th>
                        <input type="checkbox" id="check-all" />
                        all
                    </th>
                </tr>
            </tfoot>
            <tbody>
                @foreach($strategies as $strategy)
                <tr>
                    <td>{{ $strategy->id }}</id>
                    <td>{{ $strategy->name }}</td>
                    <td>{{ $strategy->description }}</td>
                    <td>
                        <input 
                            type="checkbox" 
                            id="strategy-{{ $strategy->id }}" 
                            name="strategies[]" 
                            value="{{ $strategy->id }}"
                        />            
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <input type="submit" value="Submit">
    </form>   
    <script>
        $("table tr").click(function() { 
            var checkbox = $(this).find("input[type='checkbox']");
            checkbox.attr('checked', !checkbox.attr('checked')); 
        });

        $("input#check-all").change(function() { 
            console.log($("input#check-all").attr('checked'))
            $(this).closest("table")
            .find("tbody")
            .find("input[type='checkbox']")
            .attr('checked', $("input#check-all").attr('checked') === 'checked' ? true : false); 
        });
    </script> 
</x-layout>