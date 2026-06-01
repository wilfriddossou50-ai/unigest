<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$report = [];

// 1. Check Views
$report['missing_views'] = [];
$controllersDir = __DIR__ . '/app/Http/Controllers';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllersDir));

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all("/view\(['\"]([^'\"]+)['\"]/", $content, $matches);
        foreach ($matches[1] as $viewName) {
            $viewPath = __DIR__ . '/resources/views/' . str_replace('.', '/', $viewName) . '.blade.php';
            if (!file_exists($viewPath)) {
                $report['missing_views'][] = [
                    'controller' => $file->getFilename(),
                    'view' => $viewName
                ];
            }
        }
    }
}

// 2. Models vs Migrations
$report['models_issues'] = [];
$modelsDir = __DIR__ . '/app/Models';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($modelsDir));

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $className = 'App\\Models\\' . $file->getBasename('.php');
        if (class_exists($className)) {
            try {
                $model = new $className;
                $table = $model->getTable();
                if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                    $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
                    $fillable = $model->getFillable();
                    
                    $missingInDb = array_diff($fillable, $columns);
                    if (!empty($missingInDb)) {
                        $report['models_issues'][$className] = "Fillable fields not in DB: " . implode(', ', $missingInDb);
                    }
                } else {
                    $report['models_issues'][$className] = "Table {$table} does not exist.";
                }
            } catch (\Exception $e) {
                // Ignore abstract or trait
            }
        }
    }
}

file_put_contents('audit_report.json', json_encode($report, JSON_PRETTY_PRINT));
echo "Audit complete.\n";
