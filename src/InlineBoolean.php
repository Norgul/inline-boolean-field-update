<?php

namespace Wehaa\LiveupdateBoolean;

use Illuminate\Support\Facades\Config;
use Laravel\Nova\Fields\Boolean;

class InlineBoolean extends Boolean
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'liveupdate-boolean';

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param mixed $resource
     * @param string $attribute
     * @return bool|null
     */
    protected function resolveAttribute($resource, $attribute): ?bool
    {
        $this->setResourceId(data_get($resource, $resource->getKeyName()));

        return parent::resolveAttribute($resource, $attribute);
    }

    /**
     * @param int|null $id
     *
     * @return InlineBoolean
     */
    protected function setResourceId(?int $id): self
    {
        return $this->withMeta(['id' => $id, 'nova_path' => Config::get('nova.path')]);
    }

    public function withinRelation(string $modelClass): self
    {
        return $this->withMeta(['extraData' => [
            'relationClass' => $modelClass,
        ]]);
    }
}
