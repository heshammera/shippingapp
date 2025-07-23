<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;



class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * تخزين مستخدم جديد في قاعدة البيانات
     */
     
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|string|in:admin,moderator,viewer,delivery_agent,accountant',
        'phone' => 'nullable|string',
        'address' => 'nullable|string',
        'is_active' => 'nullable|boolean',
        'expires_days' => 'nullable|integer|min:1',
        'expires_lifetime' => 'nullable',
    ]);

    // ✅ هنا نحدد قيمة expires_at بناءً على checkbox أو days
    $expiresAt = null;
    if ($request->has('expires_lifetime')) {
        $expiresAt = now()->addYears(100); // مدى الحياة = 100 سنة
    } elseif (!empty($validated['expires_days'])) {
        $expiresAt = now()->addDays($validated['expires_days']);
    }

    DB::beginTransaction();

    try {
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'],
            'role_id' => Role::where('name', $validated['role'])->value('id'),
            'is_active' => $request->has('is_active'),
            'expires_at' => $expiresAt, // ← هذا السطر هو الذي يحفظ القيمة
        ]);

        DB::commit();
        return redirect()->route('users.index')->with('success', '✅ تم إضافة المستخدم بنجاح');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', '❌ فشل الإضافة: ' . $e->getMessage())->withInput();
    }
}

// public function store(Request $request)
//{
//    $validated = $request->validate([
//        'name' => 'required|string|max:255',
//        'email' => 'required|email|unique:users',
//        'password' => 'required|string|min:6|confirmed',
//        'role' => 'required|string|in:admin,moderator,viewer,delivery_agent',
//        'phone' => 'nullable|string',
//        'address' => 'nullable|string',
//        'is_active' => 'nullable|boolean',
//        'expires_days' => 'nullable|integer|min:1',
//        'expires_lifetime' => 'nullable',
//    ]);
//
//    $expiresAt = null;
//    if (!$request->has('expires_lifetime') && !empty($validated['expires_days'])) {
//        $expiresAt = now()->addDays($validated['expires_days']);
//    }
//
//    DB::beginTransaction();
//
//    try {
//        $user = User::create([
//            'name' => $validated['name'],
//            'email' => $validated['email'], // ✅ تصحيح هنا
//            'password' => Hash::make($validated['password']), // ✅ تصحيح هنا
//            'phone' => $validated['phone'] ?? null,
//            'address' => $validated['address'] ?? null,
//            'role' => $validated['role'], // ✅ تخزين الدور
//            'role_id' => Role::where('name', $request->role)->value('id'), // ← ID الخاص بالدور
//            'is_active' => $request->has('is_active'),
//            'expires_at' => $expiresAt, // ← استخدم المتغير المحسوب هنا مش ثابت
//        ]);
//
//        DB::commit();
//        return redirect()->route('users.index')->with('success', '✅ تم إضافة المستخدم بنجاح');
//    } catch (\Exception $e) {
//        DB::rollBack();
//        return redirect()->back()->with('error', '❌ فشل الإضافة: ' . $e->getMessage())->withInput();
//    }
//}


    /**
     * عرض تفاصيل مستخدم محدد
     */
public function show(User $user)
{
    $expires_days = null;

    if ($user->expires_at && $user->expires_at->gt(now())) {
        $expires_days = now()->diffInDays($user->expires_at);
    }

    return view('users.show', compact('user', 'expires_days'));
}

    /**
     * عرض نموذج تعديل مستخدم محدد
     */
  public function edit(User $user)
{
    $roles = Role::all();

    $expires_days = null;

    if ($user->expires_at && $user->expires_at->gt(now())) {
        $expires_days = now()->diffInDays($user->expires_at);
    }

    return view('users.edit', compact('user', 'roles', 'expires_days'));
}



    /**
     * تحديث بيانات مستخدم محدد في قاعدة البيانات
     */
  public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'nullable|confirmed|min:6',
        'role' => 'required|in:admin,moderator,delivery_agent,viewer,accountant',
        'phone' => 'nullable|string',
        'address' => 'nullable|string',
        'is_active' => 'boolean',
    ]);
$validated = $request->validate([
    'name' => 'required|string',
    'email' => 'required|email',
    'password' => 'nullable|confirmed|min:6',
    'role' => 'required|in:admin,moderator,delivery_agent,viewer,accountant',
    'phone' => 'nullable|string',
    'address' => 'nullable|string',
    // فقط validate لو كانت موجودة، لكن بعدين بنضبطها يدوي
    'is_active' => 'sometimes|boolean',
]);

$validated['is_active'] = $request->has('is_active');

if ($request->filled('password')) {
    $validated['password'] = bcrypt($request->password);
} else {
    unset($validated['password']);
}

$expiresAt = null;
if (!$request->has('expires_lifetime') && !empty($request->expires_days)) {
    $expiresAt = now()->addDays($request->expires_days);
}

$user->update($validated);

// تحديث الدور
\DB::table('model_has_roles')
    ->where('model_type', \App\Models\User::class)
    ->where('model_id', $user->id)
    ->delete();

$roleId = Role::where('name', $validated['role'])->value('id');

\DB::table('model_has_roles')->insert([
    'role_id' => $roleId,
    'model_type' => \App\Models\User::class,
    'model_id' => $user->id,
]);

$user->update([
    'role' => $validated['role'],
    'role_id' => $roleId,
    'expires_at' => $expiresAt,
]);

    if ($request->filled('password')) {
        $validated['password'] = bcrypt($request->password);
    } else {
        unset($validated['password']);
    }
$expiresAt = null;
if (!$request->has('expires_lifetime') && !empty($request->expires_days)) {
    $expiresAt = now()->addDays($request->expires_days);
}

    $user->update($validated);

    // تحديث الدور في جدول model_has_roles
    \DB::table('model_has_roles')
        ->where('model_type', \App\Models\User::class)
        ->where('model_id', $user->id)
        ->delete();

$roleId = Role::where('name', $validated['role'])->value('id');

\DB::table('model_has_roles')->insert([
    'role_id' => $roleId,
    'model_type' => \App\Models\User::class,
    'model_id' => $user->id,
]);

$user->update([
    'role' => $validated['role'],
    'role_id' => $roleId,
    'expires_at' => $expiresAt

]);



    return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستخدم.');
}



    /**
     * حذف مستخدم محدد من قاعدة البيانات
     */
    public function destroy(User $user)
    {
        // منع حذف المستخدم الحالي
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'لا يمكن حذف المستخدم الحالي');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }



     public function updateThemeColor(Request $request) {
        $user = auth()->user();
        $request->validate([
            'color' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/']
        ]);
        $user->theme_color = $request->color;
        $user->save();
        return response()->json(['message' => 'تم تحديث اللون بنجاح']);
    }
}
