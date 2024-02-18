<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class MakeFilter extends Command
{
    protected $signature = 'make:filter {name : The name of the filter}';

    protected $description = 'Create a new filter';

    public function handle(): void
    {
        $name = $this->argument('name');
        $segments = explode('/', $name);
        $filterName = array_pop($segments);
        $folderPath = implode('/', $segments);

        $folderPath = trim($folderPath, '/');
        $basePath = base_path("app/Filters/{$folderPath}");
        $path = "{$basePath}/{$filterName}.php";

        try {
            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }

            file_put_contents($path, $this->getFilterContent($folderPath, $filterName));
            $this->info('Filter created successfully!');
        } catch (\Exception $e) {
            $this->error("Error creating filter: {$e->getMessage()}");
        }
    }

    /**
     * @param $folderPath
     * @param $filterName
     * @return string
     */
    protected function getFilterContent($folderPath, $filterName): string
    {
        $namespace = 'App\Filters';

        if ($folderPath) {
            $namespace .= '\\' . str_replace('/', '\\', $folderPath);
        }

        return "<?php\n\nnamespace {$namespace};\n\nuse Closure;\n\nclass {$filterName}\n{\n    public function handle(\$query, Closure \$next)\n    {\n        // Your filter implementation goes here\n        return \$next(\$query);\n    }\n}\n";
    }
}
