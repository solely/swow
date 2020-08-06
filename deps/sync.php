#!/usr/bin/env php -n
<?php
/**
 * This file is part of Swow
 *
 * @link     https://github.com/swow/swow
 * @contact  twosee <twose@qq.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code
 */

require __DIR__ . '/../tools/dm.php';

use Swow\Tools\DependencyManager;
use function Swow\Tools\error;
use function Swow\Tools\log;
use function Swow\Tools\notice;
use function Swow\Tools\ok;

$syncFromGithub = function (string $orgName, string $repoName, string $sourceDir, string $targetDir): array {
    $fullName = "{$orgName}/{$repoName}";
    log("Sync {$fullName}...");
    $url = "git@github.com:{$fullName}.git";
    $version = DependencyManager::sync($repoName, $url, $sourceDir, $targetDir);
    ok("Sync {$fullName} to {$version}");
    return [$repoName => "* {$fullName}@{$version}\n"];
};

try {
    $deps = [];
    $deps += $syncFromGithub(
        'libcat', 'libcat',
        'libcat', __DIR__ . '/libcat'
    );
} catch (Exception $exception) {
    error($exception->getMessage());
}

$depNames = implode(', ', array_keys($deps));
$depsInfo = implode("\n", $deps);
notice('You can put following content to git commit message:');
log("Sync deps: {$depNames}\n\n{$depsInfo}");