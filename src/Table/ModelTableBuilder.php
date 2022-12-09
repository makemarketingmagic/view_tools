<?php

namespace Makemarketingmagic\ViewTools\Table;


use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelTableBuilder
{
    protected Builder $query;
    protected Table $table;
    protected array $columns = [];
    protected Closure $cellFormatter;

    /**
     * @param Builder $query
     * @return $this
     */
    public function query(Builder $query): static
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param string|int $key
     * @param string $caption
     * @param array $attributes
     * @return $this
     */
    public function column(string|int $key, string $caption, array $attributes = []): static
    {
        $this->columns[$key] = new TableColumn($caption, $attributes);
        return $this;
    }

    public function setCellFormatter(callable $callback)
    {
        $this->cellFormatter = $callback;
    }

    /**
     * Assumes $this->table is set...
     *
     * @param Model $row
     */
    protected function setDefaultColumns(Model $row)
    {
        foreach ($row as $key => $field) {
            $this->table->addColumn($key, new TableColumn(Str::headline($key), ['class' => 'header']));
        }
    }

    /**
     * Assumes $this->table is set...
     *
     * @param Model $row
     */
    protected function setColumns(Model $row)
    {
        if (empty($this->columns)) {
            $this->setDefaultColumns($row);
            return;
        }
        foreach ($this->columns as $key => $column) {
            $this->table->addColumn($key, $column);
        }
    }

    public function toHtml(): string
    {
        $this->table = new Table();
        // todo: get limit from somewhere meaningful...
        $limit = 3;
        $rows = $this->query->paginate($limit);
        if (!empty($this->cellFormatter)) {
            $this->table->setCellFormatter($this->cellFormatter);
        }
        $first = true;
        /** @var Model $row */
        foreach ($rows as $row) {
            $rowData = $row->toArray();
            if ($first) {
                $first = false;
                $this->setColumns($row);
            }
            $cells = [];
            foreach ($rowData as $key => $field) {
                $cells[$key] = new TableCell($field);
            }
            $this->table->addRow(new TableRow($cells, ['id' => 'row-' . $rowData['id']]));
        }
        return $this->table->html();
    }

}
