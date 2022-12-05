<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CommentProduct::class);
    }

    protected function createdAt(): Attribute
    {
        Carbon::setLocale('pt_BR');

        return Attribute::make(
            get: fn ($value) => Carbon::make($value)->format('d/m/Y') . ' (' . Carbon::make($value)->diffForHumans() . ') '       
        );
    }

    public function getProducts(string|null $filter = ''): LengthAwarePaginator
    {
        $products = $this
                    ->when(function ($query) use ($filter) {
                        $query->where('name', 'LIKE', "%{$filter}%");     
                    })
                    ->with('user')
                    ->paginate(5);

        return $products;
    }

    public function getLastThreeProductsForStatus(string|null $status = ''): object
    {

        return Product::query()
                    ->when($status == 'last_registered', fn($query) => $query->orderBy('created_at', 'DESC'))
                    ->when($status == 'cheap', fn($query) => $query->orderBy('price', 'ASC'))
                    ->when($status == 'expensive', fn($query) => $query->orderBy('price', 'DESC'))
                    ->get()
                    ->take(3);

    }
    
}