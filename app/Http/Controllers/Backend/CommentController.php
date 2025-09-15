<?php

namespace App\Http\Controllers\Backend;

use App\Enums\CommentType;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:comments-settings', ['only' => ['index']]);
         $this->middleware('permission:comments-create-settings', ['only' => ['store']]);
         $this->middleware('permission:comments-edit-settings', ['only' => ['update']]);
         $this->middleware('permission:comments-delete-settings', ['only' => ['destroy']]);
    }

    public function index()
    {
        $comments = Comment::latest()->paginate(15);

        return view('backend.page.comments.index', compact('comments'));
    }

    public function create()
    {
        $types = CommentType::cases();
        return view('backend.page.comments.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . collect(CommentType::cases())->pluck('value')->implode(',')],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean']
        ]);

        $validated['status'] = (bool) ($request->input('status', 1));

        Comment::create($validated);

        notify()->success(__('Comment created successfully'));
        return redirect()->route('admin.page.comments.index');
    }

    public function edit(Comment $comment)
    {
        $types = CommentType::cases();
        return view('backend.page.comments.edit', compact('comment', 'types'));
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . collect(CommentType::cases())->pluck('value')->implode(',')],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean']
        ]);

        $validated['status'] = (bool) ($request->input('status', $comment->status));
        $comment->update($validated);

        notify()->success(__('Comment updated successfully'));
        return redirect()->route('admin.page.comments.index');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        notify()->success(__('Comment deleted successfully'));
        return redirect()->route('admin.page.comments.index');
    }
}


