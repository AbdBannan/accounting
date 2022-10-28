<div id="errors" style="max-width: 200px; position: fixed;bottom: 10px; @if(auth()->user()->getConfig("language")) left: 2px; @else right: 2px; @endif z-index: 100">
    @if(count($errors)>0 || session("success") || session("error"))
        <button onclick="$('#errors').slideToggle();" class="btn btn-info btn-sm btn-block mb-2" >
            {{__("global.hide_all",[],session("lang"))}}
        </button>
    @endif
    @if (count($errors)>0)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show">
                <button class="close" data-dismiss="alert" type="button">
                    <span>×</span>
                </button>
                {{$error}}
            </div>
        @endforeach
    @endif

    @if (session("success"))
        <div class="alert alert-success alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>×</span>
            </button>
            {{session("success")}}
        </div>
    @endif

    @if (session("error"))
        <div class="alert alert-danger alert-dismissible fade show">
            <button class="close" data-dismiss="alert" type="button">
                <span>×</span>
            </button>
            {{session("error")}}
        </div>
    @endif
    <script>
        setTimeout(function (){
            $("#errors").slideUp();
        },6000);
    </script>
</div>

