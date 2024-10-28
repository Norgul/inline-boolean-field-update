<?php

namespace Wehaa\LiveupdateBoolean\Http\Controllers;

use Exception;
use Illuminate\Support\Arr;
use Laravel\Nova\Http\Requests\NovaRequest;

class InlineBooleanFieldController
{
    public function update(NovaRequest $request)
    {
        $modelId = $request->_inlineResourceId;
        $attribute = $request->_inlineAttribute;

        $relationClass = Arr::get($request->get('extraData'), 'relationClass');

        $resourceClass = $request->resource();
        $resourceValidationRules = $resourceClass::rulesForUpdate($request);
        $fieldValidationRules = $resourceValidationRules[$attribute] ?? null;

        if (!empty($fieldValidationRules)) {
            $request->validate([$attribute => $fieldValidationRules]);
        }

        try {
            $model = $relationClass ? (new $relationClass) : $request->model();

            $model->find($modelId)->update([
                $attribute => $request->{$attribute},
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response('', 204);
    }
}
