<?php

namespace App\Repository;

use App\Models\Member;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberRepository
{
    /**
     * @throws \Exception
     */
    public function findById($id): Member
    {
        $member = Member::find($id);
        if (!$member) {
            throw new \Exception('Member not found.');
        }

        return $member;
    }

    public function getWithPagination($page, $limit = 3): LengthAwarePaginator
    {
        $members = Member::query()->paginate($limit, ['*'], 'page', $page);
        return  $members;
    }

    public function emailExists(mixed $email): bool
    {
        return Member::where('email', $email)->exists();
    }

    public function create(array $data): Member
    {
        $member = Member::create($data);

        if (key_exists('tags', $data)) {
            $member->tags()->attach($data['tags']);
        }

        return $member;
    }

    public function update(int $id, array $data)
    {
        $member = $this->findById($id);

        if (isset($data['email'])) {
            if ($data['email'] === $member->email) {
                unset($data['email']);
            } elseif ($this->emailExists($data['email'])) {
                throw new \Exception('Email is already taken.');
            }
        }

        $member->update($data);

        if (key_exists('tags', $data)) {
            if (empty($data['tags'])) {
                $member->tags()->detach();
            } else {
                $member->tags()->sync($data['tags']);
            }
        }

        return $member;
    }
}
