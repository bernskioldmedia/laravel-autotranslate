<?php

use BernskioldMedia\Autotranslate\TranslateStrings;
use DeepL\TextResult;
use DeepL\Translator;
use Mockery\MockInterface;

it('can translate strings', function () {
    $this->partialMock(Translator::class, function (MockInterface $mock) {
        $mock->shouldReceive('translateText')
            ->withAnyArgs()
            ->once()
            ->andReturn([
                new TextResult('Min ej översatta teststräng', 'en'),
            ]);
    });

    $results = app(TranslateStrings::class)->execute(
        collect([
            'My untranslated test string' => 'My untranslated test string',
        ]),
        'sv'
    );

    expect($results)->toEqual(collect([
        'My untranslated test string' => 'Min ej översatta teststräng',
    ]));
});

it('skips translating previously translated strings', function () {
    $this->partialMock(Translator::class, function (MockInterface $mock) {
        $mock->shouldReceive('translateText')
            ->withAnyArgs()
            ->once()
            ->andReturn([
                new TextResult('Min ej översatta teststräng', 'en'),
            ]);
    });

    $results = app(TranslateStrings::class)->execute(
        collect([
            'My untranslated test string' => 'My untranslated test string',
            'My already translated test string' => 'Min redan översatta teststräng',
        ]),
        'sv'
    );

    expect($results)->toEqual(collect([
        'My untranslated test string' => 'Min ej översatta teststräng',
        'My already translated test string' => 'Min redan översatta teststräng',
    ]));
});
