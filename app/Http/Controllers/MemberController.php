<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\SuccessResource;
use App\Services\IMemberService;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(IMemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index()
    {
        $response = $this->memberService->getAll();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request): SuccessResource|array|null
    {
        $response = $this->memberService->store($request);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $response = $this->memberService->getById($id);

        return $response;
    }

    public function searchByUserName($username)
    {
        $response = $this->memberService->getByUsername($username);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, $id)
    {
        $response = $this->memberService->update($request, $id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->memberService->delete($id);

        return $response;
    }
    public function member_children($id)
    {
        $response = $this->memberService->getMemberChildren($id);

        return $response;
    }
    public function member_earning($id)
    {
        $response = $this->memberService->getMemberEarning($id);

        return $response;
    }
    public function member_payment($id)
    {
        $response = $this->memberService->getMemberPayment($id);

        return $response;
    }
}
