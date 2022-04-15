<?php
/**
 * In this file, we call template engine methods.
 * Twig template engine has used in this plugin.
 */
global $loader, $twig;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$loader = new FilesystemLoader(scc_template_dir);
$twig = new Environment($loader);