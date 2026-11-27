<?php

namespace App\Http\Requests\Admin;

use App\Services\SectionContentService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $slug = (string) $this->route('section');
        $key = SectionContentService::SLUG_TO_KEY[$slug] ?? $slug;
        $def = SectionContentService::defaults($key);

        if (! $def) {
            return [];
        }

        $rules = [];
        foreach (array_keys($def['data']) as $field) {
            $max = in_array($field, ['maps_embed', 'maps_link', 'routes_link', 'intro', 'intro_1', 'intro_2', 'venue_intro', 'confirm_box_intro', 'bottom_quote'], true)
                ? 2000
                : 500;
            $rules[$field] = ['nullable', 'string', "max:{$max}"];
        }

        return $rules;
    }
}
