<?php

namespace Makemarketingmagic\ViewTools\Tables;


use JetBrains\PhpStorm\Pure;
use Makemarketingmagic\ViewTools\Services\ArrayHelper;
use Throwable;
use function array_keys;
use function array_merge;
use function dump;
use function implode;
use function is_array;
use function is_callable;
use function is_null;
use function strtolower;
use function view;

class TableBuilder
{
    protected string $result;

    protected array $views = [
        'table' => 'view_tools::tables.table',
        'headers' => 'view_tools::tables.headers',
        'header' => 'view_tools::tables.header',
        'body' => 'view_tools::tables.tbody',
        'row' => 'view_tools::tables.row',
        'cell' => 'view_tools::tables.cell',
        'before' => 'view_tools::tables.before',
        'after' => 'view_tools::tables.after',
        'action' => 'view_tools::tables.action',
        'empty' => 'view_tools::tables.empty'
    ];

    protected array $attributes = [
        'table' => [],
        'headers' => [],
        'header' => [],
        'body' => [],
        'row' => [],
        'cell' => [],
    ];

    protected array $actions = [];
    protected array $headers = [];
    protected array $rows = [];
    protected string $before = '';
    protected string $after = '';

    protected string $nullValue = '-';

    protected array $callbacks = [];

    protected ArrayHelper $arrayHelper;

    /**
     * @param array $config
     */
    #[Pure] public function __construct(array $config = [])
    {
        if (!empty($config['views'])) {
            $this->views = $config['views'];
        }
        if (!empty($config['attributes'])) {
            $this->attributes = $config['attributes'];
        }
        if (!empty($config['nullValue'])) {
            $this->nullValue = $config['nullValue'];
        }
        $this->arrayHelper = new ArrayHelper();
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function toTable(): string
    {
        if (empty($this->rows)) {
            return view($this->views['empty']);
        }
        $content = $this->formatHeaders() .
            view($this->views['body'], [
                'content' => $this->formatRows(),
                'attributes' => $this->formatAttributes('body')
            ])->render();
        $this->result =
            $this->before .
            view($this->views['table'], [
                'content' => $content,
                'attributes' => $this->formatAttributes('table')
            ])->render() .
            $this->after;
        return $this->result;
    }

    /**
     * @param array $headers
     * @return TableBuilder
     * @throws Throwable
     */
    public function headers(array $headers): TableBuilder
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * @param array $row
     * @param null $rowId
     * @return $this
     */
    public function row(array $row, $rowId = null): TableBuilder
    {
        if (is_null($rowId)) {
            $this->rows[] = $row;
        } else {
            $this->rows[$rowId] = $row;
        }
        return $this;
    }

    /**
     * @return string
     * @throws Throwable
     */
    protected function formatRows(): string
    {
        $result = '';
        foreach ($this->rows as $rowId => $row) {
            $renderedRow = '';
            foreach ($row as $key => $column) {
                $attributes = [];
                if ($column instanceof Column) {
                    $attributes = $column->getAttributes();
                    dump($column->getField(), $row);exit;;
                    $column = $this->arrayHelper->get($column->getField(), $row);
                }
                $renderedRow .= $this->formatCell($key, $column, $attributes);
            }
            $renderedRow .= $this->formatActions($rowId);
            $this->attributes['row']['data-row-id'] = $rowId;
            $result .= view($this->views['row'], [
                'content' => $renderedRow,
                'attributes' => $this->formatAttributes('row')
            ])->render();
        }
        return $result;
    }

    /**
     * @param string $content
     * @return $this
     * @throws Throwable
     */
    public function before(string $content): TableBuilder
    {
        $this->before = view($this->views['before'], [
            'content' => $content,
            'attributes' => $this->formatAttributes('before')
        ])->render();
        return $this;
    }

    /**
     * @param string $content
     * @return $this
     * @throws Throwable
     */
    public function after(string $content): TableBuilder
    {
        $this->after = view($this->views['after'], [
            'content' => $content,
            'attributes' => $this->formatAttributes('after')
        ])->render();
        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes): TableBuilder
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * @param $caption
     * @param $routeName
     * @param array $arguments using {id} for primary key
     * @return $this
     */
    public function addAction($caption, $routeName, array $arguments = []): TableBuilder
    {
        $this->actions[$caption] = ['routeName' => $routeName, 'arguments' => $arguments];
        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setCellFormatter(callable $callback): TableBuilder
    {
        $this->callbacks['cell'] = $callback;
        return $this;
    }

    /**
     * @param $key
     * @param null $content
     * @param array $customAttributes
     * @return string
     * @throws Throwable
     */
    protected function formatCell($key, $content = null, array $customAttributes = []): string
    {
        if (!empty($this->callbacks['cell']) && is_callable($this->callbacks['cell'])) {
            $content = $this->callbacks['cell']($key, $content);
        }

        if (is_null($content)) {
            $content = $this->nullValue;
        }
        if (is_array($content)) {
            $content = '[array]';
        }
        if ($content instanceof Column) {
            $content = '[column]';
        }
        return view($this->views['cell'], [
            'content' => $content,
            'attributes' => $this->formatAttributes('cell', $customAttributes)
        ])->render();
    }

    /**
     * @return string
     * @throws Throwable
     */
    protected function formatHeaders(): string
    {
        $result = '';
        foreach ($this->headers as $header) {
            $result .= $this->formatHeader($header);
        }
        foreach (array_keys($this->actions) as $action) {
            $result .= $this->formatHeader($action);
        }
        return view($this->views['headers'], [
            'content' => $result,
            'attributes' => $this->formatAttributes('headers')
        ])->render();
    }

    /**
     * @param string $content
     * @return string
     * @throws Throwable
     */
    protected function formatHeader(string $content): string
    {
        return view($this->views['header'], [
            'content' => $content,
            'attributes' => $this->formatAttributes('header')
        ])->render();
    }

    /**
     * @param string $key
     * @param array $customAttributes
     * @return string
     */
    protected function formatAttributes(string $key, $customAttributes = []): string
    {
        if (empty($this->attributes[$key])) {
            return '';
        }
        $result = [];
        $attributes = array_merge($this->attributes[$key], $customAttributes);
        foreach ($attributes as $attribute => $value) {
            $result[] = $attribute . '="' . $value . '"';
        }
        return ' ' . implode(' ', $result);
    }

    /**
     * @param $rowId
     * @return string
     * @throws Throwable
     */
    protected function formatActions($rowId): string
    {
        if (empty($this->actions)) {
            return '';
        }
        $result = '';
        foreach ($this->actions as $caption => $routeParams) {
            $result .= $this->formatAction($rowId, $caption, $routeParams);
        }
        return $result;
    }

    /**
     * @param $rowId
     * @param string $caption
     * @param array $routeParams
     * @return string
     * @throws Throwable
     */
    protected function formatAction($rowId, string $caption, array $routeParams): string
    {
        $arguments = [];
        foreach ($routeParams['arguments'] as $key => $value) {
            if ($value == '{id}') {
                $value = $rowId;
            }
            $arguments[$key] = $value;
        }
        $content = view($this->views['action'], [
            'caption' => $caption,
            'routeName' => $routeParams['routeName'],
            'arguments' => $arguments,
        ]);
        return $this->formatCell($caption, $content, ['class' => 'action-' . strtolower($caption)]);
    }

    /**
     * @param string $type
     * @param callable $callback
     */
    protected function setCallback(string $type, callable $callback)
    {
        $this->callbacks[$type] = $callback;
    }
}
