<?php
declare(strict_types=1);

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
    protected $outputColumns;

    private $primaryKeyColumn;

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
    function __construct($outputColumns = [], $primaryKeyColumn = null)
    {
        $this->outputColumns = $outputColumns;
        $this->primaryKeyColumn = $primaryKeyColumn;
    }

    private function getLinkAttributes(array $linkInfo, string $cellIndex, string $cellValue): array
    {
        $returnArr = [];
        $attributes = ['href', 'title', 'target', 'onclick', 'text'];
        foreach ($attributes as $attribute) {
            $returnArr[$attribute] = (isset($linkInfo[$attribute])) ? str_replace("VALUE", $cellValue, $linkInfo[$attribute]) : '';
        }
        return $returnArr;
    }

    protected function bodyCellLinkedContent(array $linkInfo, string $cellIndex, string $cellValue): string
    {
        extract($this->getLinkAttributes($linkInfo, $cellIndex, $cellValue));
        return "<a href='$href' title='$title' target='$target' onclick='$onclick'>$text</a>";
    }

    protected function bodyCellContent(string $cellIndex, string $cellValue): string
    {
        $cellValue = (isset($this->outputColumns[$cellIndex]['shorten'])) ? substr($cellValue, 0, $this->outputColumns[$cellIndex]['shorten']) : $cellValue;

        if (isset($this->outputColumns[$cellIndex]['link'])) {
            return $this->bodyCellLinkedContent($this->outputColumns[$cellIndex]['link'], $cellIndex, $cellValue);
        }

        return $cellValue;
    }

    protected function bodyCell(string $cellIndex, string $cellValue): string
    {
        return "<td>".$this->bodyCellContent($cellIndex, $cellValue)."</td>";
    }

    protected function headerCell(string $cellIndex): string
    {
        $cellContent = (isset($this->outputColumns[$cellIndex]['label'])) ? $this->outputColumns[$cellIndex]['label'] : $cellIndex;
        return "<th>".$cellContent."</th>";
    }

    protected function tableRow(array $row, bool $inHeader = false): string
    {
        $html = "<tr>";
        foreach ($row as $cellIndex => $cellValue) {
            if (count($this->outputColumns) == 0 || array_key_exists($cellIndex, $this->outputColumns)) {
                $html .= ($inHeader) ? $this->headerCell($cellIndex) : $this->bodyCell($cellIndex, (string) $cellValue);
            }
        }
        if (!is_null($this->primaryKeyColumn)) {
            $html .= ($inHeader) ? $this->headerCell('X') : $this->bodyCell('X', $row[$this->primaryKeyColumn]);
        }
        $html .= "</tr>";
        return $html;
    }

    public function makeTable($rs, $captionTop = null, $captionBottom = null)
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
                $html .= $this->tableRow($row, true);
                $html .= "</thead>";
                $html .= "<tbody>";
            }
            $html .= $this->tableRow($row);
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
