<x-masterLayout.master>
    @section("title")
        {{ __("global.all_accounts_discover") }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.all_accounts_discover")}}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.account_id")}}</th>
                                    <th>{{__("global.account_name")}}</th>
                                    <th class="fade1" style="display: none">{{__("global.debit")}}</th>
                                    <th class="fade1" style="display: none">{{__("global.credit")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($actions)>0)
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td>{{$action->id}}</td>
                                            <td id="first_part_name">{{$action->name}}</td>
                                            @if($action->credit - $action->debit > 0)
                                                <td class="fade1" style="display: none"></td>
                                                <td class="fade1" style="display: none">{{round(abs($action->credit - $action->debit),2)}}</td>
                                            @else
                                                <td class="fade1" style="display: none">{{round(abs($action->credit - $action->debit),2)}}</td>
                                                <td class="fade1" style="display: none"></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" id="show_debit_credit">0</th>
                                        <th class="fade1" style="display:none;">{{round($total_debit,2)}}</th>
                                        <th class="fade1" style="display:none;">{{round($total_credit,2)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <form id="go_to_global_discover_form" hidden action="{{route("discover.globalDiscoverUntilNow")}}" method="get">
                        @csrf
                        <input id="account" name="account" type="hidden">
                        <input type="submit" hidden>
                    </form>
                    <div class="card-footer">
                    </div>
                </div>
            @endif
        </div>

    @endsection
    @section("script")
        <script>
            // to navigate into the discover for the clicked account
            $("tr#discover_rows").on("dblclick",function (){
                let first_part_name = $(this).children("td#first_part_name").text();
                $("form#go_to_global_discover_form input#account").val(first_part_name);
                $("form#go_to_global_discover_form input[type='submit']").click();
            });
            let debit_credit_show_status = true;
            // this is to switch the show debit_credit column on or off
            $("#show_debit_credit").on("dblclick",function (){
                if (debit_credit_show_status) {
                    $(".fade1").fadeIn();
                    $(this).text("");
                    debit_credit_show_status = false;
                }
                else {
                    $(".fade1").fadeOut();
                    $(this).text(0);
                    debit_credit_show_status = true;
                }
            });
            // update the debit_credit column status
            function columnShowHide(){
                if (!debit_credit_show_status) {
                    $(".fade1").fadeIn();
                    $("#show_debit_credit").text("");
                    debit_credit_show_status = false;
                }
                else {
                    $(".fade1").fadeOut();
                    $("#show_debit_credit").text(0);
                    debit_credit_show_status = true;
                }
            }




            // Call the dataTables jQuery plugin

            let info = "{{__("global.Showing")}} _START_ {{__("global.to")}} _END_ {{__("global.of")}} _TOTAL_ {{__("global.entries")}}";
            let emptyTable = "{{__("global.no_data_available_in_table")}}";
            let infoEmpty = "{{__("global.Showing")}} 0 {{__("global.to")}} 0 {{__("global.of")}} 0 {{__("global.entries")}}";
            let lengthMenu = "{{__("global.Show")}} _MENU_ {{__("global.entries")}}";
            let loadingRecords = "{{__("global.please_wait_loading")}}";
            let search = "{{__("global.Search")}}:";
            let next = "{{__("global.Next")}}";
            let previous = "{{__("global.Previous")}}";
            let infoFiltered = " - {{__("global.filtered_from")}} _MAX_ {{__("global.entries")}}";
            let pageLength = {{auth()->user()->getConfig("row_count_in_table")}};
            $('#dataTable1').DataTable(
                {
                    "buttons": ["colvis"],
                    "ordering":true,
                    "autoWidth": false,
                    "language": {
                        // "info": "Showing page _PAGE_ of _PAGES_",
                        "info": info,
                        "infoEmpty": infoEmpty,
                        "emptyTable": emptyTable,
                        "lengthMenu": lengthMenu,
                        "loadingRecords": loadingRecords,
                        "search": search,
                        "paginate": {
                            "next": next,
                            "previous": previous,
                        },
                        "infoFiltered": infoFiltered,
                    },
                    "processing": true,
                    "stateSave": true,
                    "createdRow": function( row, data, dataIndex ) {
                        // alert();
                    },
                    "scrollCollapse": true,
                    "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
                    "pageLength": pageLength,
                    drawCallback: function(){
                        $('.paginate_button', this.api().table().container())
                            .on('click', function(){
                                columnShowHide();
                            });
                        $('.custom-select', this.api().table().container())
                            .on('change', function(){
                                columnShowHide();
                            });
                        $('#dataTable_filter label input', this.api().table().container())
                            .on('keyup', function(){
                                columnShowHide();
                            });
                    }
                });
        </script>

    @endsection
</x-masterLayout.master>
