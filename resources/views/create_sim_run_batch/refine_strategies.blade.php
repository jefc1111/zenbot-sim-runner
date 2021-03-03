<x-layout>
    <style>
        table {
            width: 100%;            
        }

        table tr td:nth-child(n+6) {
            
        }

        table tr td:nth-child(n+6), input[type=text] {
            background: #ddd;
        }

        input[type=text] {
            border: none;
            width: 40px;
        }

        ul {
            list-style-type: none;
        }
    </style>
    <h2>Refine sim run batch</h2>
    <form method="post" action="/sim-run-batch/confirm">
        @csrf  
        <ul>
            @foreach($strategies as $strategy)
            <li>
                <h3>{{ $strategy->name }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>                
                            <th>Description</th>
                            <th>Default</th>
                            <th>Unit</th>
                            <th>Default step</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Step</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($strategy->options as $strategy_option)
                        <tr>
                            <td>{{ $strategy_option->name }}</td>                
                            <td>{{ $strategy_option->description }}</td>
                            <td>{{ $strategy_option->default }}</td>
                            <td>{{ $strategy_option->unit }}</td>
                            <td>{{ $strategy_option->step }}</td>
                            <td><input type="text" name="{{ $strategy_option->id }}-min" /></td>
                            <td><input type="text" name="{{ $strategy_option->id }}-max" /></td>
                            <td><input type="text" name="{{ $strategy_option->id }}-step" /></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>  
            </li>    
            @endforeach
        </ul>
        <input type="submit" value="Submit">
    </form>    
</x-layout>