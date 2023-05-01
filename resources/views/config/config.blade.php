<x-masterLayout.master>
    @section("title")
        {{ __("global.user_config") }}
    @endsection
    @section('content')
        <div class="container">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="global-config-tab" data-toggle="pill" href="#global-config" role="tab" aria-controls="global-config" aria-selected="false">{{__("global.global")}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="app-look-config-tab" data-toggle="pill" href="#app-look-config" role="tab" aria-controls="app-look-config" aria-selected="true">{{__("global.look")}}</a>
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
