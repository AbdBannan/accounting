@extends('layouts.app')

@section('content')
    @if (isset($_GET["lang"]))
        @php
            app()->setLocale($_GET["lang"]);
        @endphp
    @else
        @php
            $_GET["lang"] = app()->getLocale();
        @endphp
    @endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('global.verify_your_email_address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('global.a_fresh_verification_link_has_been_sent_to_your_email_address') }}
                        </div>
                    @endif

                    {{ __('global.before_proceeding_please_check_your_email_for_a_verification_link') }}
                    {{ __('global.if_you_did_not_receive_the_email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('global.click_here_to_request_another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
