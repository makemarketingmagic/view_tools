<?php

namespace Makemarketingmagic\ViewTools\Table;

use function is_array;
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
     * @param string|null $content
     * @param array $attributes
     */
    public function __construct($content, array $attributes = [])
    {
        $this->content = $content;
        $this->attributes = $attributes;
    }

    /**
     * Sets parent TableRow
     *
     * @param TableRow $row
     * @return TableCell
     */
    public function setRow(TableRow $row)
    {
        $this->row = $row;
        return $this;
    }

    /**
     * Returns row content
     *
     * @return array|string|null
     */
    public function getContent(): array|string|null
    {
        return $this->content;
    }

    /**
     * Sets row content
     *
     */
    public function setContent(array|string $content = null)
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
        if (is_array($content)) {
            $content = $key . '::' . $content['id'];
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
