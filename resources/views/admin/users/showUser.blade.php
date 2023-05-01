<x-masterLayout.master>
    @section("title")
        {{__("global.view_profile")}}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("user.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.users")])}}
        </a>
    @endsection
    @section("content")-

        <div class="container">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
{{--                        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>--}}
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{__("global.profile")}}</h1>
                                </div>

                                <form method="POST" class="user" action="{{ route('user.updateUser',$user) }}" accept-charset="UTF-8" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input tabindex="1" id="first_name" type="text" class="form-control form-control-user @error('first_name') is-invalid @enderror" placeholder="{{__("global.first_name")}}" name="first_name" value="{{ $user->first_name }}" autocomplete="first_name" autofocus>
                                            @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <input tabindex="2" id="last_name" type="text" class="form-control form-control-user @error('last_name') is-invalid @enderror" placeholder="{{__("global.last_name")}}" name="last_name" value="{{ $user->last_name }}" autocomplete="last_name" autofocus>
                                            @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input tabindex="3" id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" placeholder="{{__("global.enter_email_address")}}" autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <div class="form-group">
                                                <input tabindex="4" id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="{{__("global.password")}}" autocomplete="new-password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                            </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <input tabindex="5" id="password-confirm"  name="password_confirmation" type="password" class="form-control form-control-user" placeholder="{{__("global.confirm_your_password")}}" autocomplete="new-password">
                                            </div>

                                            <div class="form-group">
                                                <input tabindex="6" id="file" type="file" class=" form-control-file" name="file" placeholder="{{__("profile_image")}}">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{__("messages.max_size_is_3_MB")}}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group text-center" >
                                                <div id="disp_tmp_path"></div>
                                                <img id="profile_image" src="{{asset($user->profile_image)}}" style="width:100%;max-width:170px;margin:10px auto;border-radius:50%">
                                            </div>
                                        </div>
                                    </div>
                                    @can("update",$user )
                                        <input tabindex="7" id="btn_update_user" type="submit" class="btn btn-primary btn-user btn-block" value="{{__('global.update')}}">
                                    @endcan
                                    <hr>
                                </form>

                            </div>

                        </div>
                        <div class="col-lg-6 d-flex flex-column justify-content-around">
                            @if(auth()->user()->isAdmin())

                                <div class="card mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">{{__("global.roles")}}</h6>
                                    </div>
                                    <div class="card-body" >

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th>{{__("global.id")}}</th>
                                                    <th>{{__("global.role_name")}}</th>
                                                    <th>{{__("global.assign_deassign_role")}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach (App\Models\Role::all() as $role)

                                                        <tr>
                                                            <td>{{$role->id}}</td>
{{--                                                            @if(strtolower($role->name) != "admin" )--}}
{{--                                                                <td>{{$role->name}}</td>--}}
{{--                                                            @else--}}
{{--                                                                <td>{{__("global.".$role->name)}}</td>--}}
{{--                                                            @endif--}}
                                                            <td>{{$role->name}}</td>

                                                            <td class="text-center">
                                                                @if(strtolower($role->slug) == "admin")
                                                                @elseif(count($user->roles) != 0)
                                                                    @php $found = false; @endphp
                                                                    @foreach($user->roles as $user_role)
                                                                        @if($user_role->slug == $role->slug)
                                                                            @php $found = true; @endphp
                                                                            @break
                                                                        @endif
                                                                    @endforeach

                                                                    @if($found == true)
                                                                        <a id="btn_detach_role" route-attr="{{route("user.detachRole",[$user->id,$role->id])}}" class="btn btn-sm btn-success">{{__("global.detach")}}</a>
                                                                        <a id="btn_attach_role" route-attr="{{route("user.attachRole",[$user->id,$role->id])}}" hidden="true" class="btn btn-sm btn-danger">{{__("global.attach")}}</a>
                                                                    @else
                                                                        <a id="btn_detach_role" route-attr="{{route("user.detachRole",[$user->id,$role->id])}}" hidden="true" class="btn btn-sm btn-success">{{__("global.detach")}}</a>
                                                                        <a id="btn_attach_role" route-attr="{{route("user.attachRole",[$user->id,$role->id])}}" class="btn btn-sm btn-danger">{{__("global.attach")}}</a>
                                                                    @endif

                                                                @else
                                                                    <a id="btn_detach_role" route-attr="{{route("user.detachRole",[$user->id,$role->id])}}" hidden="true" class="btn btn-sm btn-success">{{__("global.detach")}}</a>
                                                                    <a id="btn_attach_role" route-attr="{{route("user.attachRole",[$user->id,$role->id])}}" class="btn btn-sm btn-danger">{{__("global.attach")}}</a>
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
            </div>

        </div>
    @endsection

    @section("script")

    @endsection
</x-masterLayout.master>
