<?php

// load the autoloader
require __DIR__ . '/src/Autoload/Loader.php';
use src\Autoload\Loader as Loader;
use src\Web\Scraper as Scraper;

// add the current directory to the path
Loader::init(__DIR__ . '/');

$scraper = new Scraper('https://hstoyanov.com/blog/');
$articleElements = $scraper->getContent()->getElementsByTagName('article');
$articlesArray = array();
/* @var $articleElement DOMElement */
foreach ($articleElements as $articleElement) {
    $continueReading = Scraper::findElement($articleElement, 'div', 'class', 'continue-reading');
    $anchorToFullArticle = $continueReading->getElementsByTagName('a')->item(0);
    $scraper = new Scraper($anchorToFullArticle->getAttribute('href'));
    $fullArticleElement = $scraper->getContent()->getElementsByTagName('article')->item(0);
    $article = new \src\Web\Article($fullArticleElement);
    $articlesArray[$article->getId()] = $article->toArray();
}
header('Content-Type: application/json');
echo json_encode($articlesArray, JSON_UNESCAPED_UNICODE);
