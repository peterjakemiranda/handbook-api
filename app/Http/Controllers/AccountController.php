<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use App\Traits\ApiResponser;

class AccountController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $query = User::where('student_id', '!=', 'admin');
        $this->setPagination($request->limit);
        $pagination = $query->paginate($this->getPagination());

        return $this->respondWithPagination($pagination, $pagination->items());
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
            'password' => 'nullable|confirmed|string|min:4',
        ]);
        try {
            $user = $id ? User::find($id) : new User();
            $user->student_id = $request->input('student_id');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            if (!$user->email) {
                $user->email = trim($request->input('student_id')).'@handbook.user';
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
