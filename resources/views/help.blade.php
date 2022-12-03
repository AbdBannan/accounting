<x-masterLayout.master>
    @section("content")
        <div style="margin: 30px;">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">
                        {{__("global.short_cuts")}}
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="callout callout-danger info-box col-md-6 col-sm-12 m-2">

                            <span class="info-box-icon bg-danger">F1</span>

                            <div class="info-box-content">
                                <h3>{{__("global.view_all_accounts")}}</h3>
                                <span class="">{{__("global.press_f1_to_view_all_accounts")}}</span>
                            </div>
                        </div>
                        <div class="callout callout-info info-box col-md-5 col-sm-12 m-2">

                            <span class="info-box-icon bg-info">F2</span>

                            <div class="info-box-content">
                                <h3>{{__("global.close_invoice")}}</h3>
                                <span class="">{{__("global.press_f2_to_save_the_invoice")}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="callout callout-warning info-box col-md-6 col-sm-12 m-2">

                            <span class="info-box-icon bg-warning">F3</span>

                            <div class="info-box-content">
                                <h3>{{__("global.search_invoice")}}</h3>
                                <span class="">{{__("global.press_f3_to_search_invoice")}}</span>
                            </div>
                        </div>
                        <div class="callout callout-success info-box col-md-5 col-sm-12 m-2">

                            <span class="info-box-icon bg-success">F4</span>

                            <div class="info-box-content">
                                <h3>{{__("global.welcome_page")}}</h3>
                                <span class="">{{__("global.press_f4_to_view_welcome_page")}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="callout callout-info info-box col-md-6 col-sm-12 m-2">

                            <span class="info-box-icon bg-info">F5</span>

                            <div class="info-box-content">
                                <h3>{{__("global.refresh_page")}}</h3>
                                <span class="">{{__("global.press_f5_to_refresh_the_page")}}</span>
                            </div>
                        </div>
                        <div class="callout callout-warning info-box col-md-5 col-sm-12 m-2">

                            <span class="info-box-icon bg-warning">F1</span>

                            <div class="info-box-content">
                                <h3>{{__("global.open_recyclebin")}}</h3>
                                <span class="">{{__("global.press_f6_to_view_recyclebin_page")}}</span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    @endsection
</x-masterLayout.master>
