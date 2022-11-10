<?php

namespace Makemarketingmagic\ViewTools\Tables;

use Makemarketingmagic\ViewTools\Services\ArrayHelper;
use Throwable;
use function array_keys;
use function array_merge;
use function config;
use function implode;

class HtmlTable
{

    protected array $views = [];
    protected string $nullValue = '-';
    protected array $attributes = [];
    protected ArrayHelper $arrayHelper;

    protected array $headers = [];


    /**
     *
     */
    public function __construct()
    {
        $this->views = config('view_tools_tables.views');
        $this->attributes = config('view_tools_tables.attributes');
        $this->nullValue = config('view_tools_tables.nullValue');
        $this->arrayHelper = new ArrayHelper();
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
    protected function formatAttributes(string $key, array $customAttributes = []): string
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

}
