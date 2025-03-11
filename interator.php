<?php


class HtmlMetaRemover implements Iterator
{
    private $dom;
    private $currentNode;
    private $metaNodes = [];
    private $currentIndex = 0;

    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
        
        
        $metaTags = $this->dom->getElementsByTagName('meta');
        foreach ($metaTags as $meta) {
            $this->metaNodes[] = $meta;
        }
        
        
        $title = $this->dom->getElementsByTagName('title');
        if ($title->length > 0) {
            $this->metaNodes[] = $title->item(0);
        }
        
        $this->rewind();
    }

    public function current(): mixed
    {
        return $this->currentNode;
    }

    public function key() : mixed
    {
        return $this->currentIndex;
    }

    public function next() : void
    {
        $this->currentIndex++;
        $this->currentNode = $this->metaNodes[$this->currentIndex] ?? null;
    }

    public function rewind() : void
    {
        $this->currentIndex = 0;
        $this->currentNode = $this->metaNodes[0] ?? null;
    }
    
    public function valid(): bool
    {
        return isset($this->metaNodes[$this->currentIndex]);
    }

    public function removeMetaTags()
    {
        foreach ($this as $meta) {
            $meta->parentNode->removeChild($meta);
        }
    }
}


$dom = new DOMDocument();
$dom->loadHTMLFile('index.html'); 

$remover = new HtmlMetaRemover($dom);
$remover->removeMetaTags();
echo $dom->saveHTML();
