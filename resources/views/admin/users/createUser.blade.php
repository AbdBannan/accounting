<x-masterLayout.master>
    @section("title")
        {{__("global.create_user")}}
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
                                    <h1 class="h4 text-gray-900 mb-4">{{__("global.new_user")}}</h1>
                                </div>

                                <form method="POST" class="user" action="{{ route('user.storeUser') }}" accept-charset="UTF-8" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input tabindex="1" id="first_name" type="text" class="form-control form-control-user @error('first_name') is-invalid @enderror" placeholder="{{__("global.first_name")}}" name="first_name"  autocomplete="first_name" autofocus>
                                            @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <input tabindex="2" id="last_name" type="text" class="form-control form-control-user @error('last_name') is-invalid @enderror" placeholder="{{__("global.last_name")}}" name="last_name" autocomplete="last_name" autofocus>
                                            @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input tabindex="3" id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email"  placeholder="{{__("global.enter_email_address")}}" autocomplete="email">
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
                                                <input tabindex="6" id="file" type="file" class=" form-control-file" name="file" placeholder="profile image">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{__("messages.max_size_is_3_MB")}}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group text-center" >
                                                <div id="disp_tmp_path"></div>
                                                <img id="profile_image" src="{{asset("images/systemImages/default_user_img.png")}}" style="width:100%;max-width:170px;margin:10px auto;border-radius:50%">
                                            </div>
                                        </div>
                                    </div>
                                    <input tabindex="7" id="but_create_user" type="submit" class="btn btn-primary btn-user btn-block" value="{{ __('global.create') }}">
                                    <hr>
                                </form>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    @endsection

    @section("script")

    @endsection
</x-masterLayout.master>
