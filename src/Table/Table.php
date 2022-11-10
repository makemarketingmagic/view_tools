<?php

namespace Makemarketingmagic\ViewTools\Table;

use function array_merge_recursive;

/**
 * Main Table class, you can add columns and rows to it
 *
 */
class Table
{

    /**
     * Tag attributes
     *
     * @var array
     */
    protected array $attributes = [
        'table' => [],
        'head' => [],
        'body' => [],
        'before' => [],
        'after' => []
    ];

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
     * Appended html after table
     */
    protected string $after = '';

    /**
     * Constructor
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['table'])) {
            $attributes['table'] = $attributes;
        }
        $this->attributes = array_merge_recursive($this->attributes, $attributes);
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
        if (empty($this->rows)) {
            $html = view(config('view_tools_tables.views.empty'), [
                'content' => config('view_tools_tables.noResultsText')
            ])->render();
        } else {
            $html = view(config('view_tools_tables.views.table'), [
                'content' => $this->headHtml() . $this->bodyHtml() . $this->footHtml(),
                'attributes' => Attribute::str($this->attributes['table'])
            ])->render();
        }
        return
            $this->beforeHtml() .
            $html .
            $this->afterHtml();
    }

    /**
     * @param string $content
     * @param array $attributes
     */
    public function before(string $content, array $attributes = [])
    {
        $this->before = $content;
        $this->attributes['before'] = $attributes;
    }

    /**
     * @param string $content
     * @param array $attributes
     */
    public function after(string $content, array $attributes = [])
    {
        $this->after = $content;
        $this->attributes['after'] = $attributes;
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
            'attributes' => Attribute::str($this->attributes['before'])
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
            'attributes' => Attribute::str($this->attributes['after'])
        ])->render();
    }

    /**
     * Generates thead html
     *
     * @return string
     */
    protected function headHtml(): string
    {
        if (empty($this->columns)) {
            return '';
        }
        $html = '';
        foreach ($this->columns as $col) {
            $html .= $col->html();
        }
        return view(config('view_tools_tables.views.headers'), [
            'content' => $html,
            'attributes' => Attribute::str($this->attributes['head'])
        ])->render();
    }

    /**
     * Generates tbody html
     *
     * @return string
     */
    protected function bodyHtml(): string
    {
        if (empty($this->rows)) {
            return '';
        }
        $html = '';
        foreach ($this->rows as $row) {
            $html .= $row->html();
        }
        return view(config('view_tools_tables.views.body'), [
            'content' => $html,
            'attributes' => Attribute::str($this->attributes['body'])
        ])->render();
    }

    /**
     * Generates tfoot html
     *
     * @return string
     */
    protected function footHtml(): string
    {
        return view(config('view_tools_tables.views.footer'))->render();
    }
}
