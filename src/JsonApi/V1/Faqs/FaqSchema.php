<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\JsonApi\V1\Faqs;

use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Filters\Has;
use LaravelJsonApi\Eloquent\Filters\OnlyTrashed;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereDoesntHave;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Filters\WhereIn;
use LaravelJsonApi\Eloquent\Filters\WhereNotIn;
use LaravelJsonApi\Eloquent\Filters\WithTrashed;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraFaq\Models\Faq;

final class FaqSchema extends Schema
{
    public static string $model = Faq::class;

    protected ?array $defaultPagination = ['number' => 1];

    public function fields(): array
    {
        return [
            ID::make(),
            ArrayHash::make('name'),
            ArrayHash::make('description'),
            ArrayHash::make('slug'),
            Number::make('position')
                ->sortable()
                ->readOnly(),
            Boolean::make('status'),
            DateTime::make('created_at')
                ->sortable()
                ->readOnly(),
            DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),
            BelongsTo::make('faqCategory')
                ->readOnly(),
            BelongsToMany::make('multimedia')
                ->readOnly(),
        ];
    }

    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            WhereIdNotIn::make($this, 'exclude'),
            Where::make('faq-category', 'faq_category_id'),
            Where::make('slug', 'slug->fa')
                ->singular(),
            Where::make('status')
                ->asBoolean(),
            WhereHas::make($this, 'faqCategory', 'with-faq-category'),
            WhereDoesntHave::make($this, 'faqCategory', 'without-faq-category'),
            WhereIn::make('in-faq-category', 'faq_category_id'),
            WhereNotIn::make('not-in-faq-category', 'faq_category_id'),
            Has::make($this, 'multimedia', 'has-multimedia'),
            WhereHas::make($this, 'multimedia', 'with-multimedia'),
            WhereDoesntHave::make($this, 'multimedia', 'without-multimedia'),
            WithTrashed::make('with-trashed'),
            OnlyTrashed::make('trashed'),
        ];
    }

    public function includePaths(): iterable
    {
        return [
            'faqCategory',
            'multimedia',
        ];
    }

    public function pagination(): PagePagination
    {
        return PagePagination::make();
    }
}
