<?php

namespace App\Http\Controllers\FeedBack;

use App\Http\Requests\FeedBack\FeedBackCommentRequest;
use App\Http\Requests\FeedBack\FeedBackRequest;
use App\Services\FeedBack\FeedbackService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper;

class FeedBackController extends Controller
{
    protected FeedbackService $feedBackService;

    /**
     * @param FeedbackService $feedBackService
     */
    public function __construct(FeedBackService $feedBackService)
    {
        $this->feedBackService = $feedBackService;
    }

    /**
     * save submitted feedback
     * @param FeedBackRequest $request
     * @return JsonResponse
     */
    public function addFeedback(FeedBackRequest $request): JsonResponse
    {
        $addFeedBack = $this->feedBackService->addFeedback($request);
        return Helper::response($request, $addFeedBack['body'], $addFeedBack['header_code'], optional(auth()->user())->name . ' has submitted feedback named: ' . $request['category']);
    }

    /**
     * show all submitted feedbacks
     * @param Request $request
     * @return JsonResponse
     */
    public function getFeedbacks(Request $request): JsonResponse
    {
        $addFeedBack = $this->feedBackService->getFeedbacks($request);
        return Helper::response($request, $addFeedBack['body'], $addFeedBack['header_code']);
    }

    /**
     * show all submitted feedbacks
     * @param Request $request
     * @return JsonResponse
     */
    public function viewFeedbacks(Request $request): JsonResponse
    {
        $addFeedBack = $this->feedBackService->viewFeedbacks($request);
        return Helper::response($request, $addFeedBack['body'], $addFeedBack['header_code']);
    }

    /**
     * add comments to feedback
     * @param FeedBackCommentRequest $request
     * @return JsonResponse
     */
    public function addComments(FeedBackCommentRequest $request): JsonResponse
    {
        {
            $addComment = $this->feedBackService->addComments($request);
            return Helper::response($request, $addComment['body'], $addComment['header_code']);
        }
    }
}
