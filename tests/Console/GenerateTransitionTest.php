<?php

namespace Transitions\Console;

use Illuminate\Filesystem\Filesystem;
use Transitions\TestCase;

class GenerateTransitionTest extends TestCase
{

    /** @var  Filesystem */
    protected $files;

    public function setUp() : void
    {

        parent::setUp();
        $this->files = $this->app->make(Filesystem::class);
        $this->withoutMockingConsoleOutput();
    }

    /** @test */
    public function itGeneratesATransitionClass() : void
    {

        $path = app_path('Http/Transitions/TestTransition.php');
        $this->artisan('make:transition', ['name' => 'TestTransition']);
        $this->assertFileExists($path);
        $contents = $this->files->get($path);
        $this->assertContains('namespace App\Http\Transitions;', $contents);
        $this->assertContains('class TestTransition extends Transition', $contents);
        $this->assertContains('public function transformRequest', $contents);
        $this->assertContains('public function transformResponse', $contents);
    }

    /** @test */
    public function itGeneratesARequestOnlyTransitionClass() : void
    {

        $path = app_path('Http/Transitions/TestRequestTransition.php');
        $this->artisan('make:transition', ['name' => 'TestRequestTransition', '--request-only' => true]);
        $this->assertFileExists($path);
        $contents = $this->files->get($path);
        $this->assertContains('public function transformRequest', $contents);
        $this->assertNotContains('public function transformResponse', $contents);
    }

    /** @test */
    public function itGeneratesAResponseOnlyTransitionClass() : void
    {

        $path = app_path('Http/Transitions/TestResponseTransition.php');
        $this->artisan('make:transition', ['name' => 'TestResponseTransition', '--response-only' => true]);
        $this->assertFileExists($path);
        $contents = $this->files->get($path);
        $this->assertContains('public function transformResponse', $contents);
        $this->assertNotContains('public function transformRequest', $contents);
    }

    public function tearDown() : void
    {
        $this->files->cleanDirectory(app_path('Http/Transitions'));
    }
}
