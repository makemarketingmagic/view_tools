<?php

namespace Makemarketingmagic\ViewTools\Table;

use Makemarketingmagic\ViewTools\Services\ArrayHelper;

/**
 * Table row class
 *
 */
class TableRow
{
    /**
     * References to parent Table class
     *
     * @var Table|null
     */
    protected ?Table $table = null;

    /**
     * Row cells
     *
     * @var array
     */
    protected array $cells;

    /**
     * Row tr element attributes
     *
     * @var array
     */
    protected array $attributes;

    /**
     * Class constructor
     *
     * @param array $cells
     * @param array $attributes
     */
    public function __construct(array $cells, array $attributes = [])
    {
        foreach ($cells as $key => $cell) {
            $this->addCell($key, $cell);
        }
        $this->attributes = $attributes;
    }

    /**
     * Sets parent Table reference
     *
     * @param Table $table
     */
    public function setTable(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Adds a new cell to row
     *
     * @param TableCell $cell
     */
    public function addCell(string $key, TableCell $cell)
    {
        $cell->setRow($this);
        $this->cells[$key] = $cell;
    }

    /**
     * Returns html of row
     *
     * @param callable|null $callback
     * @return string
     */
    public function html(callable $callback = null): string
    {
        $result = '';
        $html = '';
        if ($this->table && !empty($this->table->getColumns())) {
            $columns = $this->table->getColumns();
            foreach ($columns as $key => $column) {
                if (isset($this->cells[$key])) {
                    $html .= $this->cells[$key]->html($key, $callback);
                } else {
                    $arrayHelper = new ArrayHelper();
                    $foo = $arrayHelper->get($key, $this->cells);
                    if ($foo instanceof TableCell) {
                        $html .= $foo->html($key, $callback);
                    } else {
                        $html .= (new TableCell($foo))->html($key, $callback);
                    }
                }
            }
        } else {
            foreach ($this->cells as $key => $cell) {
                $html .= $cell->html($key, $callback);
            }
        }
        $result .= view(config('view_tools_tables.views.row'), [
            'content' => $html,
            'attributes' => Attribute::str($this->attributes)
        ])->render();
        return trim($result);
    }

    /**
     * Returns row as an array
     *
     * @return array
     */
    public function array()
    {
        $data = [
            'cells' => [],
            'attributes' => $this->attributes
        ];
        foreach ($this->cells as $name => $cell) {
            $data['cells'][$name] = $cell->array();
        }
        return $data;
    }
}
