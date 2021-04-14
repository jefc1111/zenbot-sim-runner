<x-layout>
    <style>
        table {
            width: 100%;            
        }

        table tr td {
            
        }

        table tr td, input[type=text] {
            background: #ddd;
        }

        input[type=text] {
            border: none;
            width: 80px;
        }

        ul {
            list-style-type: none;
        }
    </style>
    @include('sim_run_batches.create.progress_bar', ['progress_pct' => 75])
    @include('sim_run_batches.metadata_snippet')
    <form method="post" action="/sim-run-batches">
        <h2>Confirm sim runs for batch</h2>
        @csrf
        <h2>Sim run quantities</h2>
        <ul>
            @foreach($strategies as $strategy)
            <li>
                {{ $strategy->name }}: {{ count($strategy->sim_runs) }}
            </li>
            @endforeach
        </ul>
        <h2>Sim run detail</h2>
        @foreach($strategies as $strategy)
        <h3>{{ $strategy->name }}</h3>
        <table>
            <thead>
                <tr>
                    @foreach($strategy->options as $strategy_option)
                    <th>{{ $strategy_option->name }}</th>
                    @endforeach
                    <th>
                </tr>
            </thead>
            <tbody>
                @foreach($strategy->sim_runs as $k => $sim_run)
                <tr>
                    @foreach($strategy->options as $strategy_option)
                    <td>
                        <input 
                            type="text" 
                            name="{{ $strategy_option->strategy_id }}_{{ $k }}-{{ $strategy_option->id }}"
                            value="{{ $sim_run->get_value_for_option($strategy_option) }}" 
                        />                        
                    </td>
                    @endforeach
                    <td>
                        <input 
                            type="checkbox" 
                            id="" 
                            name="" 
                            value=""
                        >            
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
        <input type="submit" value="Save">
    </form>    
</x-layout>