<?php

/**
 * Return form validation error message for a given field.
 *
 * @param string $msg Error message to display.
 * @return string Error message as HTML output.
 */
function formError(string $msg)
{
    if (trim($msg) === '') return;
    $html = '<p class="form-error">' . h($msg) . '</p>';
    return $html;
}


/**
 * Purify HTML output with HTML Purifier.
 *
 * Useful as an alternative to htmlspecialchars for WYSIWYG output.
 *
 * @param string $dirty Dirty input.
 * @return string Purified output.
 */
function purify(string $dirty)
{
    $purifier = app('purifier');
    return $purifier->purify($dirty);
}
