<?php
$routes = file_get_contents('routes/web.php');
$startTag = "Route::get('/', function () {";
$endTag = "})->name('welcome');";
$startPos = strpos($routes, $startTag);
$endPos = strpos($routes, $endTag, $startPos);

if ($startPos !== false && $endPos !== false) {
    $before = substr($routes, 0, $startPos);
    $after = substr($routes, $endPos + strlen($endTag));
    $replacement = "Route::get('/', [WelcomeController::class, 'index'])->name('welcome');";
    $newRoutes = $before . $replacement . $after;
    file_put_contents('routes/web.php', $newRoutes);
    echo "Updated routes/web.php\n";
} else {
    echo "Failed to find Route::get('/')\n";
}
