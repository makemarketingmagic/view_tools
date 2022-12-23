<?php

namespace Makemarketingmagic\ViewTools\Table;


use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function config;

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
     * @param string|null $caption
     * @param array $attributes
     * @return $this
     */
    public function column(string|int $key, string $caption = null, array $attributes = []): static
    {
        if (empty($caption)) {
            $caption = Str::headline($key);
        }
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

    /**
     * @param int|null $limit
     * @return string
     */
    public function toHtml(int $limit = null): string
    {
        if (empty($limit)) {
            $limit = config('pagination.limit');
        }
        $this->table = new Table(['class' => 'model-table']);
        $rows = $this->query->paginate($limit);
        $this->table->before($rows->onEachSide(2)->render());
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
            $this->table->addRow(new TableRow($cells, [
                'id' => 'row-' . $rowData['id'],
                'data-row-id' => $rowData['id']
            ]));
        }
        return $this->table->html();
    }

}
