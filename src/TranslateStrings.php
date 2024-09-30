<?php

namespace BernskioldMedia\Autotranslate;

use const ENT_HTML5;
use const ENT_QUOTES;

use DeepL\Translator;
use Illuminate\Support\Collection;

use function collect;
use function config;
use function html_entity_decode;
use function preg_replace;
use function str_replace;

class TranslateStrings
{
    public function __construct(
        protected Translator $translator
    ) {}

    /**
     * @throws \DeepL\DeepLException
     */
    public function execute(Collection $strings, string $targetLanguage): Collection
    {
        $stringsToTranslate = $this->removePreviouslyTranslatedStrings($strings)
            ->map(fn ($string) => $this->wrapVariablesInTags($string))
            ->map(fn ($string) => $this->wrapExcludedWordsInTags($string));

        $rawTranslations = $this->translator->translateText(
            texts: $stringsToTranslate->values()->toArray(),
            sourceLang: config('autotranslate.source_language'),
            targetLang: $targetLanguage,
            options: $this->getDeepLTextOptions()
        );

        $translations = collect($rawTranslations)
            ->map(fn ($translation) => $translation->text)
            ->map(fn ($translation) => $this->removeTagsFromVariables($translation))
            ->map(fn ($translation) => $this->unencodeHtmlEntities($translation));

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

    protected function unencodeHtmlEntities(string $string): string
    {
        return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    protected function removePreviouslyTranslatedStrings(Collection $strings): Collection
    {
        return $strings->filter(fn ($value, $key) => $value === $key);
    }

    protected function wrapVariablesInTags(string $string): string
    {
        return preg_replace('/:(\w+)/', '<notranslate translate="no">:$1</notranslate>', $string);
    }

    protected function wrapExcludedWordsInTags(string $string): string
    {
        $excludedWords = config('autotranslate.excluded_words');

        if (empty($excludedWords)) {
            return $string;
        }

        $excludedWords = collect($excludedWords)->map(fn ($word) => preg_quote($word))->implode('|');

        return preg_replace('/('.$excludedWords.')/', '<notranslate translate="no">$1</notranslate>', $string);
    }

    protected function removeTagsFromVariables(string $string): string
    {
        $string = str_replace('<notranslate translate="no">', '', $string);
        $string = str_replace('</notranslate>', '', $string);
        $string = str_replace('<\/notranslate>', '', $string);

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
