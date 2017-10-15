<?php

/** @param \Exception $exception */
function exception_handler($exception)
{
    echo "<h3>Uncaught exception:</h3>"
    , "<p style=color:red>"
    , $exception->getMessage(), "</p>\n<br />"
    , $exception->getTraceAsString();
}

set_exception_handler('exception_handler');
