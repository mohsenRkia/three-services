<?php

declare(strict_types=1);

namespace Codeception\Extension;

use Codeception\Event\StepEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Exception\ExtensionException;
use Codeception\Extension;
use Codeception\Lib\Interfaces\ScreenshotSaver;
use Codeception\Module;
use Codeception\Module\WebDriver;
use Codeception\Step;
use Codeception\Step\Comment as CommentStep;
use Codeception\Test\Descriptor;
use Codeception\Util\FileSystem;
use Codeception\Util\Template;
use DateTime;
use DirectoryIterator;
use Exception;
use Symfony\Contracts\EventDispatcher\Event;

use function array_diff;
use function array_key_exists;
use function array_keys;
use function array_merge;
use function array_unique;
use function basename;
use function codecept_output_dir;
use function codecept_relative_path;
use function dirname;
use function file_put_contents;
use function in_array;
use function is_array;
use function is_dir;
use function mkdir;
use function preg_match;
use function preg_replace;
use function sprintf;
use function str_pad;
use function str_replace;
use function strcasecmp;
use function strlen;
use function substr;
use function trim;
use function ucfirst;
use function uniqid;

/**
 * Saves a screenshot of each step in acceptance tests and shows them as a slideshow on one HTML page (here's an [example](https://codeception.com/images/recorder.gif)).
 * Works only for suites with WebDriver module enabled.
 *
 * The screenshots are saved to `tests/_output/record_*` directories, open `index.html` to see them as a slideshow.
 *
 * #### Installation
 *
 * Add this to the list of enabled extensions in `codeception.yml` or `Acceptance.suite.yml`:
 *
 * ``` yaml
 * extensions:
 *     enabled:
 *         - Codeception\Extension\Recorder
 * ```
 *
 * #### Configuration
 *
 * * `delete_successful` (default: true) - delete screenshots for successfully passed tests  (i.e. log only failed and errored tests).
 * * `module` (default: WebDriver) - which module for screenshots to use. Set `AngularJS` if you want to use it with AngularJS module. Generally, the module should implement `Codeception\Lib\Interfaces\ScreenshotSaver` interface.
 * * `ignore_steps` (default: []) - array of step names that should not be recorded (given the step passed), * wildcards supported. Meta steps can also be ignored.
 * * `success_color` (default: success) - bootstrap values to be used for color representation for passed tests
 * * `failure_color` (default: danger) - bootstrap values to be used for color representation for failed tests
 * * `error_color` (default: dark) - bootstrap values to be used for color representation for scenarios where there's an issue occurred while generating a recording
 * * `delete_orphaned` (default: false) - delete recording folders created via previous runs
 * * `include_microseconds` (default: false) - enable microsecond precision for recorded step time details
 *
 * #### Examples:
 *
 * ``` yaml
 * extensions:
 *     enabled:
 *         - Codeception\Extension\Recorder:
 *             module: AngularJS # enable for Angular
 *             delete_successful: false # keep screenshots of successful tests
 *             ignore_steps: [have, grab*]
 * ```
 * #### Skipping recording of steps with annotations
 *
 * It is also possible to skip recording of steps for specified tests by using the `@skipRecording` annotation.
 *
 * ```php
 * /**
 * * @skipRecording login
 * * @skipRecording amOnUrl
 * *\/
 * public function testLogin(AcceptanceTester $I)
 * {
 *     $I->login();
 *     $I->amOnUrl('https://codeception.com');
 * }
 * ```
 */
class Recorder extends Extension
{
    protected array $config = [
        'delete_successful'    => true,
        'module'               => 'WebDriver',
        'template'             => null,
        'animate_slides'       => true,
        'ignore_steps'         => [],
        'success_color'        => 'success',
        'failure_color'        => 'danger',
        'error_color'          => 'dark',
        'delete_orphaned'      => false,
        'include_microseconds' => false,
    ];

    protected string $template = <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recorder Result</title>

    <!-- Bootstrap Core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
        }
        .active {
            height: 100%;
        }
        .carousel-caption {
            background: rgba(0,0,0,0.8);
        }
        .carousel-caption.error {
            background: #c0392b !important;
        }
        .carousel-item {
            min-height: 100vh;
        }
        .fill {
            width: 100%;
            height: 100%;
            text-align: center;
            overflow-y: scroll;
            background-position: top;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            background-size: cover;
            -o-background-size: cover;
        }
        .gradient-right {
             background:
                linear-gradient(to left, rgba(0,0,0,.4), rgba(0,0,0,.0))
        }
        .gradient-left {
            background:
                linear-gradient(to right, rgba(0,0,0,.4), rgba(0,0,0,.0))
        }
    </style>
</head>
<body>
    <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="../records.html"></span>Recorded Tests</a>
        </div>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <span class="navbar-text">{{feature}}</span>
            </ul>
            <span class="navbar-text">{{test}}</span>
        </div>
    </nav>
    <header id="steps" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            {{indicators}}
        </ol>

        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
            {{slides}}
        </div>

        <!-- Controls -->
        <a class="carousel-control-prev gradient-left" href="#steps" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="false"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next gradient-right" href="#steps" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="false"></span>
            <span class="sr-only">Next</span>
        </a>
    </header>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        wrap: true,
        interval: false
    })

    $(document).bind('keyup', function(e) {
      if(e.keyCode==39){
      jQuery('a.carousel-control.right').trigger('click');
      }

      else if(e.keyCode==37){
      jQuery('a.carousel-control.left').trigger('click');
      }

    });

    </script>

</body>

</html>
EOF;

    protected string $indicatorTemplate = <<<EOF
<li data-target="#steps" data-slide-to="{{step}}" class="{{isActive}}"></li>
EOF;

    protected string $indexTemplate = <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recorder Results Index</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Recorded Tests
            </a>
        </div>
    </nav>
    <div class="container py-4">
        <h1>Record #{{seed}}</h1>
        <ul>
            {{records}}
        </ul>
    </div>
</body>

</html>

EOF;

    protected string $slidesTemplate = <<<EOF
<div class="carousel-item {{isActive}}">
    <img class="mx-auto d-block mh-100" src="{{image}}">
    <div class="carousel-caption {{isError}}">
        <h5>{{caption}}</h5>
        <p>Step finished at <span style="color: #3498db">"{{timeStamp}}"</span></p>
    </div>
</div>
EOF;

    public static array $events = [
        Events::SUITE_BEFORE => 'beforeSuite',
        Events::SUITE_AFTER  => 'afterSuite',
        Events::TEST_BEFORE  => 'before',
        Events::TEST_ERROR   => 'persist',
        Events::TEST_FAIL    => 'persist',
        Events::TEST_SUCCESS => 'cleanup',
        Events::STEP_AFTER   => 'afterStep',
    ];

    protected ?Module $webDriverModule = null;

    protected ?string $dir = null;

    protected array $slides = [];

    protected int $stepNum = 0;

    protected ?string $seed = null;

    protected array $seeds = [];

    protected array $recordedTests = [];

    protected array $skipRecording = [];

    protected array $errorMessages = [];

    protected bool $colors = false;

    protected bool $ansi = false;

    protected array $timeStamps = [];

    private ?string $dateFormat = null;

    public function beforeSuite(): void
    {
        $this->webDriverModule = null;
        if (!$this->hasModule($this->config['module'])) {
            $this->writeln('Recorder is disabled, no available modules');

            return;
        }

        $this->seed = uniqid();
        $this->seeds[] = $this->seed;
        $this->webDriverModule = $this->getModule($this->config['module']);
        $this->skipRecording = [];
        $this->errorMessages = [];
        $this->dateFormat = $this->config['include_microseconds'] ? 'Y-m-d\TH:i:s.uP' : DATE_ATOM;
        $this->ansi = !isset($this->options['no-ansi']);
        $this->colors = !isset($this->options['no-colors']);

        if (!$this->webDriverModule instanceof ScreenshotSaver) {
            throw new ExtensionException(
                $this,
                'You should pass module which implements ' . ScreenshotSaver::class . ' interface'
            );
        }

        $this->writeln(
            sprintf(
                '⏺ <bold>Recording</bold> ⏺ step-by-step screenshots will be saved to <info>%s</info>',
                codecept_output_dir()
            )
        );
        $this->writeln("Directory Format: <debug>record_{$this->seed}_{filename}_{testname}</debug> ----");
    }

    public function afterSuite(): void
    {
        if (!$this->webDriverModule instanceof Module) {
            return;
        }
        $links = '';

        if ($this->slides !== []) {
            foreach ($this->recordedTests as $suiteName => $suite) {
                $links .= "<ul><li><b>{$suiteName}</b></li><ul>";
                foreach ($suite as $fileName => $tests) {
                    $links .= "<li>{$fileName}</li><ul>";

                    foreach ($tests as $test) {
                        $links .= in_array($test['path'], $this->skipRecording, true)
                            ? "<li class=\"text{$this->config['error_color']}\">{$test['name']}</li>\n"
                            : '<li class="text-' . $this->config[$test['status'] . '_color']
                            . "\"><a href='{$test['index']}'>{$test['name']}</a></li>\n";
                    }

                    $links .= '</ul>';
                }
                $links .= '</ul></ul>';
            }

            $indexHTML = (new Template($this->indexTemplate))
                ->place('seed', $this->seed)
                ->place('records', $links)
                ->produce();

            try {
                file_put_contents(codecept_output_dir() . 'records.html', $indexHTML);
            } catch (Exception $exception) {
                $this->writeln(
                    "⏺ An exception occurred while saving records.html: <info>{$exception->getMessage()}</info>"
                );
            }

            $this->writeln('⏺ Records saved into: <info>file://' . codecept_output_dir() . 'records.html</info>');
        }

        foreach ($this->errorMessages as $message) {
            $this->writeln($message);
        }
    }

    public function before(TestEvent $event): void
    {
        if (!$this->webDriverModule instanceof Module) {
            return;
        }
        $this->dir = null;
        $this->stepNum = 0;
        $this->slides = [];
        $this->timeStamps = [];

        $this->dir = codecept_output_dir() . "record_{$this->seed}_{$this->getTestName($event)}";
        $testPath = codecept_relative_path(Descriptor::getTestFullName($event->getTest()));

        try {
            !is_dir($this->dir) && !mkdir($this->dir) && !is_dir($this->dir);
        } catch (Exception $exception) {
            $this->skipRecording[] = $testPath;
            $this->appendErrorMessage(
                $testPath,
                "⏺ An exception occurred while creating directory: <info>{$this->dir}</info>"
            );
        }
    }

    public function cleanup(TestEvent $event): void
    {
        if ($this->config['delete_orphaned']) {
            $recordingDirectories = [];
            $directories = new DirectoryIterator(codecept_output_dir());

            // getting a list of currently present recording directories
            foreach ($directories as $directory) {
                preg_match('/^record_(.*?)_[^\n]+.php_[^\n]+$/', $directory->getFilename(), $match);
                if (isset($match[1])) {
                    $recordingDirectories[$match[1]][] = codecept_output_dir() . $directory->getFilename();
                }
            }

            // removing orphaned recording directories
            foreach (array_diff(array_keys($recordingDirectories), $this->seeds) as $orphanedSeed) {
                foreach ($recordingDirectories[$orphanedSeed] as $orphanedDirectory) {
                    FileSystem::deleteDir($orphanedDirectory);
                }
            }
        }

        if (!$this->webDriverModule instanceof Module || !$this->dir) {
            return;
        }
        if (!$this->config['delete_successful']) {
            $this->persist($event);

            return;
        }

        // deleting successfully executed tests
        FileSystem::deleteDir($this->dir);
    }

    public function persist(TestEvent $event): void
    {
        if (!$this->webDriverModule instanceof Module) {
            return;
        }
        $indicatorHtml = '';
        $slideHtml = '';
        $testName = $this->getTestName($event);
        $testPath = codecept_relative_path(Descriptor::getTestFullName($event->getTest()));
        $dir = codecept_output_dir() . "record_{$this->seed}_$testName";
        $status = 'success';

        if (strcasecmp($this->dir ?? '', $dir) !== 0) {
            $filename = str_pad('0', 3, '0', STR_PAD_LEFT) . '.png';

            try {
                !is_dir($dir) && !mkdir($dir) && !is_dir($dir);
                $this->dir = $dir;
            } catch (Exception) {
                $this->skipRecording[] = $testPath;
                $this->appendErrorMessage(
                    $testPath,
                    "⏺ An exception occurred while creating directory: <info>{$dir}</info>"
                );
            }

            $this->slides = [];
            $this->timeStamps = [];
            $this->slides[$filename] = new Step\Action('encountered an unexpected error prior to the test execution');
            $this->timeStamps[$filename] = (new DateTime())->format($this->dateFormat);
            $status = 'error';

            try {
                if ($this->webDriverModule->webDriver === null) {
                    throw new ExtensionException($this, 'Failed to save screenshot as webDriver is not set');
                }

                $this->webDriverModule->webDriver->takeScreenshot($this->dir . DIRECTORY_SEPARATOR . $filename);
            } catch (Exception) {
                $this->appendErrorMessage(
                    $testPath,
                    "⏺ Unable to capture a screenshot for <info>{$testPath}/before</info>"
                );
            }
        }

        if (!in_array($testPath, $this->skipRecording, true)) {
            foreach ($this->slides as $i => $step) {
                /** @var Step $step */
                if ($step->hasFailed()) {
                    $status = 'failure';
                }

                $indicatorHtml .= (new Template($this->indicatorTemplate))
                    ->place('step', (int)$i)
                    ->place('isActive', (int)$i !== 0 ? '' : 'active')
                    ->produce();

                $slideHtml .= (new Template($this->slidesTemplate))
                    ->place('image', $i)
                    ->place('caption', $step->getHtml('#3498db'))
                    ->place('isActive', (int)$i !== 0 ? '' : 'active')
                    ->place('isError', $status === 'success' ? '' : 'error')
                    ->place('timeStamp', $this->timeStamps[$i])
                    ->produce();
            }

            $html = (new Template($this->template))
                ->place('indicators', $indicatorHtml)
                ->place('slides', $slideHtml)
                ->place('feature', ucfirst((string) $event->getTest()->getFeature()))
                ->place('test', Descriptor::getTestSignature($event->getTest()))
                ->place('carousel_class', $this->config['animate_slides'] ? ' slide' : '')
                ->produce();

            $indexFile = $this->dir . DIRECTORY_SEPARATOR . 'index.html';
            $environment = $event->getTest()->getMetadata()->getCurrent('env') ?: '';
            $suite = ucfirst(basename(dirname($event->getTest()->getMetadata()->getFilename())));
            $testName = basename($event->getTest()->getMetadata()->getFilename());

            try {
                file_put_contents($indexFile, $html);
            } catch (Exception $exception) {
                $this->skipRecording[] = $testPath;
                $this->appendErrorMessage(
                    $testPath,
                    "⏺ An exception occurred while saving index.html for <info>{$testPath}: "
                    . "{$exception->getMessage()}</info>"
                );
            }

            $this->recordedTests["{$suite} ({$environment})"][$testName][] = [
                'name' => $event->getTest()->getMetadata()->getName(),
                'path' => $testPath,
                'status' => $status,
                'index' => substr($indexFile, strlen(codecept_output_dir())),
            ];
        }
    }

    public function afterStep(StepEvent $event): void
    {
        if (!$this->webDriverModule instanceof Module || $this->dir === null) {
            return;
        }

        if ($event->getStep() instanceof CommentStep) {
            return;
        }

        // only taking the ignore step into consideration if that step has passed
        if ($this->isStepIgnored($event) && !$event->getStep()->hasFailed()) {
            return;
        }

        $filename = str_pad((string)$this->stepNum, 3, '0', STR_PAD_LEFT) . '.png';

        try {
            if ($this->webDriverModule->webDriver === null) {
                throw new ExtensionException($this, 'Failed to save screenshot as webDriver is not set');
            }

            $this->webDriverModule->webDriver->takeScreenshot($this->dir . DIRECTORY_SEPARATOR . $filename);
        } catch (Exception) {
            $testPath = codecept_relative_path(Descriptor::getTestFullName($event->getTest()));
            $this->appendErrorMessage(
                $testPath,
                "⏺ Unable to capture a screenshot for <info>{$testPath}/{$event->getStep()->getAction()}</info>"
            );
        }

        ++$this->stepNum;
        $this->slides[$filename] = $event->getStep();
        $this->timeStamps[$filename] = (new DateTime())->format($this->dateFormat);
    }

    protected function isStepIgnored(StepEvent $event): bool
    {
        $configIgnoredSteps = $this->config['ignore_steps'];
        $annotationIgnoredSteps = $event->getTest()->getMetadata()->getParam('skipRecording');

        /** @var string[] $ignoredSteps */
        $ignoredSteps = array_unique(
            array_merge(
                $configIgnoredSteps,
                is_array($annotationIgnoredSteps) ? $annotationIgnoredSteps : []
            )
        );

        foreach ($ignoredSteps as $stepPattern) {
            $stepRegexp = '/^' . str_replace('*', '.*?', $stepPattern) . '$/i';

            if (preg_match($stepRegexp, $event->getStep()->getAction())) {
                return true;
            }

            if (
                $event->getStep()->getMetaStep() !== null &&
                preg_match($stepRegexp, $event->getStep()->getMetaStep()->getAction())
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param StepEvent|TestEvent $event
     */
    private function getTestName(Event $event): string
    {
        return basename($event->getTest()->getMetadata()->getFilename()) . '_' . preg_replace('/[^A-Za-z0-9\-\_]/', '_', $event->getTest()->getMetadata()->getName());
    }

    protected function writeln(iterable|string $messages): void
    {
        parent::writeln(
            $this->ansi
            ? $messages
            : trim(preg_replace('/[ ]{2,}/', ' ', str_replace('⏺', '', $messages)))
        );
    }

    private function appendErrorMessage(string $testPath, string $message): void
    {
        $this->errorMessages[$testPath] = array_merge(
            array_key_exists($testPath, $this->errorMessages) ? $this->errorMessages[$testPath] : [],
            [$message]
        );
    }
}
