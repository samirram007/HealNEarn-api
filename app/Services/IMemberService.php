<?php

namespace App\Services;

use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;

interface IMemberService
{
    public function getAll();

    public function getById($id);
    public function getMemberChildren($id);
    public function getMemberEarning($id);
    public function getMemberPayment($id);

    public function getByUsername($username);

    public function store(StoreMemberRequest $request);

    public function update(UpdateMemberRequest $request, $id);

    public function delete($id);

    public function profile();
}
