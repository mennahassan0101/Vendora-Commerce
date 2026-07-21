<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        Review::create($request->validated());

        return back()->with('review_success', 'Thanks! Your review has been submitted and will appear once approved.');
    }
}