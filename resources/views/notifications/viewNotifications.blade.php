<x-masterLayout.master>
    @section("title")
        {{ __("global.notifications") }}
    @endsection

    @section("style")
        <style>
            .light-anchor {
                background-color: #b7c7f2;
                transition: background-color 1s ease-in-out;
            }

            .not-light-anchor {
                background-color: inherit;
                transition: background-color 1s ease-in-out;
            }
            html{
                scroll-padding: 100px;
            }
        </style>
    @endsection
    @section('content')

        <div class="container">
            @foreach($notifications as $notification)
                <div id="{{$notification->name}}" style="white-space: unset;" class="dropdown-item d-flex justify-content-between">
                    <div>
                    @if ($notification->type == "product_quantity_is_not_enough")
                        <i class="fa fa-bell mr-2"></i>
                    @elseif ($notification->type == "new_messages")
                        <i class="fas fa-envelope mr-2"></i>
                    @elseif ($notification->type == "friend_requests")
                        <i class="fas fa-users mr-2"></i>
                    @elseif ($notification->type == "new_reports")
                        <i class="fas fa-file mr-2"></i>
                    @endif

                    {{__("global.product_quantity_running_out",["attribute"=>$notification->name])}}</div>
                    <span class="text-blue text-muted text-sm">{{$notification->created_at->diffForHumans()}}</span>
                </div>
                <div class="dropdown-divider"></div>
            @endforeach

            <div style="color: #77b2d4"></div>
        </div>

    @endsection
    @section("modals")
    @endsection
    @section("script")
        <script>
            setTimeout(function (){
                if (location.href.indexOf("#")>-1){
                    let id = location.href.split("#")[1];
                    // $("#"+id).addClass("light-anchor");
                    $("#"+id).css("background-color", "#b7c7f2");
                    $("#"+id).css("transition", "background-color 1s ease-in-out");


                    setTimeout(function (){
                        // $("#"+id).addClass("not-light-anchor").removeClass("light-anchor");
                        $("#"+id).css("background-color", "transparent");
                        $("#"+id).css("transition", "background-color 1s ease-in-out");

                    },1500);
                }
            },200);
        </script>
    @endsection
</x-masterLayout.master>








