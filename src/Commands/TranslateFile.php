<?php

namespace BernskioldMedia\Autotranslate\Commands;

use BernskioldMedia\Autotranslate\TranslateStrings;
use DeepL\DeepLException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use JsonException;
use function collect;
use function json_decode;
use function lang_path;
use const JSON_THROW_ON_ERROR;

class TranslateFile extends Command
{
    public $signature = 'autotranslate:translate {lang} {deeplCode?}';

    public $description = 'Translates the selected language file into the selected language.';

    public function handle(TranslateStrings $translator): int
    {
        $language = $this->argument('lang');
        $deeplLanguageCode = $this->argument('deeplCode', $language);

        $path = lang_path($language . '.json');
        $errors = [];

        $this->comment('Translating the file: ' . $path);

        try {
            $contents = File::get($path);
            $originals = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

            $translations = collect($originals)
                ->chunk(50)
                ->map(function ($chunk) use ($translator, $deeplLanguageCode, &$errors) {
                    try {
                        return $translator->execute($chunk, $deeplLanguageCode)
                            ->map(fn($value, $key) => [
                                'original' => $key,
                                'translation' => $value,
                            ]);
                    } catch (DeepLException $e) {
                        $errors[] = $e->getMessage();

                        return collect();
                    }
                })
                ->flatten(1)
                ->pluck('translation', 'original');

            $stringsToSave = collect($originals)->merge($translations);

            File::put($path, json_encode($stringsToSave, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (JsonException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if (!empty($errors)) {
            $this->error('The following errors occurred:');
            foreach ($errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $this->info('The file was translated successfully.');

        return self::SUCCESS;
    }
}
