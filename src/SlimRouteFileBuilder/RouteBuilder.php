<?php
namespace District5\SlimRouteFileBuilder;

/**
 * Class RouteBuilder
 * @package District5\SlimRouteFileBuilder
 * @noinspection PhpUnused
 */
class RouteBuilder
{
    /**
     * Get all route files in a given directory (recursive).
     *
     * @param string $dir
     * @param array $results
     * @return array
     */
    protected static function getRouteFiles(string $dir, array &$results = []): array
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value !== "." && $value !== "..") {
                self::getRouteFiles($path, $results);
            }
        }
        return $results;
    }

    /**
     * @param bool $rescanAndWrite
     * @param string $baseDirectory
     * @param string|null $fileEndsWith
     * @return string
     * @noinspection PhpUnused
     */
    public static function scanRoutes(bool $rescanAndWrite, string $baseDirectory, ?string $fileEndsWith = null): string
    {
        $ds = DIRECTORY_SEPARATOR;
        $baseDirectory = rtrim($baseDirectory, $ds);

        $routesFile = $baseDirectory . $ds . 'app' . $ds . 'bootstrap.php';
        if ($rescanAndWrite === false) {
            return $routesFile;
        }

        $routes = [];
        $matchingFiles = self::getRouteFiles($baseDirectory . $ds . 'app' . $ds . 'routes/');
        $routes = array_merge_recursive($routes, $matchingFiles);

        $fileContent = '<?php' . PHP_EOL;
        $fileContent .= '$sfRouteBuildingDir = __DIR__;' . PHP_EOL . PHP_EOL;
        foreach ($routes as $route) {
            if (substr($route, 0, strlen($baseDirectory)) !== $baseDirectory) {
                continue;
            }
            $route = substr($route, strlen($baseDirectory));
            $route = ltrim($route, $ds);
            if (substr($route, 0, 4) !== 'app' . $ds) {
                continue;
            }
            $route = substr($route, 4);
            if ($fileEndsWith === null || substr($route, (0-strlen($fileEndsWith))) === $fileEndsWith) {
                $fileContent .= sprintf('include $sfRouteBuildingDir . \'%s%s\';', $ds, $route) . PHP_EOL;
            }
        }
        file_put_contents($routesFile, $fileContent);
        return $routesFile;
    }
}
