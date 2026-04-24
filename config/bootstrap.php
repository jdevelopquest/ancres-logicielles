<?php
declare(strict_types=1);

/**
 * Écrit par gpt5
 * Bootstrap de configuration :
 * Lit le fichier .env.local (à la racine du projet)
 * Exporte les variables via putenv(), $_ENV et $_SERVER
 */

(function (string $envFile = '.env.local', bool $override = true): void {
    $projectRoot = dirname(__DIR__); // ../
    $path = $projectRoot . DIRECTORY_SEPARATOR . $envFile;

    if (!is_file($path) || !is_readable($path)) {
        return; // Silencieux si le fichier n'existe pas
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);

        // Ignore commentaires et lignes vides
        if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
            continue;
        }

        // Support "export KEY=VALUE"
        if (str_starts_with($line, 'export ')) {
            $line = substr($line, 7);
        }

        // Doit contenir un "="
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }

        $key = trim(substr($line, 0, $pos));
        $value = trim(substr($line, $pos + 1));

        if ($key === '') {
            continue;
        }

        // Retire les guillemets entourant la valeur et gère l'échappement
        if (($value[0] ?? '') === '"' && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
            $value = str_replace(['\\"', '\\n', '\\r', '\\t', '\\\\'], ['"', "\n", "\r", "\t", '\\'], $value);
        } elseif (($value[0] ?? '') === "'" && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        } else {
            // Retire d'éventuels commentaires en fin de ligne : KEY=VALUE # comment
            $hashPos = strpos($value, ' #');
            if ($hashPos !== false) {
                $value = substr($value, 0, $hashPos);
            }
            $value = rtrim($value);
        }

        // N'écrase pas si la variable existe déjà quand override=false
        $alreadySet = getenv($key) !== false || array_key_exists($key, $_ENV) || array_key_exists($key, $_SERVER);
        if ($alreadySet && !$override) {
            continue;
        }

        // Export dans l'environnement
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        // Aligne $_SERVER si non défini ou si override
        if (!array_key_exists($key, $_SERVER) || $override) {
            $_SERVER[$key] = $value;
        }
    }
})();
