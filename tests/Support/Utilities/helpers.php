<?php

function file_fixture($file_path)
{
    $base_path = base_path('tests/Support/Fixtures/');
    return file_get_contents($base_path . $file_path);
}
