<?php
namespace It_All\BoutiqueCommerce\UI;

/**
 * Class UiRsTable
 * makes html table from recordset
 */
class UiRsTable
{

    /**
     * @var array of column info
     * all display columns must be in this array
     * special case if empty array all columns will be displayed
     */
    private $outputColumns;

    /**
     * UiRsTable constructor.
     * @param $outputColumns
     * usage example
     *
    $outputColumns = array(
        'order_id' => array(
            'label' => 'Order #',
            'link' => 'details.php',
            'linkQsVar' => 'id',
            'linkQsVal' => 'order_id',
            'linkTarget' => '_blank'
            ),
        'order_dt' => array(
            'label' => 'Date/Time',
            'shorten' => 16
        ),
        'order_type' => array(
            'label' => 'Type'
        ),
        'name' => array(
            'label' => 'Name'
        )
    );
     */
    function __construct($outputColumns = [])
    {
        $this->outputColumns = $outputColumns;
    }

    private function tableRow($row, $type = 'body')
    {
        $html = "<tr>";
        foreach ($row as $i=>$v) {
            if (count($this->outputColumns) == 0 || array_key_exists($i, $this->outputColumns)) {
                if ($type == 'body') {
                    $cellTag = 'td';
                    $cellValue = (isset($this->outputColumns[$i]['shorten'])) ? substr($v, 0, $this->outputColumns[$i]['shorten']) : $v;

                    if (isset($this->outputColumns[$i]['link'])) {
                        $link = $this->outputColumns[$i]['link'];
                        if (isset($this->outputColumns[$i]['linkQsVar'])) {
                            $link .= '?'.$this->outputColumns[$i]['linkQsVar'] . '=' . $row[$this->outputColumns[$i]['linkQsVal']]; 
                        }
                        $cellContent = "<a href='$link'";
                        if (isset($this->outputColumns[$i]['linkTarget'])) {
                            $cellContent .= " target='".$this->outputColumns[$i]['linkTarget']."'";
                        }
                        $cellContent .= ">$cellValue</a>";
                    } else {
                        $cellContent = $cellValue;
                    }
                }
                else {
                    $cellTag = 'th';
                    $cellContent = (isset($this->outputColumns[$i]['label'])) ? $this->outputColumns[$i]['label'] : $i;
                }
                $html .= "<$cellTag>".$cellContent."</$cellTag>";
                }
        }
        $html .= "</tr>";
        return $html;
    }

    protected function tableHeaderRow($row)
    {
        return $this->tableRow($row, 'header');
    }

    protected function tableBodyRow($row)
    {
        return $this->tableRow($row);
    }

    public function makeTableOld(&$rs, $class = 'adminTable')
    {
        $html = "<table class='$class'>";
        $ct = 0;
        while ($row = pg_fetch_assoc($rs)) {
            ++$ct;
            if ($ct == 1) {
                $html .= "<thead>";
                $html .= $this->tableHeaderRow($row);
                $html .= "</thead>";
                $html .= "<tbody>";
            }
            $html .= $this->tableBodyRow($row);
            if($ct == pg_num_rows($rs)) {
                $html .= "</tbody>";
            }
        }
        $html .= "</table>";
        return $html;
    }

    public function makeTable(&$rs, $captionTop = null, $captionBottom = null)
    {
        $html = <<< EOT
<div id='scrollingTableContainer'>
    <table class='scrollingTable'>
EOT;
        if (!is_null($captionTop) && strlen($captionTop) > 0) {
            $html .= "<caption>$captionTop</caption>";
        }
        $html .= "<thead>";
        $ct = 0;
        while ($row = pg_fetch_assoc($rs)) {
            ++$ct;
            if ($ct == 1) {
                $html .= $this->tableHeaderRow($row);
                $html .= "</thead>";
                $html .= "<tbody>";
            }
            $html .= $this->tableBodyRow($row);
            if($ct == pg_num_rows($rs)) {
                $html .= "</tbody>";
            }
        }
        $html .= "</table>";
        if (!is_null($captionBottom) && strlen($captionBottom) > 0) {
            $html .= "<caption>$captionBottom</caption>";
        }
        $html .= "</div>";
        return $html;
    }
}
