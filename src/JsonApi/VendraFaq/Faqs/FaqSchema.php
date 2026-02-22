<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\JsonApi\VendraFaq\Faqs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraFaq\Models\Faq;

final class FaqSchema extends Schema
{
    /**
     * @var class-string<Faq>
     */
    public static string $model = Faq::class;

    protected $defaultSort = 'position';

    /**
     * @return list<object>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('faqCategory')->type('categories')->readOnly(),
            Str::make('name'),
            Str::make('description'),
            Str::make('slug'),
            Number::make('position')->sortable(),
            Boolean::make('status'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
        ];
    }

    /**
     * @return list<object>
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            Where::make('faqCategoryId', 'faq_category_id'),
            Where::make('position'),
        ];
    }

    public function pagination(): ?Paginator
    {
        return PagePagination::make()->withDefaultPerPage(15);
    }

    /**
     * @param Builder<Faq> $query
     * @return Builder<Faq>
     */
    public function indexQuery(?Request $request, Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->whereHas('faqCategory', function (Builder $faqCategoryQuery): void {
                $faqCategoryQuery->where('status', true);
            })
            ->orderBy('position');
    }
}
