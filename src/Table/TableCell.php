<?php

namespace Makemarketingmagic\ViewTools\Table;

use function is_callable;
use function is_null;

/**
 * Cell of a table row
 *
 */
class TableCell
{
    /**
     * Parent row reference
     *
     * @var TableRow|null
     */
    protected ?TableRow $row = null;

    /**
     * Content of cell
     *
     * @var array|string|null
     */
    protected array|string|null $content;

    /**
     * Attributes of td element
     *
     * @var array
     */
    protected array $attributes;

    /**
     * Class constructor
     *
     * @param string $content
     * @param array $attributes
     */
    public function __construct(string $content, array $attributes = [])
    {
        $this->content = $content;
        $this->attributes = $attributes;
    }

    /**
     * Sets parent TableRow
     *
     * @param TableRow $row
     */
    public function setRow(TableRow $row)
    {
        $this->row = $row;
    }

    /**
     * Returns row content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Returns row html
     *
     * @param int|string $key
     * @param callable|null $callback
     * @return string
     */
    public function html(int|string $key, callable $callback = null): string
    {
        $content = $this->content;
        if (is_callable($callback)) {
            $content = $callback($key, $content);
        }
        if (is_null($content)) {
            $content = config('view_tools_tables.nullValue');
        }
        $result = view(config('view_tools_tables.views.cell'), [
            'content' => $content,
            'attributes' => Attribute::str($this->attributes)
        ])->render();
        return trim($result);
    }

    /**
     * Returns cell as an array
     *
     * @return array
     */
    public function array(): array
    {
        return [
            'content' => $this->content,
            'attributes' => $this->attributes
        ];
    }
}
