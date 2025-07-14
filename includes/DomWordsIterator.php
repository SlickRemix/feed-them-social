<?php
namespace feedthemsocial\includes;

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Iterates individual words of DOM text and CDATA nodes
 * while keeping track of their position in the document.
 *
 * @author pjgalbraith http://www.pjgalbraith.com
 * @author porneL http://pornel.net
 * @license Public Domain
 */
final class DomWordsIterator implements \Iterator {

    private $start, $current;
    private $offset, $key, $words;

    /**
     * DomWordsIterator constructor.
     *
     * @param \DOMNode $el The DOM node.
     * @throws \InvalidArgumentException
     */
    public function __construct(\DOMNode $el)
    {
        if ($el instanceof \DOMDocument) {
            $this->start = $el->documentElement;
        }
        else if ($el instanceof \DOMElement) {
            $this->start = $el;
        }
        else {
            throw new \InvalidArgumentException( "Invalid arguments, expected DOMElement or DOMDocument" );
        }
    }

    /**
     * Returns position in text as DOMText node and character offset.
     *
     * @return array
     */
    public function currentWordPosition()
    {
        return array($this->current, $this->offset, $this->words);
    }

    /**
     * Returns DOMElement that is currently being iterated or null if iterator has finished.
     *
     * @return \DOMElement|null
     */
    public function currentElement()
    {
        return $this->current ? $this->current->parentNode : null;
    }

    // Implementation of Iterator interface
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->key;
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        if (!$this->current) {
            return;
        }

        // Use the helper function here
        if ($this->isTextNode($this->current)) {
            if ($this->offset == -1) {
                $this->words = preg_split("/[\n\r\t ]+/", $this->current->textContent, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE);
            }
            $this->offset++;

            if ($this->offset < count($this->words)) {
                $this->key++;
                return;
            }
            $this->offset = -1;
        }

        while ($this->current->nodeType == XML_ELEMENT_NODE && $this->current->firstChild) {
            $this->current = $this->current->firstChild;
            // Use the helper function here as well
            if ($this->isTextNode($this->current)) {
                return $this->next();
            }
        }

        while (!$this->current->nextSibling && $this->current->parentNode) {
            $this->current = $this->current->parentNode;
            if ($this->current === $this->start) {
                $this->current = null;
                return;
            }
        }

        $this->current = $this->current->nextSibling;

        return $this->next();
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        if ($this->current) {
            return $this->words[$this->offset][0];
        }
        return null;
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        return (bool)$this->current;
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->offset = -1; $this->words = array();
        $this->current = $this->start;
        $this->next();
    }

    /**
     * Checks if the current node is a text or CDATA node.
     * @param \DOMNode $node The node to check.
     * @return bool
     */
    private function isTextNode(\DOMNode $node): bool
    {
        return $node->nodeType === XML_TEXT_NODE || $node->nodeType === XML_CDATA_SECTION_NODE;
    }
}
