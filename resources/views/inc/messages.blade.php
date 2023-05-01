<div id="errors" style="max-width: 200px; position: fixed;bottom: 10px; @if(auth()->user()->getConfig("language") == "arabic") left: 2px; @else right: 2px; @endif z-index: 100">
    @if (count($errors)>0)
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{\App\functions\globalFunctions::fixTranslation($error)}}');
            </script>
        @endforeach
    @endif

    @if (session("success"))
        <script>
            toastr.success('{{session("success")}}');
        </script>
    @endif

    @if (session("error"))
        <script>
            toastr.error('{{session("error")}}');
        </script>
    @endif
</div>

