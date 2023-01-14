<?php

namespace BernskioldMedia\Autotranslate;

use DeepL\Translator;
use Illuminate\Support\Collection;

class TranslateStrings
{

    public function __construct(
        protected Translator $translator
    )
    {
    }

    /**
     * @throws \DeepL\DeepLException
     */
    public function execute(Collection $strings, string $targetLanguage): Collection
    {
        $stringsToTranslate = $this->removePreviouslyTranslatedStrings($strings);

        $rawTranslations = $this->translator->translateText(
            texts: $stringsToTranslate->values()->toArray(),
            sourceLang: config('autotranslate.source_language'),
            targetLang: $targetLanguage,
            options: $this->getDeepLTextOptions()
        );

        $translations = collect($rawTranslations)->map(fn($translation) => $translation->text);

        // Add back the original keys to get the Original => Translated array structure.
        $translatedStrings = $stringsToTranslate->keys()->combine($translations);

        return $this->mergeNewTranslationsWithPrevious($translatedStrings, $strings);
    }

    protected function mergeNewTranslationsWithPrevious(Collection $newTranslations, Collection $originalStrings): Collection
    {
        return $originalStrings->mapWithKeys(fn($value, $key) => [
            $key => $newTranslations->get($key) ?? $value
        ]);
    }

    protected function removePreviouslyTranslatedStrings(Collection $strings): Collection
    {
        return $strings->filter(fn($value, $key) => $value === $key);
    }

    protected function getDeepLTextOptions(): array
    {
        return [
            'formality' => $this->getOptionForLanguageOrDefault('formality'),
            'split_sentences' => $this->getOptionForLanguageOrDefault('split_sentences'),
            'preserve_formatting' => $this->getOptionForLanguageOrDefault('preserve_formatting'),
            'tag_handling' => $this->getOptionForLanguageOrDefault('tag_handling'),
            'glossary' => $this->getOptionForLanguageOrDefault('glossary'),
        ];
    }

    protected function getOptionForLanguageOrDefault(string $key)
    {
        return config("autotranslate.language_options.{$key}") ?? config("autotranslate.options.{$key}");
    }

}
