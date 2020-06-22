<?php

namespace Helper\HTML;

/**
 * Generates HTML Tables
 * 
 * @package Helper\HTML
 * 
 * Based on STable\STable
 * @see PHP HTML Table Class {@link https://www.shayanderson.com/php/php-html-table-class.htm}
 */
class Table
{
    /**
     * End of line string (default \n)
     */
    const EOF_LINE = "\n";


    /**
     * Current node ID
     *
     * @var int $node_id
     */
    protected $node_id = 0;

    /**
     * HTML table
     *
     * @var string $table
     */
    protected $table;

    /**
     * thead parts
     *
     * @var array $thead
     */
    protected $thead = [];

    /**
     * tr parts
     *
     * @var array $tr
     */
    protected $tr = [];


    /**
     * Table attributes
     *
     * @var string $attributes
     */
    public $attributes;

    /**
     * Table border width
     *
     * @var int $border
     */
    public $border = 0;

    /**
     * Table cell padding width
     *
     * @var int $cellpadding
     */
    public $cellpadding = 3;

    /**
     * Table cell spacing width
     *
     * @var int $cellspacing
     */
    public $cellspacing = 0;

    /**
     * Table class
     *
     * @var string $class
     */
    public $class;

    /**
     * Table ID
     *
     * @var string $id
     */
    public $id;

    /**
     * Table width
     *
     * @var mixed width
     */
    public $width;


    /**
     * Format table class attribute
     *
     * @param string $class
     */
    protected function formatAttributeClass($class = null)
    {
        return $class ? " class=\"{$class}\"" : null;
    }

    /**
     * Format table attributes
     *
     * @param string $attributes
     */
    protected function formatAttributes($attributes = null)
    {
        return $attributes ? " {$attributes}" : null;
    }

    /**
     * Current node ID getter
     *
     * @return int
     */
    protected function getNodeId()
    {
        // return node ID
        return $this->node_id;
    }

    /**
     * Current node ID setter
     */
    protected function setNodeId()
    {
        // increment new node ID
        $this->node_id++;
    }

    /**
     * tbody getter
     *
     * @return string
     */
    protected function getTbody()
    {
        $html = null;

        // add tr(s)
        foreach($this->tr as $tr) {
            // add tr and close tr
            $html .= "{$tr}</tr>" . static::EOF_LINE;
        }

        return $html;
    }

    /**
     * thead getter
     *
     * @return string
     */
    protected function getThead()
    {
        $html = null;

        // add thead(s)
        foreach($this->thead as $thead) {
            // add thead and close thead
            $html .= "{$thead}</thead>" . static::EOF_LINE;
        }

        return $html;
    }


    /**
     * Set params
     *
     * @param string $id
     */
    public function __construct($id = null)
    {
        // set table ID
        $this->id = $id;
    }

    /**
     * Table td setter
     *
     * @param mixed $text
     * @param string $class
     * @param string $attributes
     * @return Table
     */
    public function td($text = null, $class = null, $attributes = null)
    {
        // add td to current tr
        $this->tr[$this->getNodeId()] .= "<td{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>"
            . "{$text}</td>" . static::EOF_LINE;

        return $this;
    }

    /**
     * Table th setter
     *
     * @param mixed $text
     * @param string $class
     * @param string $attibutes
     * @return Table
     */
    public function th($text = null, $class = null, $attributes = null)
    {
        // add th to current thead
        $this->thead[$this->getNodeId()] .= "<th{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>"
            . "{$text}</th>" . static::EOF_LINE;

        return $this;
    }

    /**
     * Table thead setter
     *
     * @param string $class
     * @param string $attibutes
     * @return Table
     */
    public function thead($class = null, $attributes = null)
    {
        // set new node ID
        $this->setNodeId();

        // add thead
        $this->thead[$this->getNodeId()] = "<thead{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>"
            . static::EOF_LINE;

        return $this;
    }

    /**
     * Table tr setter
     *
     * @param string $class
     * @param string $attributes
     * @return Table
     */
    public function tr($class = null, $attributes = null)
    {
        // set new node ID
        $this->setNodeId();

        // add tr
        $this->tr[$this->getNodeId()] = "<tr{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>"
            . static::EOF_LINE;

        return $this;
    }

    /**
     * Table HTML getter
     *
     * @return string
     */
    public function getTable()
    {
        // return table HTML
        return "<table border=\"{$this->border}\""
            // set ID if set, set class and attributes
            . ( $this->id ? " id=\"{$this->id}\"" : null ) . $this->formatAttributeClass($this->class)
            . $this->formatAttributes($this->attributes)

            // set width if set
            . ( $this->width ? " width=\"{$this->width}\"" : null )

            // set table params
            . " cellpadding=\"{$this->cellpadding}\" cellspacing=\"{$this->cellspacing}\">" . static::EOF_LINE

            // add table thead and tbody
            . $this->getThead() . $this->getTbody()

            // add table HTML
            . $this->table

            // close table
            . "</table>" . static::EOF_LINE;
    }
}
