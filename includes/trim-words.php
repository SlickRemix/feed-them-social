<?php
/**
 * Truncates HTML text retaining tags and formatting.
 * See http://www.pjgalbraith.com/2011/11/truncating-text-html-with-php/ for related blog post.
 * https://www.pjgalbraith.com/truncating-text-html-with-php/
 * Example:
 * $output = FeedThemSocialTruncateHTML::truncateChars($your_html, '40', '...');
 * $output = FeedThemSocialTruncateHTML::truncateWords($your_html, '7', '...');
 *
 * @author pjgalbraith http://www.pjgalbraith.com
 *
 */

/*
    Copyright (c) 2011 Patrick Galbraith (http://www.pjgalbraith.com).
    All rights reserved.

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
*/

class FeedThemSocialTruncateHTML {

    public static function fts_custom_trim_words($html, $limit, $ellipsis = '...') {

        if($limit <= 0 || $limit >= self::countWords(strip_tags($html)))
            return $html;

        // create new DOMDocument
        $dom = new DOMDocument();
        // set error level
        $internalErrors = libxml_use_internal_errors(true);
        // load HTML
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        // Restore error level
        libxml_use_internal_errors($internalErrors);

        $body = $dom->getElementsByTagName("body")->item(0);

        $it = new FeedThemSocialDOMWordsIterator($body);

        foreach($it as $word) {
            if($it->key() >= $limit) {
                $currentWordPosition = $it->currentWordPosition();
                $curNode = $currentWordPosition[0];
                $offset = $currentWordPosition[1];
                $words = $currentWordPosition[2];

                $curNode->nodeValue = substr($curNode->nodeValue, 0, $words[$offset][1] + strlen($words[$offset][0]));

                self::removeProceedingNodes($curNode, $body);
                self::insertEllipsis($curNode, $ellipsis);
                break;
            }
        }

        return preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $dom->saveHTML());
    }

    private static function removeProceedingNodes(DOMNode $domNode, DOMNode $topNode) {
        $nextNode = $domNode->nextSibling;

        if($nextNode !== NULL) {
            self::removeProceedingNodes($nextNode, $topNode);
            $domNode->parentNode->removeChild($nextNode);
        } else {
            //scan upwards till we find a sibling
            $curNode = $domNode->parentNode;
            while($curNode !== $topNode) {
                if($curNode->nextSibling !== NULL) {
                    $curNode = $curNode->nextSibling;
                    self::removeProceedingNodes($curNode, $topNode);
                    $curNode->parentNode->removeChild($curNode);
                    break;
                }
                $curNode = $curNode->parentNode;
            }
        }
    }

    private static function insertEllipsis(DOMNode $domNode, $ellipsis) {
        $avoid = array('a', 'strong', 'em', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'); //html tags to avoid appending the ellipsis to

        if( in_array($domNode->parentNode->nodeName, $avoid) && $domNode->parentNode->parentNode !== NULL) {
            // Append as text node to parent instead
            $textNode = new DOMText($ellipsis);

            if($domNode->parentNode->parentNode->nextSibling)
                $domNode->parentNode->parentNode->insertBefore($textNode, $domNode->parentNode->parentNode->nextSibling);
            else
                $domNode->parentNode->parentNode->appendChild($textNode);
        } else {
            // Append to current node
            $domNode->nodeValue = rtrim($domNode->nodeValue).$ellipsis;
        }
    }

    private static function countWords($text) {
        $words = preg_split("/[\n\r\t ]+/", $text, -1, PREG_SPLIT_NO_EMPTY);
        return count($words);
    }

}

/**
 * Iterates individual words of DOM text and CDATA nodes
 * while keeping track of their position in the document.
 *
 * Example:
 *
 *  $doc = new DOMDocument();
 *  $doc->load('example.xml');
 *  foreach(new FeedThemSocialDOMWordsIterator($doc) as $word) echo $word;
 *
 * @author pjgalbraith http://www.pjgalbraith.com
 * @author porneL http://pornel.net (based on DOMLettersIterator available at http://pornel.net/source/domlettersiterator.php)
 * @license Public Domain
 *
 */

final class FeedThemSocialDOMWordsIterator implements Iterator {

    private $start, $current;
    private $offset, $key, $words;

    /**
     * expects DOMElement or DOMDocument (see DOMDocument::load and DOMDocument::loadHTML)
     */
    function __construct(DOMNode $el)
    {
        if ($el instanceof DOMDocument) $this->start = $el->documentElement;
        else if ($el instanceof DOMElement) $this->start = $el;
        else throw new InvalidArgumentException("Invalid arguments, expected DOMElement or DOMDocument");
    }

    /**
     * Returns position in text as DOMText node and character offset.
     * (it's NOT a byte offset, you must use mb_substr() or similar to use this offset properly).
     * node may be NULL if iterator has finished.
     *
     * @return array
     */
    function currentWordPosition()
    {
        return array($this->current, $this->offset, $this->words);
    }

    /**
     * Returns DOMElement that is currently being iterated or NULL if iterator has finished.
     *
     * @return DOMElement
     */
    function currentElement()
    {
        return $this->current ? $this->current->parentNode : NULL;
    }

    // Implementation of Iterator interface
    function key()
    {
        return $this->key;
    }

    function next()
    {
        if (!$this->current) return;

        if ($this->current->nodeType == XML_TEXT_NODE || $this->current->nodeType == XML_CDATA_SECTION_NODE)
        {
            if ($this->offset == -1)
            {
                // fastest way to get individual Unicode chars and does not require mb_* functions
                //preg_match_all('/./us',$this->current->textContent,$m); $this->words = $m[0];
                $this->words = preg_split("/[\n\r\t ]+/", $this->current->textContent, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_OFFSET_CAPTURE);
            }
            $this->offset++;

            if ($this->offset < count($this->words)) {
                $this->key++;
                return;
            }
            $this->offset = -1;
        }

        while($this->current->nodeType == XML_ELEMENT_NODE && $this->current->firstChild)
        {
            $this->current = $this->current->firstChild;
            if ($this->current->nodeType == XML_TEXT_NODE || $this->current->nodeType == XML_CDATA_SECTION_NODE) return $this->next();
        }

        while(!$this->current->nextSibling && $this->current->parentNode)
        {
            $this->current = $this->current->parentNode;
            if ($this->current === $this->start) {$this->current = NULL; return;}
        }

        $this->current = $this->current->nextSibling;

        return $this->next();
    }

    function current()
    {
        if ($this->current) return $this->words[$this->offset][0];
        return NULL;
    }

    function valid()
    {
        return !!$this->current;
    }

    function rewind()
    {
        $this->offset = -1; $this->words = array();
        $this->current = $this->start;
        $this->next();
    }
}
// class FTS_test_runit
// {

//    public function returnthis ()
//    {
//        $html = 'HUGE thanks @damon_powers and #florida_greatshots';

//        $output =  new FeedThemSocialTruncateHTML();
//        echo $output->fts_custom_trim_words($html, '37', '...');
//    }
// }
// $output = new runit();
// echo $output->returnthis();