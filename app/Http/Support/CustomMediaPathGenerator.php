<?php

namespace App\Http\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class CustomMediaPathGenerator extends DefaultPathGenerator
{
    public function getPath(Media $media): string
    {
        $tenantId = $this->getTenantId();
        $collectionName = $media->collection_name;

        return "{$tenantId}/{$collectionName}/{$media->id}/";
    }

    public function getPathForConversions(Media $media): string
    {
        $tenantId = $this->getTenantId();
        $collectionName = $media->collection_name;

        return "{$tenantId}/{$collectionName}/{$media->id}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        $tenantId = $this->getTenantId();
        $collectionName = $media->collection_name;

        return "{$tenantId}/{$collectionName}/{$media->id}/responsive-images/";
    }

    private function getTenantId(): string
    {
        $tenant = tenant();
        return $tenant ? $tenant->id : 'central';
    }
}
