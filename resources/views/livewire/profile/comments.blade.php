<div class="w-full flex flex-col">
    <div class="text-slate-700 text-lg flex items-center gap-2">
        Your comments <div wire:loading><i class="fa fa-spinner fa-spin"></i></div>
    </div>
    <div class="w-full flex flex-col">
        @if($comments->count())
            @foreach($comments as $comment)
                @php
                    $key = 'd';
                    if ($comment->source_type == \App\Models\Discussion::class) {
                        $discussion = $comment->source;
                    } elseif ($comment->source_type == \App\Models\Reply::class) {
                        $discussion = $comment->source->discussion;
                        $key = 'r';
                    }
                @endphp
                <a href="{{ route('discussion', ['discussion' => $discussion, 'slug' => Str::slug($discussion->name), 'c' => $comment->id, $key => $comment->source_id]) }}"
                   class="w-full flex lg:flex-row flex-col lg:gap-0 gap-3 items-start justify-between hover:bg-slate-100 hover:cursor-pointer px-3 hover:rounded transition-all border-slate-200 py-5 {{ $loop->last ? '' : 'border-b' }}">
                    <div class="flex gap-3">
                        <a href="{{ route('user', ['user' => $discussion->user, 'slug' => Str::slug($discussion->user->name)]) }}">
                            <img src="{{ $discussion->user->avatarUrl }}" alt="Avatar"
                                 class="rounded-full w-10 h-10"/>
                        </a>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1">
                                <span class="font-medium text-slate-500">
                                    <span class="font-medium text-slate-400">You added a comment to</span>
                                    @if($discussion->is_resolved)
                                        <span class="font-normal">[Resolved]</span>
                                    @endif
                                    {{ $discussion->name }}
                                </span>
                            </div>
                            <span class="text-slate-400 text-sm">
                                Commented {{ $comment->created_at->diffForHumans() }}
                            </span>
                            <span class="text-slate-400 font-light lg:max-w-[90%] max-w-full text-sm">
                                {{ Str::limit(strip_tags($comment->content), 300) }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <span class="py-3 text-slate-700 font-medium text-sm">
                You haven't commented any discussions or replies yet.
            </span>
        @endif
    </div>
    @if(!$disableLoadMore)
        <div class="w-full flex justify-center items-center text-center mt-5">
            <button type="button" wire:click="loadMore" wire:loading.attr="disabled"
                    class="bg-slate-100 disabled:bg-slate-50 px-3 py-2 text-slate-500 border-slate-100 rounded hover:cursor-pointer w-fit hover:bg-slate-200">
                Load more
            </button>
        </div>
    @endif
</div>
