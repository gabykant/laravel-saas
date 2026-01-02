<?php
namespace App\Actions\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterTenantAction
{
    public function execute(RegisterRequest $request): array
    {
        return DB::transaction(function () use ($request) {

            $tenant = Tenant::create([
                'name' => $request->company_name,
                'slug' => Str::slug($request->company_name) . '-' . uniqid()
            ]);

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
                'tenant_id' => $tenant->id,
                'role'      => 'owner',
            ]);

            $tenant->update(['owner_id' => $user->id]);

            $token = $user->createToken('api-token')->plainTextToken;

            return compact('tenant', 'user', 'token');
        });
    }
}
