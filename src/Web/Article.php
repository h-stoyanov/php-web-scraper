<?php
// creating article from DOMElement
namespace src\Web;

use src\Web\Scraper as Scraper;

/**
 * Class Article
 * @author Hristo Stoyanov <hristo@hstoyanov.com>
 * @package src\Web
 */
class Article
{

    private $id;

    protected $html;

    /**
     * Article constructor.
     * @param \DOMElement $element is the article element
     */
    public function __construct(\DOMElement $element)
    {
        $this->id = Scraper::getAttributeValue($element, 'id');
        $this->html = $element->ownerDocument->saveHTML($element);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }
}