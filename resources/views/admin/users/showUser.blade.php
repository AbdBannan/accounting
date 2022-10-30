<x-masterLayout.master>
    @section("title")
        {{__("global.view_profile",[],session("lang"))}}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("user.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.users",[],session("lang"))],session("lang"))}}
        </a>
    @endsection
    @section("content")

        <div class="container">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{__("global.profile",[],session("lang"))}}</h1>
                                </div>

                                <form method="POST" class="user" action="{{ route('user.updateUser',$user) }}" accept-charset="UTF-8" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input id="first_name" type="text" class="form-control form-control-user @error('first_name') is-invalid @enderror" placeholder="{{__("global.first_name",[],session("lang"))}}" name="first_name" value="{{ $user->first_name }}" autocomplete="first_name" autofocus>
                                            @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <input id="last_name" type="text" class="form-control form-control-user @error('last_name') is-invalid @enderror" placeholder="{{__("global.last_name",[],session("lang"))}}" name="last_name" value="{{ $user->last_name }}" autocomplete="last_name" autofocus>
                                            @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" placeholder="{{__("global.enter_email_address",[],session("lang"))}}" autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <div class="form-group">
                                                <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="{{__("global.password",[],session("lang"))}}" autocomplete="new-password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                            </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <input id="password-confirm"  name="password_confirmation" type="password" class="form-control form-control-user" placeholder="{{__("global.confirm_your_password",[],session("lang"))}}" autocomplete="new-password">
                                            </div>

                                            <div class="form-group">
                                                <input id="file" type="file" class=" form-control-file" name="file" placeholder="{{__("profile_image",[],session("lang"))}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group text-center" >
                                                <div id="disp_tmp_path"></div>
                                                <img id="profile-image" src="{{asset($user->profile_image)}}" style="width:100%;max-width:170px;margin:10px auto;border-radius:50%">
                                            </div>
                                        </div>
                                    </div>
                                    @can("update",$user )
                                        <input id="btn_update_user" type="submit" class="btn btn-primary btn-user btn-block" value="{{__('global.update',[],session("lang"))}}">
                                    @endcan
                                    <hr>
                                </form>

                            </div>

                        </div>

                    </div>
                    @if(auth()->user()->hasRole('admin'))

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">{{__("global.roles",[],session("lang"))}}</h6>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>{{__("global.id",[],session("lang"))}}</th>
                                            <th>{{__("global.role_name",[],session("lang"))}}</th>
                                            <th>{{__("global.assign_deassign_role",[],session("lang"))}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach (App\Models\Role::all() as $role)

                                                <tr>
                                                    <td>{{$role->id}}</td>
                                                    <td>{{$role->name}}</td>
                                                    <td class="text-center">
                                                        @if($role->slug == "admin")
                                                        @elseif(count($user->roles) != 0)
                                                            @php $found = false; @endphp
                                                            @foreach($user->roles as $user_role)
                                                                @if($user_role->slug == $role->slug)
                                                                    @php $found = true; @endphp
                                                                    @break
                                                                @endif
                                                            @endforeach

                                                            @if($found == true)
                                                                <a id="btn_detach_role" route-attr="{{route("user.detachRole",[$user->id,$role->id])}}" class="btn btn-sm btn-success">{{__("global.detach",[],session("lang"))}}</a>
                                                                <a id="btn_attach_role" route-attr="{{route("user.attachRole",[$user->id,$role->id])}}" hidden="true" class="btn btn-sm btn-danger">{{__("global.attach",[],session("lang"))}}</a>
                                                            @else
                                                                <a id="btn_detach_role" route-attr="{{route("user.detachRole",[$user->id,$role->id])}}" hidden="true" class="btn btn-sm btn-success">{{__("global.detach",[],session("lang"))}}</a>
                                                                <a id="btn_attach_role" route-attr="{{route("user.attachRole",[$user->id,$role->id])}}" class="btn btn-sm btn-danger">attach</a>
                                                            @endif

                                                        @else
                                                            <a id="btn_detach_role" route-attr="{{route("user.detachRole",[$user->id,$role->id])}}" hidden="true" class="btn btn-sm btn-success">{{__("global.detach",[],session("lang"))}}</a>
                                                            <a id="btn_attach_role" route-attr="{{route("user.attachRole",[$user->id,$role->id])}}" class="btn btn-sm btn-danger">attach</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    @endif
                </div>
            </div>

        </div>
    @endsection

    @section("script")
    <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
