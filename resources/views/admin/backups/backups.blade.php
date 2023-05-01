<x-masterLayout.master>

    @section("title")
        {{__("global.backups")}}
    @endsection

    @section("content")
        @php
            $breadcrumbs = [
              trans('backpack::crud.admin') => backpack_url('dashboard'),
              trans('backpack::backup.backup') => false,
            ];
        @endphp

        <div class="container">

                <!-- Default box -->
            <div class="form-group">
                <a id="create-new-backup-button" href="{{ route("backup.store") }}" class="btn btn-info btn-sm" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::backup.create_a_new_backup') }}</span></a>
                <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("backup.delete",-1) }}?disk=@if(count($backups)>0){{ $backups[0]['disk'] }}@endif">
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected")}}
                </a>
            </div>
{{--            <a id="create-new-backup-button" href="{{ url(config('backpack.base.route_prefix', 'admin').'/backup/create') }}" class="btn btn-primary ladda-button mb-2" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::backup.create_a_new_backup') }}</span></a>--}}
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ trans('backpack::backup.backup') }}</h6>
                </div>
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><input id="check_all" type="checkbox" class="form-check"></th>
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
                                    <td><input form="form_delete" name="multi_files_name[]" value="{{$b['file_name']}}" type="checkbox" class="form-check"></td>
                                    <th scope="row">{{ $k+1 }}</th>
                                    <td>{{ $b['disk'] }}</td>
                                    <td>{{ \Carbon\Carbon::createFromTimeStamp($b['last_modified'])->formatLocalized('%d %B %Y, %H:%M') }}</td>
                                    <td class="text-right">{{ round((int)$b['file_size']/1048576, 2).' MB' }}</td>
                                    <td class="text-right">
                                        @if ($b['download'])
                                                <a class="btn btn-sm btn-link" href="{{ url('backup/download/') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}" title="{{ trans('backpack::backup.download') }}"><i class="fas fa-download text-green"></i></a>
                                        @endif
{{--                                        <a class="btn btn-sm btn-link" data-button-type="delete" href="" title=" {{ trans('backpack::backup.delete') }}"><i class="fas fa-trash text-red"></i></a>--}}
                                        <a id="btn_delete" title="{{__("global.delete")}}" class="btn-link" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("backup.delete",$b['file_name']) }}?disk={{ $b['disk'] }}">
                                            <i class="fas fa-trash text-red"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    @endsection
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
    @endsection
        after_scripts
    @section('script')
        
    @endsection

</x-masterLayout.master>

