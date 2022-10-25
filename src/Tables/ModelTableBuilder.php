<?php

namespace Makemarketingmagic\ViewTools\Tables;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Throwable;
use function array_keys;

class ModelTableBuilder extends TableBuilder
{

    /** @var Builder */
    protected Builder $query;

    protected $limit;

    protected $columns = [];

    public function toTable(): string
    {
        if (empty($this->query)) {
            throw new Exception('No model set');
        }
        $this->build($this->query->paginate());
        return parent::toTable();
    }

    /**
     * @param LengthAwarePaginator $page
     * @throws Throwable
     */
    protected function build(LengthAwarePaginator $page)
    {
        if (empty($page->items())) {
            return;
        }
        $this->headers($this->getHeaders($page));
        /** @var Model $item */
        foreach ($page->items() as $item) {
            $this->row($item->toArray(), $item->getKey());
        }
        $this->after($page->links());
    }

    public function row(array $row, $rowId = null): TableBuilder
    {
        if (!empty($this->columns)) {
            $result = [];
//            $arrayHelper = new ArrayHelper();
            foreach ($this->columns as $column) {
                $result[$column->getField()] = $column;//$arrayHelper->get($column->getField(), $row);
            }
        } else {
            $result = $row;
        }
        return parent::row($result, $rowId);
    }

    protected function getHeaders(LengthAwarePaginator $page): array
    {
        if (!empty($this->columns)) {
            $result = [];
            /** @var Column $column */
            foreach ($this->columns as $column) {
                $result[] = $column->getCaption();
            }
            return $result;
        }
        return array_keys($page->first()->toArray());
    }

    public function query(Builder $query): ModelTableBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function column(Column $column): ModelTableBuilder
    {
        $this->columns[] = $column;
        return $this;
    }

    /**
     * @param array[Column] $columns
     * @return $this
     */
    public function columns(array $columns): ModelTableBuilder
    {
        $this->columns = $columns;
        return $this;
    }

    protected function formatAction($rowId, string $caption, array $routeParams): string
    {
        $row = $this->rows[$rowId];
        foreach ($routeParams['arguments'] as $key => $value) {
            if (Str::startsWith($value, '{') && Str::endsWith($value, '}')) {
                $columnName = trim($value, '{}');
                if (isset($row[$columnName])) {
                    $routeParams['arguments'][$key] = $row[$columnName];
                }
            }
        }
        return parent::formatAction($rowId, $caption, $routeParams);
    }

}
