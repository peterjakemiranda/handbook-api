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
            $response = $client->request('GET', 'https://sms.pagenet.info/admin/index.php', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'route' => 'api/sms/send',
                    'auth_key' => 'p6rV1tCjldQ05HCiuO8Zh5ZXtMSv44tIOG7bvHgC',
                    'device_id' => 18,
                    'sim_id' => 3,
                    'mobile_no' => $request->get('mobile'),
                    'data_type' => 'Plain',
                    'message' => $request->get('message'),
                ]
            ]);
            info('sms response', ['data' => $response]);
            // https://sms.pagenet.info/admin/index.php?route=api/sms/send&auth_key=p6rV1tCjldQ05HCiuO8Zh5ZXtMSv44tIOG7bvHgC&device_id=18&sim_id=3&mobile_no=09665761200&data_type=Plain&message=Hello+World+test
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
