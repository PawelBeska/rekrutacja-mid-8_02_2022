<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

trait Reportable
{
    /**
     * @param \Throwable $exception
     * @param int $exceptionLevel
     * @return void
     */
    function reportError(Throwable $exception, int $exceptionLevel = 1) : void
    {
        Log::error(
            $exception->getMessage()
            . PHP_EOL . 'IN LINE: ' . $exception->getLine()
            . PHP_EOL . 'IN FILE: ' . $exception->getFile()
        );
    }
}
