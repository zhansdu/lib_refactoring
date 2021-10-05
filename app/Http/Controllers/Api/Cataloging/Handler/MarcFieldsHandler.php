<?php


namespace App\Http\Controllers\Api\Cataloging\Handler;

use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use stdClass;

/**
 * Class MarcFieldsHandler
 */
final class MarcFieldsHandler
{
    private const MARC_FIELDS_COLUMNS = [
        'ID',
        'PID',
        'FIELD_CODE',
        'IND1',
        'IND2',
        'TITLE',
        'DATA',
        'ADD',
        'DELETE',
        'REPEATABLE'
    ];

    /**
     * @var array
     */
    private array $template;

    /**
     * MarcFieldsHandler constructor.
     * @param array $template
     * @param array $input
     */
    public function __construct(array $template = [], array $input = [])
    {
        $this->template = $this->generateTemplate($template, $input);
    }

    /**
     * @return array
     */
    public function getTemplate(): array
    {
        return $this->template;
    }

    /**
     * @param array $template
     * @param array $input
     * @return array
     */
    #[ArrayShape(['Columns' => "mixed", 'Nodes' => "mixed"])]
    private function generateTemplate(array $template = [], array $input = []): array
    {
        $marcFields = !empty($template) ? $template : self::getMarcFields();

        return [
            'Columns' => $this->generateColumns(),
            'Nodes' => $this->generateNodes($marcFields, $input),
        ];
    }

    /**
     * @return array
     */
    private function generateColumns(): array
    {
        $columns = [];
        foreach (self::MARC_FIELDS_COLUMNS as $i => $column) {
            $columns["__custom:Column:$i"] = [
                'ColumnName' => $column,
                'ColumnType' => 'Bound'
            ];
        }

        return $columns;
    }

    /**
     * @param array $template
     * @param array $input
     * @return array
     */
    private function generateNodes(array $template, array $input = []): array
    {
        $nodes = [];
        $id = -1;
        $lastParentId = -1;

        self::mergeTemplateWithInput($template, $input);

        foreach ($template as $field) {
            $id++;

            if ($field instanceof stdClass)  {
                $field = json_decode(json_encode($field), true);
            }

            $lastParentId = $field['pid'] ? $lastParentId === -1 ? ($id - 1) : $lastParentId : -1;

            $cells = [
                $this->generateCell($field['id']),
                $this->generateCell($field['pid'], 2),
                $this->generateCell($field['field_code'], 3),
                $this->generateCell($field['ind1'], 4),
                $this->generateCell($field['ind2'], 5),
                $this->generateCell($field['title'], 6),
                $this->generateCell($field['data'], 7),
                $this->generateCell(null, 8),
                $this->generateCell(null, 9),
                $this->generateCell($field['repeatable'], 10, true),
            ];

            $nodeData = [];

            foreach ($cells as $cell) {
                $nodeData[$cell['key']] = $cell['value'];
            }

            $count = $id + 1;

            $nodes["__custom:Node:$count"] = [
                '_attributes' => [
                    'Id' => $id,
                    'ParentId' => $lastParentId
                ],
                'NodeData' => $nodeData
            ];
        }

        return $nodes;
    }

    /**
     * @param mixed $value
     * @param int $count
     * @param bool $isNumeric
     * @return array
     */
    private function generateCell(mixed $value, int $count = 1, bool $isNumeric = false): array
    {
        $cell = [];
        $cell['key'] = "__custom:Cell:$count";

        if ($isNumeric || !empty($value)) {
            if ($isNumeric) {
                $cell['value'] = [
                    '_attributes' => [
                        'xsi:type' => 'decimal'
                    ],
                    '_value' => $value ?? ''
                ];
            } else {
                $cell['value'] = [
                    '_attributes' => [
                        'xsi:type' => 'string'
                    ],
                    '_value' => $value ?? ''
                ];
            }

            return $cell;
        }

        $cell['value'] = [
            '_attributes' => [
                'xsi:nil' => 'true'
            ],
            '_value' => ''
        ];

        return $cell;
    }

    /**
     * @return array
     */
    private static function getMarcFields(): array
    {
        return DB::table('marc_fields')->select()->orderBy('id')->get()->toArray();
    }

    private static function mergeTemplateWithInput(array &$template, array $input)
    {
        $seenFields = [];

        foreach ($input as $field) {
            $indexOfTemplate = array_search($field->id, array_column($template, 'id'));

            if ($indexOfTemplate !== false) {
                if ($field->pid === null) {
                    $template[$indexOfTemplate] = $field;
                    continue;
                }

                if ($field->repeatable === 1 || $template[$indexOfTemplate]->repeatable === 1) {
                    $template[$indexOfTemplate]->repeatable = 1;
                    $field->repeatable = 1;
                }

                if (!in_array($field->id, $seenFields)) {
                    $seenFields[] = $field->id;
                    $template[$indexOfTemplate] = $field;
                    continue;
                }

                $template[] = $field;
                continue;
            }

            $template[] = $field;
        }
    }

    private static function mergeTemplateWithActualInput(array $template, object $input): void
    {
        foreach ($template as $field) {
            switch ($field->id) {
                case '020.a':
                    $field->data = $input->isbn;
                    break;
                case '245.a':
                    $field->data = $input->title;
                    break;
                case '245.b':
                    $field->data = $input->parallel_title;
                    break;
                case '245.c':
                    $field->data = $input->title_related_info;
                    break;
                case '260.a':
                    $field->data = $input->pub_city;
                    break;
                case '260.b':
                    $field->data = $input->publisher;
                    break;
                case '260.c':
                    $field->data = $input->pub_year;
                    break;
                case '100.a':
                    $field->data = $input->main_author;
                    break;
                case '300.a':
                    $field->data = $input->page_num;
                    break;
                case '546.a':
                    $field->data = $input->language;
                    break;
            }
        }
    }

    /**
     * @param array $input
     * @param object|null $actualData
     * @return array
     */
    public static function generateArray(array $input = [], object $actualData = null): array
    {
        $marcFields = self::getMarcFields();

        self::mergeTemplateWithInput($marcFields, $input);

        if (!empty($actualData)) {
            self::mergeTemplateWithActualInput($marcFields, $actualData);
        }

        foreach ($marcFields as &$field) {
            $field = json_decode(json_encode($field), true);
        }

        return $marcFields;
    }
}
