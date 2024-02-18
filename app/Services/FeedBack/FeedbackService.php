<?php

namespace App\Services\FeedBack;

use App\Filters\FeedBack\FeedBackNameFilter;
use App\Models\FeedBackComment;
use Illuminate\Support\Facades\DB;
use Illuminate\Pipeline\Pipeline;
use App\Models\FeedBack;
use App\Models\User;
use App\Helper;
use Exception;

class FeedbackService
{
    /**
     * @param $request
     * @return array
     */
    public static function addFeedback($request): array
    {
        try {
            DB::beginTransaction();
            FeedBack::create([
                'user_id' => auth()->user()->id,
                'request_id' => $request['request_id'],
                'title' => $request['title'],
                'category' => $request['category'],
                'description' => $request['description'],
            ]);
            DB::commit();
            return [
                'header_code' => 200,
                'body' => 'FeedBack added successfully',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }

    /**
     * show all feedbacks
     * @param $request
     * @return array
     */
    public static function getFeedbacks($request): array
    {
        try {
            $feedbacks = app(Pipeline::class)
                ->send(FeedBack::query())
                ->through([
                    FeedBackNameFilter::class,
                ])
                ->thenReturn()
                ->with('comments:id,feed_back_id,content,created_at')
                ->latest()
                ->paginate($request->per_page);
            $data = [];
            foreach ($feedbacks as $feedBack) {
                $data[] = [
                    'id' => $feedBack->id,
                    'user_name' => User::find($feedBack->user_id)->name,
                    'title' => $feedBack->title,
                    'category' => $feedBack->category,
                    'description' => $feedBack->description,
                    'created_at' => $feedBack->created_at,
                    'comments' => $feedBack->comments,
                ];
            }

            $feedBackDetail['data'] = $data;
            $feedBackDetail['current_page'] = $feedbacks->currentPage();
            $feedBackDetail['last_page'] = $feedbacks->lastPage();
            $feedBackDetail['from'] = $feedbacks->firstItem();
            $feedBackDetail['to'] = $feedbacks->lastItem();
            $feedBackDetail['total'] = $feedbacks->total();
            $feedBackDetail['per_page'] = $feedbacks->perPage();
            $feedBackDetail['last_page'] = $feedbacks->lastPage();
            $feedBackDetail['next_page_url'] = $feedbacks->nextPageUrl();
            $feedBackDetail['prev_page_url'] = $feedbacks->previousPageUrl();
            $feedBackDetail['path'] = $feedbacks->path();
            return [
                'header_code' => 200,
                'body' => $feedBackDetail
            ];
        } catch (Exception $e) {
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }


    /**
     * @param $request
     * @return array
     */
    public static function viewFeedbacks($request): array
    {
        try {
            $feedback = FeedBack::where('id', $request['id'])->with('comments:id,feed_back_id,content,created_at,user_id', 'user:name,id' , 'comments.user')->latest()->first();
            $response = [
                'title' => $feedback->title,
                'description' => $feedback->description,
                'category' => $feedback->category,
                'name' => $feedback->user->name,
                'comments' => $feedback->comments,
                'feed_back_id' => $feedback->id,
                'user_name' => $feedback->comments,


            ];
            return [
                'header_code' => 200,
                'body' => $response
            ];
        } catch (Exception $e) {
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }

    /**
     * @param $request
     * @return array
     */
    public static function addComments($request): array
    {
        try {
            DB::beginTransaction();
            FeedBackComment::create([
                'user_id' => auth()->user()->id,
                'request_id' => $request['request_id'],
                'feed_back_id' => $request['feed_back_id'],
                'content' => $request['content'],
            ]);
            DB::commit();
            return [
                'header_code' => 200,
                'body' => 'Comment added successfully',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }
}
