<?php


namespace App\Http\Controllers\Api\Cataloging;


use App\Common\Helpers\Controller\EditMaterialProcedure;
use App\Exceptions\ReturnResponseException;
use App\Http\Controllers\Api\Cataloging\Handler\MarcFieldsHandler;
use App\Http\Controllers\Api\Cataloging\Handler\MarcFieldsXmlHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cataloging\EditMaterialRequest;
use Illuminate\Http\JsonResponse;

class EditMaterialController extends Controller
{
    /**
     * @throws ReturnResponseException
     */
    public function __invoke(EditMaterialRequest $request, string $type, int $id): JsonResponse
    {
        $input = $request->validated()['data'];
        $template = (new MarcFieldsHandler($input))->getTemplate();
        $xml = (new MarcFieldsXmlHandler($template))->getXml();

        if ((int)EditMaterialProcedure::edit($this->getEditMaterialProcedureInput($input, $type, $id))['pRes'] !== 1) {
            throw new ReturnResponseException('Process error', 100);
        }

        if ((int)EditMaterialProcedure::loadXml([
                'id' => $id,
                'xml' => $xml,
                'emp_id' => $request->user()->emp_id,
            ])['pRes'] !== 1) {
            throw new ReturnResponseException('Process error', 100);
        }

        return response()->json([
            'res' => [
                'message' => 'success',
                'result' => true,
            ],
        ]);
    }

    /**
     * @param array $data
     * @param string $type
     * @param int $id
     * @return array
     */
    private function getEditMaterialProcedureInput(array $data, string $type, int $id): array
    {
        $input = [];
        $input['call_number'] = $this->getFieldData('010.a', $data);
        $input['isbn'] = $this->getFieldData('020.a', $data);
        $input['issn'] = $this->getFieldData('490.x', $data);
        $input['volume'] = $this->getFieldData('490.v', $data);
        $input['title'] = $this->getFieldData('245.a', $data);
        $input['publisher'] = $this->getFieldData('260.b', $data);
        $input['year'] = $this->getFieldData('260.c', $data);
        $input['city'] = $this->getFieldData('260.a', $data);
        $input['language'] = $this->getFieldData('546.a', $data);
        $input['main_author'] = $this->getFieldData('100.a', $data);
        $input['other_author'] = $this->getFieldData('600.a', $data, true);
        $input['page_number'] = $this->getFieldData('300.a', $data);
        $input['parallel_title'] = $this->getFieldData('245.b', $data);
        $input['title_related_info'] = $this->getFieldData('245.c', $data);
        $input['type'] = $type;
        $input['id'] = $id;

        return $input;
    }

    /**
     * @param string $key
     * @param array $data
     * @param bool $joinValue
     * @return mixed
     */
    private function getFieldData(string $key, array $data, bool $joinValue = false): mixed
    {
        $indexes = array_keys(array_column($data, 'id'), $key);

        if (!empty($indexes)) {
            switch (count($indexes)) {
                case 1:
                    return $data[$indexes[0]]['data'];
                default:
                    $value = '';
                    foreach ($indexes as $i => $index) {
                        if ($joinValue) {
                            if ($i < count($indexes) - 1) {
                                $value .= $data[$index]['data'] . ', ';
                            } else {
                                $value .= $data[$index]['data'];
                            }
                        } else {
                            if (!empty($data[$index]['repeatable'])) {
                                return $data[$index]['data'];
                            }
                        }
                    }
                    return $value;
            }
        }

        return '';
    }
}
