<?php

namespace App\Http\Controllers\Admin;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\Staff\UserListResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$sortableColumns = ['name', 'email', 'role', 'created_at'];

		$search = strtolower(request('search'));
		$role = strtolower(request('role'));
		$sortColumn = strtolower(request('column', 'role'));
		$sortOrder = strtolower(request('order', 'DESC'));

		if (!in_array($sortColumn, $sortableColumns)) {
			$sortColumn = 'role';
		}

		if (!in_array($sortOrder, ['asc', 'desc'])) {
			$sortOrder = 'desc';
		}

		$q = User::query()
			->select(['slug', 'name', 'email', 'email_verified_at', 'role', 'created_at'])
			->when($search, fn ($q) => $q->whereLike('name', "%$search%")->orWhereLike('email', "%$search%"))
			->when($role, fn ($q) => $q->where('role', $role));

		$q = match ($sortColumn) {
			'role' => $q->orderInRoleHierarchy(order: $sortOrder),
			default => $q->orderBy($sortColumn, $sortOrder)
		};

		return Inertia::render('staff/User', [
			'paginatedUsers' => Inertia::scroll(
				UserListResource::collection(
					$q->paginate(15)
				)
			),
			'user_roles' => array_column(UserRole::cases(), 'value'),
			'filter' => [
				'search' => $search,
				'role' => $role,
				'activeColumn' => $sortColumn,
				'order' => $sortOrder
			],
			'sortable_columns' => $sortableColumns
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateUserRoleRequest $request, User $user)
	{
		$action = $request->validated()['action'];

		if ($action === 'promote') {
			Gate::authorize('promote', $user);

			$user->update(['role' => UserRole::MOD]);
		}

		if ($action === 'demote') {
			Gate::authorize('demote', $user);

			$user->update(['role' => UserRole::USER]);
		}

		return back(status: 303);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(User $user)
	{
		Gate::authorize('delete', $user);

		DB::transaction(function () use ($user) {
			$user->wonGiveaways()->update(['user_id' => null]);

			$user->delete();
		});

		return back(status: 303);
	}
}
