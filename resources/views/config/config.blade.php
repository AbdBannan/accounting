<x-masterLayout.master>
    @section("title")
        {{ __("global.user_config",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="global-config-tab" data-toggle="pill" href="#global-config" role="tab" aria-controls="global-config" aria-selected="false">{{__("global.global",[],session("lang"))}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="app-look-config-tab" data-toggle="pill" href="#app-look-config" role="tab" aria-controls="app-look-config" aria-selected="true">{{__("global.look",[],session("lang"))}}</a>
                        </li>
                    </ul>
                </div>
                <div class="bg-light card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade active show" id="global-config" role="tabpanel" aria-labelledby="global-config-tab">
                            <x-configTabs.global-tab :config="$config"></x-configTabs.global-tab>
                        </div>
                        <div class="tab-pane fade" id="app-look-config" role="tabpanel" aria-labelledby="app-look-config-tab">
                            <x-configTabs.look-tab :config="$config"></x-configTabs.look-tab>
                        </div>
{{--                            <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">--}}
{{--                                Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.--}}
{{--                            </div>--}}
{{--                            <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">--}}
{{--                                Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.--}}
{{--                            </div>--}}
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section("modals")
    @endsection
    @section("script")


    @endsection

</x-masterLayout.master>
