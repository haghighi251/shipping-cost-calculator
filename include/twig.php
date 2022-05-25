<?php

/**
 * In this file, we call template engine methods.
 * Twig template engine is used in this plugin.
 */

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * These variables are used in other pages. 
 * So we should declare them as a Global variable.
 */
global $loader, $twig;

$loader = new FilesystemLoader(scc_template_dir);
$twig = new Environment($loader);
