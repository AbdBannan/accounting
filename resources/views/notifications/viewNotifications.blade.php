<x-masterLayout.master>
    @section("title")
        {{ __("global.notifications") }}
    @endsection

    @section('content')

        <div class="container">
            @foreach($notifications as $notification)
                <a href="#" class="dropdown-item">
                    @if ($notification->type == "product_quantity_is_not_enough")
                        <i class="fas fa-bread-slice mr-2"></i>
                    @elseif ($notification->type == "new_messages")
                        <i class="fas fa-envelope mr-2"></i>
                    @elseif ($notification->type == "friend_requests")
                        <i class="fas fa-users mr-2"></i>
                    @elseif ($notification->type == "new_reports")
                        <i class="fas fa-file mr-2"></i>
                    @endif

                    <span>{{$notification->name}}</span>
                    <span class="float-end text-muted text-sm">{{$notification->created_at->diffForHumans()}}</span>
                </a>
                <div class="dropdown-divider"></div>
            @endforeach

        </div>

    @endsection
    @section("modals")
    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>








