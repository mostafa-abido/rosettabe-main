<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PollResource;
use App\Models\Option;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PollController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return PollResource::collection(Poll::latest()->paginate(5));
    }

    /**
     * @param Poll $poll
     * @return PollResource
     */
    public function show(Poll $poll)
    {
        return new PollResource($poll);
    }

    /**
     * @param Option $option
     * @param Request $request
     * @return string
     */
    public function attachUserOption(Option $option, Request $request): string
    {
        $option->users()->attach($request->user()->id);

        return 'ok';
    }

    /**
     * @param Option $option
     * @param Request $request
     * @return string
     */
    public function detachUserOption(Option $option, Request $request): string
    {
        $option->dettach($request->user()->id);

        return 'ok';
    }
}
