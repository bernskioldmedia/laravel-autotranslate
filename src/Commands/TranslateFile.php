<?php

namespace BernskioldMedia\Autotranslate\Commands;

use BernskioldMedia\Autotranslate\TranslateStrings;
use function collect;
use DeepL\DeepLException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function json_decode;
use const JSON_THROW_ON_ERROR;
use JsonException;
use function lang_path;

class TranslateFile extends Command
{
    public $signature = 'autotranslate:translate {lang}';

    public $description = 'Translates the selected language file into the selected language.';

    public function handle(TranslateStrings $translator): int
    {
        $language = $this->argument('lang');

        $path = lang_path($language.'.json');

        $this->comment('Translating the file: '.$path);

        try {
            $contents = File::get($path);
            $strings = collect(json_decode($contents, true, 512, JSON_THROW_ON_ERROR));

            $translations = $translator->execute($strings, $language);

            File::put($path, json_encode($translations, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (DeepLException|JsonException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('The file was translated successfully.');

        return self::SUCCESS;
    }
}
