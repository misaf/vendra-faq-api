<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\JsonApi\VendraFaq\Categories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraFaq\Models\FaqCategory;

final class FaqCategorySchema extends Schema
{
    /**
     * @var class-string<FaqCategory>
     */
    public static string $model = FaqCategory::class;

    protected $defaultSort = 'position';

    public static function type(): string
    {
        return 'categories';
    }

    /**
     * @return list<object>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('name'),
            Str::make('description'),
            Str::make('slug'),
            Number::make('position')->sortable(),
            Boolean::make('status'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
            HasMany::make('faqs')->readOnly(),
        ];
    }

    /**
     * @return list<object>
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            Where::make('position'),
        ];
    }

    public function pagination(): ?Paginator
    {
        return PagePagination::make()->withDefaultPerPage(15);
    }

    /**
     * @param Builder<FaqCategory> $query
     * @return Builder<FaqCategory>
     */
    public function indexQuery(?Request $request, Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->orderBy('position');
    }
}
