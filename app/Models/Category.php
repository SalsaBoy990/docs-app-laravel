<?php

namespace App\Models;

use App\Casts\HtmlSpecialCharsCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Mews\Purifier\Casts\CleanHtml;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'content',
        'category_id',
        'user_id',
//        'phonetic_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => HtmlSpecialCharsCast::class,
        'content' => CleanHtml::class,
    ];

    /**
     * Category has many documents
     *
     * @return HasMany @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Category has children categories
     *
     * @return HasMany @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->with('categories');
    }

    /**
     * Category belongs to many users
     * this will be used for authorization
     *
     * @return BelongsToMany @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot('permissions');
    }


    /**
     * Attach permissions
     *
     * @param mixed $user
     * @param mixed $category
     * @param string $permission
     *
     * @return bool
     */
    public static function attachPermission($user, $category, string $permission): bool
    {
        $permissions = $user->permissions;

        if ($permissions) {
            // it can contain other permissions as well (for future usage), so make sure to have the
            // permission in the array
            if (!array_key_exists($permission, $permissions)) {
                $permissions[$permission] = [$category->id];

                $user->update([
                    'permissions' => $permissions
                ]);
            } else {
                // already there, so it needs to be removed
                $oldPermissions = $permissions[$permission];
                $categoryPermissionExists = array_search($category->id, $oldPermissions);

                if ($categoryPermissionExists === false) {

                    $newPermissions = $oldPermissions;
                    array_push($newPermissions, $category->id);

                    $permissions[$permission] = $newPermissions;

                    $user->update([
                        'permissions' => $permissions,
                    ]);
                } else {
                    return false;
                }
            }
        } else {
            $permissions[$permission] = [$category->id];
            $user->update([
                'permissions' => $permissions,
            ]);
        }

        return true;
    }


    /**
     * Detach permissions
     *
     * @param User $user
     * @param Category $category
     * @param string $permission
     *
     * @return bool
     */
    public static function detachPermission(User $user, Category $category, string $permission): bool
    {
        // if already exits, making sure to delete the appropriate permission
        $permissions = $user->permissions;

        if ($permissions) {
            // it can contain other permissions as well (for future usage), so make sure to have the
            // permission in the array
            if (!array_key_exists($permission, $permissions)) {
                return false;
            } else {

                $savedPermissions = $permissions[$permission];
                $categoryPermissionKey = array_search($category->id, $savedPermissions);

                if ($categoryPermissionKey === false) {
                    return false;
                } else {
                    // delete the category id from the array
                    unset($savedPermissions[$categoryPermissionKey]);

                    // update the full permissions array
                    $permissions[$permission] = $savedPermissions;

                    $user->update([
                        'permissions' => $permissions,
                    ]);
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Check if permission exists
     *
     * @param mixed $user
     * @param mixed $category
     * @param string $permission
     *
     * @return bool
     */
    public static function checkPermission(User $user, Category $category, string $permission): bool
    {
        $permissions = $user->permissions;
        if (!$permissions) {
            return false;
        }

        if (array_key_exists($permission, $permissions)) {
            $savedPermissions = $permissions[$permission];

            return array_search($category->id, $savedPermissions) !== false;
        }
        return false;
    }
}
