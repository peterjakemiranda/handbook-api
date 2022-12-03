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
            $response = $client->request('POST', 'https://app.mysms.ph/api/sms', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Dc-Client-Id' => '97e37d5a-fe51-4774-85a5-5bc67063e6ed',
                    'Dc-Client-Secret' => 'UoP6y1mU1CYXUkgKNXJsKEApriiT2nzUtV5PbDQk',
                ],
                'form_params' => [
                    'destination' => $request->get('mobile'),
                    'text' => $request->get('message'),
                ]
            ]);
        } catch (\Exception $e) {
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
