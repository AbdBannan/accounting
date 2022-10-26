<x-masterLayout.master>


    @section("content")
    @php
        $breadcrumbs = [
          trans('backpack::crud.admin') => backpack_url('dashboard'),
          trans('backpack::backup.backup') => false,
        ];
    @endphp

    <div class="container">
            <section class="container-fluid">
                <h2>
                    <span class="text-capitalize">{{ trans('backpack::backup.backup') }}</span>
                </h2>
            </section>
            <!-- Default box -->
            <a id="create-new-backup-button" href="{{ route("backup.store") }}" class="btn btn-primary ladda-button mb-2" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::backup.create_a_new_backup') }}</span></a>
{{--            <a id="create-new-backup-button" href="{{ url(config('backpack.base.route_prefix', 'admin').'/backup/create') }}" class="btn btn-primary ladda-button mb-2" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::backup.create_a_new_backup') }}</span></a>--}}
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover pb-0 mb-0" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('backpack::backup.location') }}</th>
                            <th>{{ trans('backpack::backup.date') }}</th>
                            <th class="text-right">{{ trans('backpack::backup.file_size') }}</th>
                            <th class="text-right">{{ trans('backpack::backup.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($backups as $k => $b)
                            <tr>
                                <th scope="row">{{ $k+1 }}</th>
                                <td>{{ $b['disk'] }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimeStamp($b['last_modified'])->formatLocalized('%d %B %Y, %H:%M') }}</td>
                                <td class="text-right">{{ round((int)$b['file_size']/1048576, 2).' MB' }}</td>
                                <td class="text-right">
                                    @if ($b['download'])
{{--                                        <a class="btn btn-sm btn-link" href="{{ url(config('backpack.base.route_prefix', 'admin').'/backup/download/') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="la la-cloud-download"></i> {{ trans('backpack::backup.download') }}</a>--}}
                                                <a class="btn btn-sm btn-link" href="{{ url('backup/download/') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="la la-cloud-download"></i> {{ trans('backpack::backup.download') }}</a>



                                        {{--                                        <a class="btn btn-sm btn-link" href="{{ '/backup/download/'}}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="la la-cloud-download"></i> {{ trans('backpack::backup.download') }}</a>--}}
{{--                                        <a class="btn btn-sm btn-link" href="{{ route("backup.download") }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="la la-cloud-download"></i> {{ trans('backpack::backup.download') }}</a>--}}
                                    @endif
                                    <a class="btn btn-sm btn-link" data-button-type="delete" href="{{route("backup.delete",$b['file_name']) }}?disk={{ $b['disk'] }}"><i class="la la-trash-o"></i> {{ trans('backpack::backup.delete') }}</a>
{{--                                        <form method="POST" action="{{route("backup.destroy",$b['disk'])}}">--}}
{{--                                            @csrf--}}
{{--                                            @method("delete")--}}
{{--                                            <input type="submit" class="btn btn-sm btn-link" data-button-type="delete" value="{{ trans('backpack::backup.delete') }}"><i class="la la-trash-o"></i> </a>--}}
{{--                                        </form>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
@endsection
        after_scripts
@section('script')
    <script>
        jQuery(document).ready(function($) {

            // capture the Create new backup button
            {{--$("#create-new-backup-button").click(function(e) {--}}
            {{--    e.preventDefault();--}}

            {{--    var create_backup_url = $(this).attr('href');--}}

            {{--    // do the backup through ajax--}}
            {{--    $.ajax({--}}
            {{--        url: create_backup_url,--}}
            {{--        type: 'PUT',--}}
            {{--        success: function(result) {--}}
            {{--            // Show an alert with the result--}}
            {{--            if (result.indexOf('failed') >= 0) {--}}
            {{--                new Noty({--}}
            {{--                    text: "<strong>{{ trans('backpack::backup.create_warning_title') }}</strong><br>{{ trans('backpack::backup.create_warning_message') }}",--}}
            {{--                    type: "warning"--}}
            {{--                }).show();--}}
            {{--            }--}}
            {{--            else--}}
            {{--            {--}}
            {{--                new Noty({--}}
            {{--                    text: "<strong>{{ trans('backpack::backup.create_confirmation_title') }}</strong><br>{{ trans('backpack::backup.create_confirmation_message') }}",--}}
            {{--                    type: "success"--}}
            {{--                }).show();--}}
            {{--            }--}}
            {{--        },--}}
            {{--        error: function(result) {--}}
            {{--            // Show an alert with the result--}}
            {{--            new Noty({--}}
            {{--                text: "<strong>{{ trans('backpack::backup.create_error_title') }}</strong><br>{{ trans('backpack::backup.create_error_message') }}",--}}
            {{--                type: "warning"--}}
            {{--            }).show();--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            // capture the delete button
            {{--$("[data-button-type=delete]").click(function(e) {--}}
            {{--    e.preventDefault();--}}
            {{--    var delete_button = $(this);--}}
            {{--    var delete_url = $(this).attr('href');--}}

            {{--    if (confirm("{{ trans('backpack::backup.delete_confirm') }}") == true) {--}}
            {{--        $.ajax({--}}
            {{--            url: delete_url,--}}
            {{--            type: 'DELETE',--}}
            {{--            success: function(result) {--}}
            {{--                // Show an alert with the result--}}
            {{--                new Noty({--}}
            {{--                    text: "<strong>{{ trans('backpack::backup.delete_confirmation_title') }}</strong><br>{{ trans('backpack::backup.delete_confirmation_message') }}",--}}
            {{--                    type: "success"--}}
            {{--                }).show();--}}
            {{--                // delete the row from the table--}}
            {{--                delete_button.parentsUntil('tr').parent().remove();--}}
            {{--            },--}}
            {{--            error: function(result) {--}}
            {{--                // Show an alert with the result--}}
            {{--                new Noty({--}}
            {{--                    text: "<strong>{{ trans('backpack::backup.delete_error_title') }}</strong><br>{{ trans('backpack::backup.delete_error_message') }}",--}}
            {{--                    type: "warning"--}}
            {{--                }).show();--}}
            {{--            }--}}
            {{--        });--}}
            {{--    } else {--}}
            {{--        new Noty({--}}
            {{--            text: "<strong>{{ trans('backpack::backup.delete_cancel_title') }}</strong><br>{{ trans('backpack::backup.delete_cancel_message') }}",--}}
            {{--            type: "info"--}}
            {{--        }).show();--}}
            {{--    }--}}
            {{--});--}}

        });
    </script>
@endsection

</x-masterLayout.master>

