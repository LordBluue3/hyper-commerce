<?php

/**
 * Copyright (c) 2026 Mikael Oliveira.
 * 
 * @author Mikael Oliveira
 * @license Proprietary
 */

declare(strict_types=1);

namespace App\Command\Framework;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

#[Command]
class StubsCommand extends HyperfCommand
{
    public function __construct()
    {
        parent::__construct('framework:fix-stubs');
    }

    public function configure(): void
    {
        parent::configure();
        $this->setDescription('Padroniza headers dos arquivos da pasta stubs');
    }

    public function handle(): void
    {
        $stubPath = BASE_PATH . '/stubs';

        if (! is_dir($stubPath)) {
            $this->error('Pasta stubs não encontrada.');
            return;
        }

        $directory = new RecursiveDirectoryIterator($stubPath);
        $iterator = new RecursiveIteratorIterator($directory);

        /** @var RegexIterator $files */
        $files = new RegexIterator($iterator, '/^.+\.stub$/i', RegexIterator::GET_MATCH);

        $header = <<<PHP
<?php

/**
 * Copyright (c) 2026 Mikael Oliveira.
 * 
 * @author Mikael Oliveira
 * @license Proprietary
 */

declare(strict_types=1);

PHP;

        foreach ($files as $file) {
            $filePath = $file[0];

            $content = file_get_contents($filePath);

            if ($content === false) {
                $this->error("Erro ao ler: {$filePath}");
                continue;
            }

            $content = preg_replace(
                '/^.*?declare\s*\(\s*strict_types\s*=\s*1\s*\);\s*/s',
                '',
                $content
            );

            if ($content === null) {
                $this->error("Erro ao processar: {$filePath}");
                continue;
            }

            file_put_contents($filePath, $header.PHP_EOL . ltrim($content));

            $this->info("Atualizado: {$filePath}");
        }

        $this->info('✔ Todos os stubs foram padronizados.');
    }
}
