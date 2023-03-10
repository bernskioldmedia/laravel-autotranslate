<?php

namespace BernskioldMedia\Autotranslate;

use DeepL\Translator;
use Illuminate\Support\Collection;
use function preg_replace;
use function str_replace;

class TranslateStrings
{
    public function __construct(
        protected Translator $translator
    ) {
    }

    /**
     * @throws \DeepL\DeepLException
     */
    public function execute(Collection $strings, string $targetLanguage): Collection
    {
        $stringsToTranslate = $this->removePreviouslyTranslatedStrings($strings)
            ->map(fn ($string) => $this->wrapVariablesInTags($string));

        $rawTranslations = $this->translator->translateText(
            texts: $stringsToTranslate->values()->toArray(),
            sourceLang: config('autotranslate.source_language'),
            targetLang: $targetLanguage,
            options: $this->getDeepLTextOptions()
        );

        $translations = collect($rawTranslations)
            ->map(fn ($translation) => $translation->text)
            ->map(fn ($translation) => $this->removeTagsFromVariables($translation));

        // Add back the original keys to get the Original => Translated array structure.
        $translatedStrings = $stringsToTranslate->keys()->combine($translations);

        return $this->mergeNewTranslationsWithPrevious($translatedStrings, $strings);
    }

    protected function mergeNewTranslationsWithPrevious(Collection $newTranslations, Collection $originalStrings): Collection
    {
        return $originalStrings->mapWithKeys(fn ($value, $key) => [
            $key => $newTranslations->get($key) ?? $value,
        ]);
    }

    protected function removePreviouslyTranslatedStrings(Collection $strings): Collection
    {
        return $strings->filter(fn ($value, $key) => $value === $key);
    }

    protected function wrapVariablesInTags(string $string): string
    {
        return preg_replace('/:(\w+)/', '<NOTRANSLATE>:$1</NOTRANSLATE>', $string);
    }

    protected function removeTagsFromVariables(string $string): string
    {
        $string = str_replace('<NOTRANSLATE>', '', $string);
        $string = str_replace('</NOTRANSLATE>', '', $string);
        $string = str_replace('<\/NOTRANSLATE>', '', $string);

        return $string;
    }

    protected function getDeepLTextOptions(): array
    {
        return [
            'formality' => $this->getOptionForLanguageOrDefault('formality'),
            'split_sentences' => $this->getOptionForLanguageOrDefault('split_sentences'),
            'preserve_formatting' => $this->getOptionForLanguageOrDefault('preserve_formatting'),
            'tag_handling' => $this->getOptionForLanguageOrDefault('tag_handling'),
            'glossary' => $this->getOptionForLanguageOrDefault('glossary'),
            'ignore_tags' => 'NOTRANSLATE',
        ];
    }

    protected function getOptionForLanguageOrDefault(string $key)
    {
        return config("autotranslate.language_options.{$key}") ?? config("autotranslate.options.{$key}");
    }
}
