<?php

namespace Makemarketingmagic\ViewTools\Tests\Unit;

use Makemarketingmagic\ViewTools\Table\Table;
use Makemarketingmagic\ViewTools\Table\TableCell;
use Makemarketingmagic\ViewTools\Table\TableColumn;
use Makemarketingmagic\ViewTools\Table\TableRow;
use Makemarketingmagic\ViewTools\Tests\TestCase;

class TableTest extends TestCase
{

    public function testTableFeatures()
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
        $table
            ->before('before text', ['class' => 'before'])
            ->after('after text', ['class' => 'after'])
            ->addColumn('id', new TableColumn('ID', ['class' => 'id-column']))
            ->addColumn('title', new TableColumn('TITLE', ['class' => 'title-column']))
            ->setCellFormatter(fn($key, $value) => $key == 'id' ? "#" . $value: $value);
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
        $this->assertSame($this->expectedFeatureResult, $html);

//         file_put_contents('output.html', $html);
    }

    public function testTableBare()
    {
        $posts = [
            ['id' => 1, 'title' => 'Title 1'],
            ['id' => 2, 'title' => 'Title 2'],
            ['id' => 3, 'title' => 'Title 3'],
        ];
        $table = new Table();
        foreach ($posts as $post) {
            $cells = [
                'id' => new TableCell($post['id']),
                'title' => new TableCell($post['title']),
            ];
            $table->addRow(new TableRow($cells));
        }
        $html = $table->html();
        $this->assertSame($this->expectedBareResult, $html);

//         file_put_contents('output.html', $html);
    }

    protected string $expectedFeatureResult = <<<EOF
<div class="before">before text</div><table id="post-table"><thead id="post-table-head" class="headers"><tr><th class="id-column">ID</th>
<th class="title-column">TITLE</th>
</tr></thead><tbody id="post-table-body" class="body"><tr id="post-1"><td class="id-cell">#1</td><td class="title-cell">Title 1</td></tr>
<tr id="post-2"><td class="id-cell">#2</td><td class="title-cell">Title 2</td></tr>
<tr id="post-3"><td class="id-cell">#3</td><td class="title-cell">Title 3</td></tr>
</tbody><tfoot></tfoot></table><div class="after">after text</div>
EOF;

    protected string $expectedBareResult = <<<EOF
<table><tbody><tr><td>1</td><td>Title 1</td></tr>
<tr><td>2</td><td>Title 2</td></tr>
<tr><td>3</td><td>Title 3</td></tr>
</tbody><tfoot></tfoot></table>
EOF;

}
