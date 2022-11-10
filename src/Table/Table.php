<?php

namespace Makemarketingmagic\ViewTools\Table;

/**
 * Main Table class, you can add columns and rows to it
 *
 */
class Table
{
    /**
     * Table tag attributes
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * Table columns
     *
     * @var array
     */
    protected array $columns = [];

    /**
     * Table rows
     *
     * @var array
     */
    protected array $rows = [];

    /**
     * Table data
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Prepended html before table
     */
    protected string $before = '';

    /**
     * Before tag attributes
     *
     * @var array
     */
    protected array $beforeAttributes = [];

    /**
     * Appended html after table
     */
    protected string $after = '';

    /**
     * After tag attributes
     *
     * @var array
     */
    protected array $afterAttributes = [];

    /**
     * Constructor
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Adds a new Column
     *
     * @param string $name
     * @param TableColumn $column
     */
    public function addColumn(string $name, TableColumn $column)
    {
        $this->columns[$name] = $column;
    }

    /**
     * Returns columns array
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Adds a new Row
     *
     * @param TableRow $row
     */
    public function addRow(TableRow $row)
    {
        $row->setTable($this);
        $this->rows[] = $row;
    }

    /**
     * Returns rows
     *
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * Generates html
     *
     * @return string
     */
    public function html(): string
    {
        $html =
            $this->headHtml() .
            $this->bodyHtml() .
            $this->footHtml();
        return
            $this->beforeHtml() .
            view(config('view_tools_tables.views.table'), [
                'content' => $html,
                'attributes' => Attribute::str($this->attributes)
            ])->render() .
            $this->afterHtml();
    }

    /**
     * @param string $content
     * @param array $attributes
     */
    public function before(string $content, array $attributes = [])
    {
        $this->before = $content;
        $this->beforeAttributes = $attributes;
    }

    /**
     * @param string $content
     * @param array $attributes
     */
    public function after(string $content, array $attributes = [])
    {
        $this->after = $content;
        $this->afterAttributes = $attributes;
    }

    /**
     * Returns table information as an array
     *
     * @return array
     */
    public function array(): array
    {
        $data = [
            'attributes' => $this->attributes,
        ];
        foreach ($this->columns as $name => $column) {
            $data['columns'][$name] = $column->array();
        }
        foreach ($this->rows as $row) {
            $data['rows'][] = $row->array();
        }
        return $data;
    }

    /**
     * Returns rendered before
     *
     * @return string
     */
    protected function beforeHtml(): string
    {
        if (empty($this->before)) {
            return '';
        }
        return view(config('view_tools_tables.views.before'), [
            'content' => $this->before,
            'attributes' => Attribute::str($this->beforeAttributes)
        ])->render();
    }

    /**
     * Returns rendered after
     *
     * @return string
     */
    protected function afterHtml(): string
    {
        if (empty($this->after)) {
            return '';
        }
        return view(config('view_tools_tables.views.after'), [
            'content' => $this->after,
            'attributes' => Attribute::str($this->afterAttributes)
        ])->render();
    }

    /**
     * Generates thead html
     *
     * @return string
     */
    public function headHtml(): string
    {
        $html = '';
        foreach ($this->columns as $col) {
            $html .= $col->html();
        }
        return view(config('view_tools_tables.views.headers'), [
            'content' => $html,
            'attributes' => Attribute::str($this->attributes)
        ])->render();
    }

    /**
     * Generates tbody html
     *
     * @return string
     */
    public function bodyHtml(): string
    {
        $html = '';
        foreach ($this->rows as $row) {
            $html .= $row->html();
        }
        return view(config('view_tools_tables.views.body'), [
            'content' => $html,
            'attributes' => Attribute::str($this->attributes)
        ])->render();
    }

    /**
     * Generates tfoot html
     *
     * @return string
     */
    public function footHtml(): string
    {
        return view(config('view_tools_tables.views.footer'))->render();
    }
}
