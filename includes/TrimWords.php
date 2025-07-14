<?php
namespace feedthemsocial\includes;

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Truncates HTML text retaining tags and formatting.
 *
 * @author pjgalbraith http://www.pjgalbraith.com
 */
class TrimWords {
    /**
     * Truncate words.
     *
     * @param string $html The HTML content.
     * @param int    $limit The word limit.
     * @param string $ellipsis The ellipsis string.
     * @return string
     */
    public static function ftsCustomTrimWords($html, $limit, $ellipsis = null): string
    {

        if($limit <= 0 || $limit >= self::countWords(strip_tags($html))) {
            return $html;
        }

        // create new DOMDocument
        $dom = new \DOMDocument();
        // set error level
        $internalErrors = libxml_use_internal_errors(true);
        // load HTML. Adding a meta tag is the modern way to specify the encoding for DOMDocument.
        $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html);
        // Restore error level
        libxml_use_internal_errors($internalErrors);

        $body = $dom->getElementsByTagName( 'body' )->item(0);

        // Here is where the other class is needed. The autoloader will now find it.
        $it = new DomWordsIterator($body);

        foreach( $it as $ignored ) {
            if($it->key() >= $limit) {
                $currentWordPosition = $it->currentWordPosition();
                $curNode = $currentWordPosition[0];
                $offset = $currentWordPosition[1];
                $words = $currentWordPosition[2];

                $curNode->nodeValue = substr($curNode->nodeValue, 0, $words[$offset][1] + \strlen($words[$offset][0]));

                self::removeProceedingNodes($curNode, $body);
                self::insertEllipsis($curNode, $ellipsis);
                break;
            }
        }

        return preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $dom->saveHTML());
    }

    /**
     * Remove proceeding nodes.
     *
     * @param \DOMNode $domNode The DOM node.
     * @param \DOMNode $topNode The top node.
     */
    private static function removeProceedingNodes(\DOMNode $domNode, \DOMNode $topNode) {
        $nextNode = $domNode->nextSibling;

        if($nextNode !== null) {
            self::removeProceedingNodes($nextNode, $topNode);
            $domNode->parentNode->removeChild($nextNode);
        } else {
            //scan upwards till we find a sibling
            $curNode = $domNode->parentNode;
            while($curNode !== $topNode) {
                if($curNode->nextSibling !== null) {
                    $curNode = $curNode->nextSibling;
                    self::removeProceedingNodes($curNode, $topNode);
                    $curNode->parentNode->removeChild($curNode);
                    break;
                }
                $curNode = $curNode->parentNode;
            }
        }
    }

    /**
     * Insert ellipsis.
     *
     * @param \DOMNode $domNode The DOM node.
     * @param string   $ellipsis The ellipsis string.
     */
    private static function insertEllipsis(\DOMNode $domNode, $ellipsis) {
        $avoid = array('a', 'strong', 'em', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'); //html tags to avoid appending the ellipsis to

        if( \in_array( $domNode->parentNode->nodeName, $avoid, true ) && $domNode->parentNode->parentNode !== null) {
            // Append as text node to parent instead
            $textNode = new \DOMText($ellipsis);

            if($domNode->parentNode->parentNode->nextSibling) {
                $domNode->parentNode->parentNode->insertBefore( $textNode, $domNode->parentNode->parentNode->nextSibling );
            }
            else {
                $domNode->parentNode->parentNode->appendChild( $textNode );
            }
        } else {
            // Append to current node
            $domNode->nodeValue = rtrim($domNode->nodeValue).$ellipsis;
        }
    }

    /**
     * Count words.
     *
     * @param string $text The text.
     * @return int
     */
    private static function countWords($text) {
        $words = preg_split("/[\n\r\t ]+/", $text, -1, PREG_SPLIT_NO_EMPTY);
        return \count($words);
    }

}
