<?php

namespace Makemarketingmagic\ViewTools\Tests\Unit;

use DOMDocument;
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
        $table
            ->before('before text', ['class' => 'before'])
            ->after('after text', ['class' => 'after'])
            ->addColumn('id', new TableColumn('ID', ['class' => 'id-column']))
            ->addColumn('title', new TableColumn('TITLE', ['class' => 'title-column']));
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
        $this->assertSame($this->expectedResult, $html);

//         file_put_contents('output.html', $html);
    }

    protected string $expectedResult = <<<EOF
<div class="before">before text</div>
<table id="post-table"><thead id="post-table-head" class="headers"><tr><th class="id-column">ID</th>
<th class="title-column">TITLE</th>
</tr></thead>
<tbody id="post-table-body" class="body"><tr id="post-1"><td class="id-cell">1</td>
<td class="title-cell">Title 1</td>
</tr>
<tr id="post-2"><td class="id-cell">2</td>
<td class="title-cell">Title 2</td>
</tr>
<tr id="post-3"><td class="id-cell">3</td>
<td class="title-cell">Title 3</td>
</tr>
</tbody>
<tfoot></tfoot>
</table>
<div class="after">after text</div>

EOF;

}
