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

    protected $featuredImage;

    protected $title;

    protected $author;

    protected $categories;

    protected $datePublished;

    protected $content;

    /**
     * Article constructor.
     * @param \DOMElement $element is the article element
     */
    public function __construct(\DOMElement $element)
    {
        $this->id = $element->getAttribute('id');
        $this->html = $element->ownerDocument->saveHTML($element);

        $figureElements = $element->getElementsByTagName('figure');
        if ($figureElements->length > 0) {
            $this->featuredImage = $figureElements->item(0)
                ->getElementsByTagName('img')->item(0)->getAttribute('src');
        }

        $headerElement = $element->getElementsByTagName('header')->item(0);

        $this->title = Scraper::findElement($headerElement, 'h1', 'class', 'entry-title')->nodeValue;

        $entryMetaDiv = Scraper::findElement($headerElement, 'div', 'class', 'entry-meta');
        $spanAuthor = Scraper::findElement($entryMetaDiv, 'span', 'class', 'author vcard');
        $authorLink = $spanAuthor->getElementsByTagName('a')->item(0);

        $this->author[$authorLink->nodeValue] = $authorLink->getAttribute('href');

        $spanPostedOn = Scraper::findElement($entryMetaDiv, 'span', 'class', 'posted-on');

        $this->datePublished = strtotime($spanPostedOn->getElementsByTagName('time')->item(0)
            ->getAttribute('datetime'));

        $spanCatLinks = Scraper::findElement($headerElement, 'span', 'class', 'cat-links');
        /* @var $spanCatLink \DOMNode */
        foreach ($spanCatLinks->getElementsByTagName('a') as $spanCatLink) {
            if ($spanCatLink->hasAttributes()) {
                /* @var $attrNode \DOMAttr */
                foreach ($spanCatLink->attributes as $name => $attrNode) {
                    if ($name == 'href') {
                        $this->categories[$spanCatLink->nodeValue] = $attrNode->value;
                    }
                }
            }
        }
        $contentDiv = $element->getElementsByTagName('section')->item(0)->getElementsByTagName('div')->item(0);
        $this->content = $contentDiv->ownerDocument->saveHTML($contentDiv);
    }

    /**
     * @return string
     */
    public function getId(): string
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

    public function getJSON()
    {
        return json_encode(array(
            'featured_image' => $this->featuredImage,
            'title' => $this->title,
            'author' => $this->author,
            'categories' => $this->categories,
            'date_published' => $this->datePublished,
            'content_html' => $this->content
        ));
    }

    public function toArray()
    {
        return array(
            'featured_image' => $this->featuredImage,
            'title' => $this->title,
            'author' => $this->author,
            'categories' => $this->categories,
            'date_published' => $this->datePublished,
            'content_html' => $this->content
        );
    }
}