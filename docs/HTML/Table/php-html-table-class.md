# PHP HTML Table Class
By Shay Anderson on March 2011

Below is the source code for a PHP class I created that will allow for easy HTML table creation. First, here is an example of how to create a simple HTML table using the class:

```php
$Table = new STable();

// add table header
$Table->thead()
      ->th("col1")
      ->th("col2")
      ->th("col3");

// add a row
$Table->tr()
      ->td("val1")
      ->td("val2")
      ->td("val3");

// add another row
$Table->tr()
      ->td("val4")
      ->td("val5")
      ->td("val6");

// display table
print $Table->getTable();
```

This example will create this table:

```html
<table border="0" cellpadding="3" cellspacing="0">
<thead>
<th>col1</th>
<th>col2</th>
<th>col3</th>
</thead>
<tr>
<td>val1</td>
<td>val2</td>
<td>val3</td>
</tr>
<tr>
<td>val4</td>
<td>val5</td>
<td>val6</td>
</tr>
</table>
```

You can also set table attributes, here is an example:

```php
$Table = new STable();
$Table->attributes = "bgcolor=\"#ccc\"";
$Table->border = 1;
$Table->cellpadding = 3;
$Table->cellspacing = 1;
$Table->class = "test_class";
$Table->width = "100%";

$Table->thead("example_thead_class", "id=\"example_tr_id\"")
      ->th("col1", null, "align=\"left\"")
      ->th("col2")
      ->th("col3");
       
$Table->tr("tr_class")
      ->td("val1", "example_td_class", "id=\"example_td_id\"")
      ->td("val2")
      ->td("val3");
       
$Table->tr(null, "valign=\"top\"")
      ->td("val4")
      ->td("val5")
      ->td("val6");

print $Table->getTable();
```

This example will output this table:

```html
<table border="1" class="test_class" bgcolor="#ccc" width="100%" cellpadding="3" cellspacing="1">
<thead class="example_thead_class" id="example_tr_id">
<th align="left">col1</th>
<th>col2</th>
<th>col3</th>
</thead>
<tr class="tr_class">
<td class="example_td_class" id="example_td_id">val1</td>
<td>val2</td>
<td>val3</td>
</tr>
<tr valign="top">
<td>val4</td>
<td>val5</td>
<td>val6</td>
</tr>
</table>
```

This class also works well with data (like arrays), here is an example:

```php
$data = array(
      array("val1", "val2", "val3"),
      array("val4", "val5", "val6")
);

$Table = new STable();

foreach($data as $tr) {
      $Table->tr();
      foreach($tr as $td) {
            $Table->td($td);
      }
}

print $Table->getTable();
```

Which would output this table:

```html
<table border="0" cellpadding="3" cellspacing="0">
<tr>
<td>val1</td>
<td>val2</td>
<td>val3</td>
</tr>
<tr>
<td>val4</td>
<td>val5</td>
<td>val6</td>
</tr>
</table>
```

Here is the PHP HTML table class source code:

```php
/**
 * STable - Generate HTML Tables
 *  
 * @package STable
 * @category STable
 * @name STable
 * @version 1.0
 * @author Shay Anderson 03.11
 */
final class STable {
      /**
       * End of line string (default \n)
       */
      const EOF_LINE = "\n";

      /**
       * Current node ID
       *  
       * @var int $_node_id
       */
      private $_node_id = 0;

      /**
       * HTML table
       *  
       * @var string $_table
       */
      private $_table;

      /**
       * thead parts
       *
       * @var array $_thead
       */
      private $_thead = array();

      /**
       * tr parts
       *
       * @var array $_tr
       */
      private $_tr = array();

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
       * Set params
       *
       * @param string $id
       */
      public function  __construct($id = null) {
            // set table ID
            $this->id = $id;
      }

      /**
       * Format table class attribute
       *
       * @param string $class
       */
      private function _formatAttributeClass($class = null) {
            return $class ? " class=\"{$class}\"" : null;
      }

      /**
       * Format table attributes
       *
       * @param string $attributes
       */
      private function _formatAttributes($attributes = null) {
            return $attributes ? " {$attributes}" : null;
      }

      /**
       * Current node ID getter
       *
       * @return int
       */
      private function _getNodeId() {
            // return node ID
            return $this->_node_id;
      }

      /**
       * Current node ID setter
       */
      private function _setNodeId() {
            // increment new node ID
            $this->_node_id++;
      }

      /**
       * tbody getter
       *
       * @return string
       */
      private function _getTbody() {
            $html = null;

            // add tr(s)
            foreach($this->_tr as $tr) {
                  // add tr and close tr
                  $html .= "{$tr}</tr>" . self::EOF_LINE;
            }

            return $html;
      }

      /**
       * thead getter
       *
       * @return string
       */
      private function _getThead() {
            $html = null;

            // add thead(s)
            foreach($this->_thead as $thead) {
                  // add thead and close thead
                  $html .= "{$thead}</thead>" . self::EOF_LINE;
            }

            return $html;
      }

      /**
       * Table td setter
       *
       * @param mixed $text
       * @param string $class
       * @param string $attributes
       * @return STable
       */
      public function td($text = null, $class = null, $attributes = null) {
            // add td to current tr
            $this->_tr[$this->_getNodeId()] .= "<td{$this->_formatAttributeClass($class)}{$this->_formatAttributes($attributes)}>"
                  . "{$text}</td>" . self::EOF_LINE;

            return $this;
      }

      /**
       * Table th setter
       *
       * @param mixed $text
       * @param string $class
       * @param string $attibutes
       * @return STable
       */
      public function th($text = null, $class = null, $attributes = null) {
            // add th to current thead
            $this->_thead[$this->_getNodeId()] .= "<th{$this->_formatAttributeClass($class)}{$this->_formatAttributes($attributes)}>"
                  . "{$text}</th>" . self::EOF_LINE;

            return $this;
      }

      /**
       * Table thead setter
       *
       * @param string $class
       * @param string $attibutes
       * @return STable
       */
      public function thead($class = null, $attributes = null) {
            // set new node ID
            $this->_setNodeId();

            // add thead
            $this->_thead[$this->_getNodeId()] = "<thead{$this->_formatAttributeClass($class)}{$this->_formatAttributes($attributes)}>"
                  . self::EOF_LINE;

            return $this;
      }

      /**
       * Table tr setter
       *
       * @param string $class
       * @param string $attributes
       * @return STable
       */
      public function tr($class = null, $attributes = null) {
            // set new node ID
            $this->_setNodeId();

            // add tr
            $this->_tr[$this->_getNodeId()] = "<tr{$this->_formatAttributeClass($class)}{$this->_formatAttributes($attributes)}>"
                  . self::EOF_LINE;

            return $this;
      }

      /**
       * Table HTML getter
       *
       * @return string
       */
      public function getTable() {
            // return table HTML
            return "<table border=\"{$this->border}\""
                  // set ID if set, set class and attributes
                  . ( $this->id ? " id=\"{$this->id}\"" : null ) . $this->_formatAttributeClass($this->class)
                  . $this->_formatAttributes($this->attributes)

                  // set width if set
                  . ( $this->width ? " width=\"{$this->width}\"" : null )

                  // set table params
                  . " cellpadding=\"{$this->cellpadding}\" cellspacing=\"{$this->cellspacing}\">" . self::EOF_LINE

                  // add table thead and tbody
                  . $this->_getThead() . $this->_getTbody()

                  // add table HTML
                  . $this->_table

                  // close table
                  . "</table>" . self::EOF_LINE;
      }
}
```

You can check out this post in full in [PHP HTML Table Class](https://www.shayanderson.com/php/php-html-table-class.htm), on the [Shay Anderson website](https://www.shayanderson.com/).