<?php

namespace Makemarketingmagic\ViewTools\Table;

use JetBrains\PhpStorm\ArrayShape;

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
     * @var string
     */
    protected string $content;

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
     * Returns row html
     *
     * @return string
     */
    public function html(): string
    {
        return view(config('view_tools_tables.views.cell'), [
            'content' => $this->content,
            'attributes' => Attribute::str($this->attributes)
        ])->render();
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
