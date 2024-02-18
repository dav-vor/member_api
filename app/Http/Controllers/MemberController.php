<?php

namespace App\Http\Controllers;

use App\OpenApi\Parameters\IncludeTagsParameter;
use App\OpenApi\Parameters\ShowMemberParameter;
use App\OpenApi\Parameters\ShowMembersParameter;
use App\OpenApi\RequestBodies\StoreMemberRequestBody;
use App\Repository\MemberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class MemberController extends Controller
{
    protected MemberRepository $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * Display member.
     */
    #[OpenApi\Operation(tags: ['Members'])]
    #[OpenApi\Parameters(ShowMemberParameter::class)]

    public function show(Request $request, $id)
    {
        try {
            $tag = $request->input('member_tags');
            $id = (int)$id;

            $member = $this->memberRepository->findById($id);
            $data = $member->toArray();

            if (!empty($tag) && $tag === '1') {
                $data['member_tags'] = $member->memberTags()->pluck('name')->toArray();
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'An error occurred while attempting to display member.',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Display members.
     *
     * Display members, with the option to include pagination if the parameter "page" is specified.
     */
    #[OpenApi\Operation]
    #[OpenApi\Parameters(ShowMembersParameter::class)]
    public function index(Request $request)
    {
        try {
            $page = $request->input('page');
            $members = $this->memberRepository->getWithPagination($page);
            return response()->json($members->items());
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'An error occurred while attempting load members. ',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Create new member.
     *
     * Create new member, first_name, last_name, unique email and birth_date are required.
     */
    #[OpenApi\Operation]
    #[OpenApi\RequestBody(StoreMemberRequestBody::class)]
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:members',
                'birth_date' => 'required|date',
                'member_tags.*' => 'integer'
            ]);

            $member = $this->memberRepository->create($validatedData);

            return response()->json($member, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'An error occurred while attempting to create member.',
                    'error' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'An error occurred while attempting to create member.',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Update member.
     *
     * Update member, change first_name, last_name, unique email and birth_date.
     */
    #[OpenApi\Operation]
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $id = (int)$id;

            $validatedData = $request->validate([
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'email' => 'email|max:255',
                'birth_date' => 'date',
                'member_tags' => 'nullable|array',
                'member_tags.*' => 'integer'
            ]);

            $member = $this->memberRepository->update($id, $validatedData);

            return response()->json($member);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'An error occurred while attempting to update member.',
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                    'message' => 'An error occurred while attempting to update member.',
                    'error' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete member.
     *
     * Delete member.
     */
    #[OpenApi\Operation]
    public function destroy($id): JsonResponse
    {
        try {
            $id = (int)$id;
            $member = $this->memberRepository->findById($id);
            $member->delete();

            return response()->json(['message' => 'Member deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'An error occurred while attempting to delete member.',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
