<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Http\RedirectResponse;


class CategoryUserController extends Controller
{
    use InteractsWithBanner;

    /**
     * @param  Category  $category
     * @param  User  $user
     *
     * @return RedirectResponse
     */
    public function attachDownloadPermission(Category $category, User $user): RedirectResponse
    {
        $success = Category::attachPermission($user, $category, User::PERMISSIONS['download']);

        if ($success) {
            $this->banner('Download permission was added.');
        } else {
            $this->banner('You already have the download permission for this category.');
        }

        return redirect()->route('dashboard');
    }


    /**
     * @param  Category  $category
     * @param  User  $user
     *
     * @return RedirectResponse
     */
    public function attachUploadPermission(Category $category, User $user): RedirectResponse
    {
        $success = Category::attachPermission($user, $category, User::PERMISSIONS['upload']);

        if ($success) {
            $this->banner('Upload permission was added.');
        } else {
            $this->banner('You already have the upload permission for this category.');
        }

        return redirect()->route('dashboard');
    }


    /**
     * @param  Category  $category
     * @param  User  $user
     *
     * @return RedirectResponse
     */
    public function detachUploadPermission(Category $category, User $user): RedirectResponse
    {
        $success = Category::detachPermission($user, $category, User::PERMISSIONS['upload']);

        if ($success) {
            $this->banner('Upload permission deleted.');
        } else {
            $this->banner('Upload permission does not exists.', 'danger');
        }
        return redirect()->route('dashboard');
    }


    /**
     * @param  Category  $category
     * @param  User  $user
     *
     * @return RedirectResponse
     */
    public function detachDownloadPermission(Category $category, User $user): RedirectResponse
    {
        $success = Category::detachPermission($user, $category, User::PERMISSIONS['download']);

        if ($success) {
            $this->banner('Download permission deleted.');
        } else {
            $this->banner('Download permission does not exists.', 'danger');
        }

        return redirect()->route('dashboard');
    }


    /**
     * Turn on/off upload permission for the root of the categories
     *
     * @param  User  $user
     *
     * @return RedirectResponse
     */
    public function toggleCategoryRootUploadPermission(User $user): RedirectResponse
    {
        $permissions = $user->permissions;

        if ($permissions) {
            // it can contain other permissions as well (for future usage), so make sure to have the
            // permission in the array
            if (!array_key_exists(User::PERMISSIONS['upload_root'], $permissions)) {

                $permissions[User::PERMISSIONS['upload_root']] = 1;
                $user->update([
                    'permissions' => $permissions
                ]);
            } else {
                // already there, so it needs to be removed
                // unset($permissions[User::PERMISSIONS['upload_root']]);
                $isRootUploadStatus = $permissions[User::PERMISSIONS['upload_root']];
                $permissions[User::PERMISSIONS['upload_root']] = $isRootUploadStatus === 0 ? 1 : 0;

                $user->update([
                    'permissions' => $permissions,
                ]);
                if ($isRootUploadStatus === 0) {
                    $this->banner('Upload permission to the root added.');
                } else {
                    $this->banner('Upload permission to the root deleted.');
                }

                return redirect()->route('dashboard');
            }
        } else {
            $permissions[User::PERMISSIONS['upload_root']] = 1;
            $user->update([
                'permissions' => $permissions
            ]);
        }

        $this->banner('Upload permission to the root added.');
        return redirect()->route('dashboard');
    }

}
