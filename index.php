<?php
//include 'simple_html_dom.php';
//$url = 'http://arenaarmeecsofia.net/';
//$output = file_get_html($url);
//$table = $output->getElementById('tablepress-1');
//$arr = array();
//foreach ($table->find('tbody tr') as $table_row) {
//    $date = $table_row->children(0)->plaintext;
//    $time = $table_row->children(1)->plaintext;
//    $name = $table_row->children(2)->plaintext;
//    if ($date != 'Дата' && $time != 'Час') {
//        $link = $table_row->children(2)->children(0)->href;
//        $imageLink = $table_row->find("img")[0]->src;
//        $image = base64_encode(file_get_contents($imageLink));
//        if ($time == null) $time = '20:00';
//        $format = "m/d/Y H:i";
//        $time_str = $date . " " . $time;
//        $date_obj = DateTime::createFromFormat($format, $time_str, new DateTimeZone("Europe/Sofia"));
//        if ($date_obj) {
//            $assoc = array(
//                'time' => $date_obj->getTimestamp() * 1000,
//                'name' => $name,
//                'url'  => $link,
//                'picture' => $imageLink,
//                'pictureData' => $image
//            );
//            array_push($arr, $assoc);
//        }
//    }
//}
//$final_arr = array('events' => $arr);
//$data = json_encode($final_arr, JSON_UNESCAPED_UNICODE);
//header('Content-Type: application/json; charset=utf-8');
//echo $data;

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
    $articlesArray[$article->getId()] = $article->getHtml();
}
