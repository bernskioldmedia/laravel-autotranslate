<?php

use DeepL\TextResult;
use DeepL\Translator;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use function Pest\testDirectory;

it('can translate the file', function () {
    File::spy();

    File::shouldReceive('get')
        ->with(lang_path('sv.json'))
        ->andReturn(file_get_contents(testDirectory('fixtures/sample-translation-file.json')));

    $this->partialMock(Translator::class, function (MockInterface $mock) {
        $mock->shouldReceive('translateText')
            ->withAnyArgs()
            ->once()
            ->andReturn([
                new TextResult('Min ej översatta teststräng', 'en'),
            ]);
    });

    $this->artisan('autotranslate:translate', ['lang' => 'sv'])
        ->assertSuccessful();

    File::shouldHaveReceived('put')
        ->once()
        ->with(lang_path('sv.json'), json_encode([
            'My untranslated test string' => 'Min ej översatta teststräng',
            'My translated test string' => 'Min översatta teststräng',
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
});
