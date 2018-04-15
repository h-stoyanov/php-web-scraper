<?php
// scraping a website
namespace src\Web;

use DOMDocument;

/**
 * Class Scraper.
 * @author Hristo Stoyanov <hristo@hstoyanov.com>
 * @package src\Web
 * This class is used to simplify working with DOMDocument class.
 */
class Scraper
{

    /* @var $content DOMDocument is used to save contents of a website */
    protected $content = NULL;

    /**
     * Scraper constructor. It scrapes url and saves the data in @var $content
     * @param string $url is the url we want to scrape
     */
    public function __construct(string $url)
    {
        if (stripos($url, 'http') !== 0) {
            $url = 'http://' . $url;
        }
        $this->content = new DOMDocument('1.0', 'utf-8');
        // this is used to save precious memory
        $this->content->preserveWhiteSpace = FALSE;
        // @ used to suppress warnings generated from improperly configured web pages
        @$this->content->loadHTMLFile($url);
    }

    /**
     * Returns the content that is populated in constructor
     *
     * @return DOMDocument $content
     */
    public function getContent()
    {
        return $this->content;
    }

    public static function getAttributeValue(\DOMElement $element, $attribute)
    {
        return $element->getAttribute($attribute);
    }

    public static function findElement(\DOMElement $element, string $tag, string $attr, string $val)
    {
        $returnElement = false;
        $nodeList = $element->getElementsByTagName($tag);
        /* @var $node \DOMNode */
        foreach ($nodeList as $node) {
            if ($node->hasAttributes()){
                /* @var $name string
                 * @var $attrNode \DOMAttr
                 */
                foreach ($node->attributes as $name => $attrNode) {
                    if ($name == $attr && $attrNode->value == $val){
                        return $returnElement = $attrNode->ownerElement;
                    }
                }
            }
        }
        return $returnElement;
    }
}