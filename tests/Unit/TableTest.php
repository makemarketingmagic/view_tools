<?php

namespace Makemarketingmagic\ViewTools\Tests\Unit;

use Makemarketingmagic\ViewTools\Table\Table;
use Makemarketingmagic\ViewTools\Table\TableCell;
use Makemarketingmagic\ViewTools\Table\TableColumn;
use Makemarketingmagic\ViewTools\Table\TableRow;
use Makemarketingmagic\ViewTools\Tests\TestCase;
use function file_put_contents;

class TableTest extends TestCase
{

    public function testTable()
    {
        $posts = [
            ['id' => 1, 'title' => 'Title 1'],
            ['id' => 2, 'title' => 'Title 2'],
            ['id' => 3, 'title' => 'Title 3'],
        ];
        $table = new Table([
            'table' => ['id' => 'post-table'],
            'head' => ['id' => 'post-table-head', 'class' => 'headers'],
            'body' => ['id' => 'post-table-body', 'class' => 'body'],
        ]);
        $table->before('before text', ['class' => 'before']);
        $table->after('after text', ['class' => 'after']);
        $table->addColumn('id', new TableColumn('ID', ['class' => 'id-column']));
        $table->addColumn('title', new TableColumn('TITLE', ['class' => 'title-column']));
        foreach ($posts as $post) {
            $cells = [
                'id' => new TableCell($post['id'], ['class' => 'id-cell']),
                'title' => new TableCell($post['title'], ['class' => 'title-cell']),
            ];
            $attrs = [
                'id' => 'post-' . $post['id']
            ];
            $table->addRow(new TableRow($cells, $attrs));
        }
        $html = $table->html();
       // file_put_contents('output.html', $html);
    }
}
