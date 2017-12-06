<?php

// render view, passing in values
function render($view, $values = [])
{
    // if view exists, render it
    if (file_exists($view))
    {
        // extract variables into local scope
        extract($values);

        // render view (between header and footer)
		require "views/header.php";
        require $view;
		require "views/footer.php";
        exit;
    }
}

// dump object contents as html
function dump()
{
    $arguments = func_get_args();
    require "dump.php";
    exit;
}

?>