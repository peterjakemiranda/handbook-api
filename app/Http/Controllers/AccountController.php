<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');
        if ($request->chapter_id) {
            $query->whereHas('answers', function($q) use($request) {
                $q->where('chapter_id', $request->chapter_id);
            });
        }
        $this->setPagination($request->limit);
        $pagination = $query->paginate($this->getPagination());

        return $this->respondWithPagination($pagination, $pagination->items());
    }

    public function byProgram(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->whereHas('answers')
            ->select('program_description',DB::raw('count(*) as total'))
            ->groupBy('program_description');

        return response()->json($query->get());

    }

    public function admins(Request $request)
    {
        $admins = User::where('role', 'admin')->get();
        return response()->json($admins);
    }

    /**
     * Display the current user account.
     *
     * @return JsonResponse
     */
    public function show(Request $request, $id = null): JsonResponse
    {
        return response()->json($id ? User::find($id) : auth()->user());
    }

    /**
     * Update account details
     *
     * @return Response
     */
    public function update(Request $request, $id = null)
    {
        $this->validate($request, [
            'student_id' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'nullable|string|min:4',
        ]);
        try {
            $user = $id ? User::find($id) : new User();
            $user->student_id = $request->input('student_id');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->role = 'admin';
            if (!$user->email) {
                $user->email = trim($request->input('student_id')).'@admin.com';
            }
            if($plainPassword = $request->input('password')){
                $user->password = app('hash')->make($plainPassword);
            }
            $user->save();
        } catch (\Exception $e) {
            return response()->json(['message' => $id ? 'Account Update Failed!' : 'Account Create Failed!'], 400);
        }
        return response()->json(['message' =>  $id ? 'Account Updated' : 'Account Created'], 200);
    }
}
