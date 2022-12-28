<div class="flex flex-row gap-5 w-full border-b border-slate-200 pb-5 mb-5" id="discussion">
    <a href="{{ route('user', ['user' => $discussion->user, 'slug' => Str::slug($discussion->user->name)]) }}">
        <img src="{{ $discussion->user->avatarUrl }}" alt="Avatar" class="rounded-full w-16 h-16 border border-slate-200 shadow" />
    </a>
    <div class="w-full flex flex-col">
        <div class="w-full flex items-center justify-between gap-2">
            <div class="flex flex-col">
                <span class="text-slate-700 font-medium">{{ $discussion->user->name }}</span>
                <span class="text-slate-500 text-sm mt-1">{{ $discussion->created_at->diffForHumans() }}</span>
            </div>
            <livewire:discussion.mark-as-resolved :discussion="$discussion" />
        </div>
        @if($edit)
            <livewire:layout.dialogs.discussion :discussion="$discussion" />
        @else
            <div class="w-full flex flex-col gap-5 hovered-section">
                <div class="w-full max-w-full prose mt-3 lg:pr-5 pr-0">
                    {!! $discussion->content !!}
                </div>
                <div class="w-full flex items-center gap-5 text-slate-500 text-xs">
                    @if(
                            (auth()->user() && auth()->user()->hasVerifiedEmail()) &&
                            (
                                auth()->user()->id === $discussion->user_id
                                || auth()->user()->can(Permissions::LIKE_POSTS->value)
                            )
                        )
                        <button type="button" class="flex items-center gap-2 hover:cursor-pointer" wire:click="toggleLike()">
                            <i class="fa-regular fa-thumbs-up"></i> {{ $likes }} {{ $likes > 1 ? 'Likes' : 'Like' }}
                        </button>
                    @else
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-thumbs-up"></i> {{ $likes }} {{ $likes > 1 ? 'Likes' : 'Like' }}
                        </div>
                    @endif
                    <button wire:click="toggleComments()" type="button" class="flex items-center gap-2 hover:cursor-pointer toggle-comments">
                        <i class="fa-regular fa-comment"></i> {{ $comments }} {{ $comments > 1 ? 'Comments' : 'Comment' }}
                    </button>
                    @if(
                            (auth()->user() && auth()->user()->hasVerifiedEmail()) &&
                            (
                                auth()->user()->id === $discussion->user_id
                                || auth()->user()->can(Permissions::EDIT_POSTS->value)
                            )
                        )
                        <button wire:click="editDiscussion()" type="button" class="flex items-center gap-2 hover:cursor-pointer text-blue-500 hover:underline hover-action">
                            Edit
                        </button>
                    @endif
                    @if(
                            (auth()->user() && auth()->user()->hasVerifiedEmail()) &&
                            (
                                auth()->user()->id === $discussion->user_id
                                || auth()->user()->can(Permissions::DELETE_POSTS->value)
                            )
                        )
                        <button wire:click="deleteDiscussion()" type="button" class="flex items-center gap-2 hover:cursor-pointer text-red-500 hover:underline hover-action">
                            Delete
                        </button>
                    @endif
                    <div wire:loading>
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
            @if($showComments)
                <div class="w-full flex flex-col gap-2 mt-5">
                    <span class="text-slate-700 text-lg">
                        {{ $comments }} {{ $comments > 1 ? 'Comments' : 'Comment' }}
                    </span>
                    <div class="w-full flex flex-col gap-0 bg-slate-50 border-y border-slate-100">
                        @if($discussion->comments->count())
                            @foreach($discussion->comments as $c)
                                <div class="w-full flex flex-col py-5 px-3 gap-2 {{ $loop->last ? '' : 'border-b border-slate-200' }} hovered-section" id="comment-{{ $c->id }}">
                                    <div class="w-full text-slate-700 text-sm">
                                        <a href="{{ route('user', ['user' => $c->user, 'slug' => Str::slug($c->user->name)]) }}" class="hover:underline font-medium">{{ $c->user->name }}</a> (<span class="text-xs">{{ $c->created_at->diffForHumans() }}</span>) - <span>{{ nl2br(e($c->content)) }}</span>
                                    </div>
                                    <div class="w-full flex items-center gap-5 text-slate-500 text-xs">
                                        @if(
                                            (auth()->user() && auth()->user()->hasVerifiedEmail()) &&
                                            (
                                                auth()->user()->id === $c->user_id
                                                || auth()->user()->can(Permissions::LIKE_POSTS->value)
                                            )
                                        )
                                            <button type="button" class="flex items-center gap-2 hover:cursor-pointer" wire:click="toggleCommentLike({{ $c->id }})">
                                                <i class="fa-regular fa-thumbs-up"></i> {{ $c->likes->count() }} {{ $c->likes->count() > 1 ? 'Likes' : 'Like' }}
                                            </button>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <i class="fa-regular fa-thumbs-up"></i> {{ $c->likes->count() }} {{ $c->likes->count() > 1 ? 'Likes' : 'Like' }}
                                            </div>
                                        @endif
                                        @if(
                                            (auth()->user() && auth()->user()->hasVerifiedEmail()) &&
                                            (
                                                auth()->user()->id === $c->user_id
                                                || auth()->user()->can(Permissions::EDIT_POSTS->value)
                                            ) && (
                                                !$comment
                                            )
                                        )
                                            <button wire:click="editComment({{ $c->id }})" type="button" class="flex items-center gap-2 hover:cursor-pointer text-blue-500 hover:underline hover-action">
                                                Edit
                                            </button>
                                        @endif
                                        @if(
                                            (auth()->user() && auth()->user()->hasVerifiedEmail()) &&
                                            (
                                                auth()->user()->id === $c->user_id
                                                || auth()->user()->can(Permissions::DELETE_POSTS->value)
                                            ) && (
                                                !$comment
                                            )
                                        )
                                            <button wire:click="deleteComment({{ $c->id }})" type="button" class="flex items-center gap-2 hover:cursor-pointer text-red-500 hover:underline hover-action">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <span class="text-slate-700 font-medium text-sm py-5 px-3">
                            No comments yet! Please come back later, or add a new comment.
                        </span>
                        @endif
                    </div>
                    @if($comment)
                        <form wire:submit.prevent="saveComment">
                            {{ $this->form }}
                            <!-- Modal footer -->
                            <div class="flex items-center space-x-2 rounded-b mt-2">
                                <button type="submit" wire:loading.attr="disabled" class="text-white bg-blue-700 disabled:bg-slate-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Save comment
                                </button>
                                <button type="button" wire:click="cancelComment()" wire:loading.attr="disabled" class="text-white bg-slate-700 disabled:bg-slate-300 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-slate-600 dark:hover:bg-slate-700 dark:focus:ring-slate-800">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @else
                        @if(
                            (auth()->user() && auth()->user()->hasVerifiedEmail()) &&
                            (
                                auth()->user()->id === $discussion->user_id
                                || auth()->user()->can(Permissions::COMMENT_POSTS->value)
                            )
                        )
                            <button wire:click="addComment()" type="button" class="bg-slate-100 px-3 py-2 text-slate-500 text-sm border-slate-100 rounded hover:cursor-pointer w-fit hover:bg-slate-200">
                                Add comment
                            </button>
                        @endif
                    @endif
                </div>
            @endif
        @endif
    </div>
</div>
