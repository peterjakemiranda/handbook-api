<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use App\Traits\ApiResponser;

class SmsController extends Controller
{
    use ApiResponser;
    /**
     * send account details
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|string',
            'message' => 'required|string',
            'student_id' => 'required|integer',
        ]);
        try {
            $user = User::find($request->get('student_id'));
            $user->parent_mobile = $request->get('mobile');
            $user->save();
            $client = new \GuzzleHttp\Client();
            $response = $client->request("POST", "https://api.sms.fortres.net/v1/messages", [
                "headers" => [
                    "Content-type" => "application/json"
                ],
                "auth" => ["48b65cb9-e518-4f2b-951c-b976214cb694", "ON5CGojnspUHVCpXvUcZ7xr9NloY6VxOlrqV4HQm"],
                "json" => [
                    "recipient" => $request->get('mobile'),
                    "message" => $request->get('message')
                ]
            ]);
        } catch (\Exception $e) {
            info('exception', compact('e'));
            return response()->json(['message' => 'Failed Sending SMS'], 400);
        }
        return response()->json(['message' =>  'Successfully sent sms'], 200);
    }

    public function updateParentMobile(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|string',
            'student_id' => 'required|integer',
        ]);

        try {
            $user = User::find($request->get('student_id'));
            $user->parent_mobile = $request->get('mobile');
            $user->save();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed updating parent mobile number'], 400);
        }
        return response()->json(['message' =>  'Successfully updated parent mobile number'], 200);
    }
}
