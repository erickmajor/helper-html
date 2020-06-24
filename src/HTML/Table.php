<?php

namespace Helper\HTML;

use Exception;

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
     * tfoot parts
     *
     * @var array $tfoot
     */
    protected $tfoot = [];

    /**
     * tbody parts
     *
     * @var array $tbody
     */
    protected $tbody = [];

    /**
     * latest strucuture called to mount table
     *
     * @var string $latestStructure
     */
    protected $latestStructure = 'tbody';

    protected $theadAttributes;
    protected $theadClass;
    protected $tfootAttributes;
    protected $tfootClass;
    protected $tbodyAttributes;
    protected $tbodyClass;


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

    protected function setAttributes(string $structure, $class = null, $attributes = null)
    {
        $classProprerty      = "{$structure}Class";
        $attributesProprerty = "{$structure}Attributes";

        if (false === empty($attributes)) {
            $this->{$attributesProprerty} .= $attributes;
        }

        if (false === empty($class)) {
            $this->{$classProprerty} .= $class;
        }
    }

    protected function getAttributes(string $structure):array
    {
        $classProprerty      = "{$structure}Class";
        $attributesProprerty = "{$structure}Attributes";

        return [$this->{$classProprerty}, $this->{$attributesProprerty}, ];
    }

    protected function getTableStructure($structure = 'tbody')
    {
        $html = null;
        $structureLines = [];

        switch($structure) {
            case 'thead':
                $structureLines = $this->thead;
                break;
            case 'tfoot':
                $structureLines = $this->tfoot;
                break;
            case 'tbody':
                $structureLines = $this->tbody;
                break;
            default:
                throw new Exception("This HTML Table structure doesn't exists", 1);
        }

        if (false === empty($structureLines)) {
            list($class, $attributes) = $this->getAttributes($structure);
            $html .= "<{$structure}{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>";
            $html .= static::EOF_LINE;
            // add lines
            foreach($structureLines as $frame) {
                // add a structure and close that
                $html .= "{$frame}</tr>" . static::EOF_LINE;
            }

            $html .= "</{$structure}>" . static::EOF_LINE;
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
        return $this->getTableStructure('thead');
    }

    /**
     * tfoot getter
     *
     * @return string
     */
    protected function getTfoot()
    {
        return $this->getTableStructure('tfoot');
    }

    /**
     * tbody getter
     *
     * @return string
     */
    protected function getTbody()
    {
        return $this->getTableStructure();
    }

    /**
     * Table structure setter
     *
     * @param string $class
     * @param string $attibutes
     * @return Table
     */
    protected function mountTableStructure($class = null, $attributes = null)
    {
        // set new node ID
        $this->setNodeId();
        $structure = $this->latestStructure;

        // add structure
        $this->$structure[$this->getNodeId()] = "<tr{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>"
            . static::EOF_LINE;

        return $this;
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
        if ('tbody' !== $this->latestStructure) {
            throw new Exception("This structure depends on TBODY.", 3);
        }

        $structure = &$this->{$this->latestStructure};
        // add td to current tr
        $structure[$this->getNodeId()] .= "<td{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>"
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
        if ('thead' !== $this->latestStructure && 'tfoot' !== $this->latestStructure) {
            throw new Exception("This structure depends on THEAD or TFOOT.", 2);
        }

        $structure = &$this->{$this->latestStructure};
        // add th to current element
        $structure[$this->getNodeId()] .= "<th{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>"
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
        $this->latestStructure = 'thead';
        $this->setAttributes($this->latestStructure, $class, $attributes);

        return $this->tr();
    }

    /**
     * Table tfoot setter
     *
     * @param string $class
     * @param string $attibutes
     * @return Table
     */
    public function tfoot($class = null, $attributes = null)
    {
        $this->latestStructure = 'tfoot';
        $this->setAttributes($this->latestStructure, $class, $attributes);

        return $this->tr();
    }

    /**
     * Table tbody setter
     *
     * @param string $class
     * @param string $attibutes
     * @return Table
     */
    public function tbody($class = null, $attributes = null)
    {
        $this->latestStructure = 'tbody';
        $this->setAttributes($this->latestStructure, $class, $attributes);

        return $this->tr();
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
        if (true === empty($this->latestStructure)) {
            $this->latestStructure = 'tbody';
        }

        $structure = $this->latestStructure;
        if (
            false === empty($this->getNodeId()) &&
            true === array_key_exists($this->getNodeId(), $this->{$structure}) &&
            '<tr>' === trim($this->{$structure}[$this->getNodeId()])
        ) {
            $this->{$structure}[$this->getNodeId()] = "<tr{$this->formatAttributeClass($class)}{$this->formatAttributes($attributes)}>" . static::EOF_LINE;
            return $this;
        }

        return $this->mountTableStructure($class, $attributes);
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
            . $this->getThead(). $this->getTfoot() . $this->getTbody()

            // add table HTML
            . $this->table

            // close table
            . "</table>" . static::EOF_LINE;
    }
}
