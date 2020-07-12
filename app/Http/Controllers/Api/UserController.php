<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class UserController extends Controller
{

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * CaseController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request) {
        $apiRes = new ApiResponse('Users');
        $userList = $this->userService->getAll();
        $apiRes->results = $userList;
        return response()->json($apiRes);
    }

    public function show($userId) {
        /**
         * @var User $user
         */
        $user = $this->userService->getById($userId);
        $apiRes = new ApiResponse('User');

        if (is_null($user)) {
            return response()->json(null, 404);
        }
        $apiRes->results[] = $user->toArray('full');
        return response()->json($apiRes);
    }

    public function store(Request $request) {
        $user = $this->userService->create($request->input());
        if ($this->userService->errors->count() > 0) {
            return response()->json([
                'errors' => $this->userService->errors
            ], 422);
        }

        return response()->json([
            'items' => [$user]
        ]);
    }

    public function update(Request $request, $userId) {
        $user = $this->userService->getById($userId);
        if (is_null($user)) {
            return response()->json(null, 404);
        }

        $user = $this->userService->update($user, $request->input());
        if ($this->userService->errors->count() > 0) {
            return response()->json([
                'errors' => $this->userService->errors
            ], 422);
        }

        return response()->json([
            'items' => [$user]
        ]);
    }

    public function destroy($userId) {
        $user = $this->userService->getById($userId);
        if (is_null($user)) {
            return response()->json(null, 404);
        }

        $this->userService->delete($user);
        return response()->json(null);
    }

    public function changePassword(Request $request) {
        $user = \Auth::user();

        $this->userService->changePassword($user, $request->input());
        if ($this->userService->errors->count() > 0) {
            return response()->json([
                'errors' => $this->userService->errors
            ], 422);
        }

        return response()->json(null);
    }
}