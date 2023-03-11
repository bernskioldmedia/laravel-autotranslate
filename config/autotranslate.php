<?php

return [

    /**
     * The source language that your application language is in before translation.
     */
    'source_language' => 'en',

    /**
     * This is your DeepL API key.
     */
    'api_key' => env('AUTOTRANSLATE_DEEPL_KEY'),

    /**
     * DeepL supports settings for each language to translate to.
     * These are the default settings that will be applied
     * unless you override it for a specific language below.
     *
     * See the DeepL PHP SDK for more information: https://github.com/DeepLcom/deepl-php
     */
    'options' => [

        // Choose the formality of the text.
        // This setting is only available for certain languages: https://github.com/DeepLcom/deepl-php#listing-available-languages
        // Available options: 'less', 'more', 'default', 'prefer_less', 'prefer_more'
        'formality' => 'default',

        // Specify how input text should be split into sentences.
        // Available options: 'on', (default) 'off', 'nonewlines'
        'split_sentences' => 'on',

        // Controls automatic-formatting-correction.
        // Set to true to prevent automatic-correction of formatting.
        'preserve_formatting' => false,

        // Type of tags to parse before translation.
        // Available options: 'html', 'xml'
        'tag_handling' => 'html',

        // The ID of the DeepL glossary to use.
        'glossary' => null,
    ],

    /**
     * You can override the default options for specific languages.
     * The key is the language code and the value is an array of options.
     */
    'language_options' => [

        //      'de' => [
        //          'formality' => 'less',
        //      ],

    ],

    /**
     * You can exclude words from being translated by adding
     * the raw string to this array.
     */
    'excluded_words' => [
        // 'raw string',
    ],

];
