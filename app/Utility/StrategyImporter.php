<?php 

namespace App\Utility;

use App\Models\Strategy;
use App\Models\StrategyOption;

class StrategyImporter 
{
    private $zenbot_location = '';
    
    function __construct(string $zenbot_location) 
    {
        // Check location exists etc
        $this->zenbot_location = $zenbot_location;
    }
    
    public function run() 
    {
        $lines = explode("\n", file_get_contents("$this->zenbot_location/docs/strategies/list-strategies.md"));

        foreach ($lines as $i => $line) {
            if (
                substr($line, 0, 1) !== ' ' &&     // First character is not a space
                substr_count($line, '`') === 0 &&  // No backticks
                substr_count($line, ' ') === 0 &&  // No spaces
                strlen($line) > 1                  // Have more than one character
            ) { // We are starting a new strategy listing
                $strategy = new Strategy(['name' => $line]);                
            } else if (substr_count($line, 'description') === 1) {
                $strategy->description = trim($lines[$i + 1]);

                $strategy->save();
            } else if (substr_count($line, '--') === 1) {
                $strategy->options()->save(new StrategyOption([
                    'name' => $this->extract_option_name(trim($line)),
                    'description' => $this->extract_option_description(trim($line)),
                    'default' => $this->extract_option_default(trim($line))
                ]));
            }
        }
    }

    private function extract_option_name(string $line): string 
    {
        return str_replace(
            '--', 
            '', 
            substr($line, 0, strpos($line, '='))
        );
    }

    private function extract_option_description(string $line): string 
    {
        $length = $this->line_has_default($line)
            ? strpos($line, '(default') - strpos($line, '  ')
            : strlen($line);

        return trim(
            substr(
                $line, 
                strpos($line, '  '), 
                $length
            )
        );
    }

    private function extract_option_default(string $line): string
    {
        if (! $this->line_has_default($line)) {
            return '';
        }
        
        $full_default_string = substr(
            $line, 
            strpos($line, '(default: '), 
            strlen($line)
        );

        // Now get rid of everything except the actual default value
        return str_replace(
            ')', 
            '', 
            str_replace(
                '(default: ', 
                '', 
                $full_default_string
            )
        );
    }

    private function extract_option_unit(string $line): string
    {
        return 'NOT IMPLEMENTED';
    }

    private function select_step_size(string $option_default, string $option_unit): int
    {
        // Has it got a decimal point? I.e. if is 1.6 default, step size be 0.1
        // Is it a 'known' unit? I.e. if minutes, maybe step size be 15 minutes?
        // Maybe qty digits is relevant? So step sixe for 1000 might be 100?

        // This can be overridden by user anytime anyway...
        return 1;
    }

    private function line_has_default(string $line): bool
    {
        return substr_count($line, '(default') === 1;
    }

    // Make A StrategiesDocLine class
    // Make A StrategiesDocOptionLine class
}