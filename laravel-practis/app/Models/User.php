<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id ID
 * @property int $company_id 会社ID
 * @property string $name 氏名
 * @property string $email メールアドレス
 * @property \Illuminate\Support\Carbon|null $email_verified_at メール認証日時
 * @property string $password パスワード
 * @property string|null $remember_token リメンバートークン
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role 権限
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Section> $sections
 * @property-read int|null $sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User searchCompany(\Illuminate\Http\Request $request)
 * @method static Builder|User searchSection(\Illuminate\Http\Request $request)
 * @method static Builder|User searchUser($request)
 * @method static Builder|User whenIsNotAdmin(\Illuminate\Http\Request $request)
 * @method static Builder|User whereCompanyId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeSearchCompany(Builder $builder, Request $request)
    {
        return $builder->when($request->search, function ($query, $search) {
            return $query->whereHas('company', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    public function scopeSearchSection(Builder $builder, Request $request)
    {
        return $builder->when($request->search, function ($query, $search) {
            return $query->whereHas('sections', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    public function scopeSearchUser($query, $request)
    {
        return $query->when($request->has('search') && $request->filled('search_user'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
    }


    public function scopeWhenIsNotAdmin(Builder $builder, Request $request)
    {
        return $builder->when(!$request->user()->isAdmin(), function (Builder $query) use ($request) {
            $query->where('company_id', $request->user()->company_id);
        });
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class);
    }
}
