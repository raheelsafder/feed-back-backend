<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class MakeService extends Command
{
    protected $signature = 'make:service {name : The name of the service}';

    protected $description = 'Create a new service';

    public function handle(): void
    {
        $name = $this->argument('name');
        $segments = explode('/', $name);
        $serviceName = array_pop($segments);
        $folderPath = implode('/', $segments);

        $folderPath = trim($folderPath, '/');
        $basePath = base_path("app/Services/{$folderPath}");
        $path = "{$basePath}/{$serviceName}.php";

        try {
            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }

            file_put_contents($path, $this->getServiceContent($folderPath, $serviceName));
            $this->info('Service created successfully!');
        } catch (\Exception $e) {
            $this->error("Error creating service: {$e->getMessage()}");
        }
    }

    /**
     * @param $folderPath
     * @param $serviceName
     * @return string
     */
    protected function getServiceContent($folderPath, $serviceName): string
    {
        $namespace = 'App\Services';

        if ($folderPath) {
            $namespace .= '\\' . str_replace('/', '\\', $folderPath);
        }

        return "<?php\n\nnamespace {$namespace};\n\nclass {$serviceName}\n{\n    // Your service implementation goes here\n}\n";
    }

}
