<form action="{{route("config.saveConfig")}}" method="post" autocomplete="off">
    @csrf
{{--    <aside class="control-sidebar-dark" style="display: block;">--}}
        <!-- Control sidebar content goes here -->
        <h5>{{__("global.customize_view")}}</h5>
        <hr class="mb-2">
        <div class="row p-3 control-sidebar-content" style="">
            <div class="col-sm-12 col-md-4">
                <div class="mb-4">
                    <input name="dark_mode" type="checkbox" value="1" @if(isset($config["dark_mode"]) and $config["dark_mode"]) checked @endif class="mr-1">
                    <span>{{__("global.dark_mode")}}</span>
                </div>
                <h6>{{__("global.header_options")}}</h6>
                <div class="mb-1">
                    <input name="fixed_header" type="checkbox" value="1" @if(isset($config["fixed_header"]) and $config["fixed_header"]) checked @endif class="mr-1">
                    <span>{{__("global.fixed")}}</span>
                </div>
                <div class="mb-1">
                    <input name="drop_down_legacy_offset" type="checkbox" value="1" @if(isset($config["drop_down_legacy_offset"]) and $config["drop_down_legacy_offset"]) checked @endif class="mr-1">
                    <span>{{__("global.drop_down_legacy_offset")}}</span>
                </div>
                <div class="mb-4">
                    <input name="no_border" type="checkbox" value="1" @if(isset($config["no_border"]) and $config["no_border"]) checked @endif class="mr-1">
                    <span>{{__("global.no_border")}}</span>
                </div>
                <h6>{{__("global.footer_options")}}</h6>
                <div class="mb-1">
                    <input name="fixed_footer" type="checkbox" value="1" @if(isset($config["fixed_footer"]) and $config["fixed_footer"]) checked @endif class="mr-1">
                    <span>{{__("global.fixed")}}</span>
                </div>
                <h6>{{__("global.small_text_options")}}</h6>
                <div class="mb-1">
                    <input name="body_small_text_options" type="checkbox" value="1" @if(isset($config["body_small_text_options"]) and $config["body_small_text_options"]) checked @endif class="mr-1">
                    <span>{{__("global.body")}}</span>
                </div>
                <div class="mb-1">
                    <input name="navbar_small_text_options" type="checkbox" value="1" @if(isset($config["navbar_small_text_options"]) and $config["navbar_small_text_options"]) checked @endif class="mr-1">
                    <span>{{__("global.navbar")}}</span>
                </div>
                <div class="mb-1">
                    <input name="brand_small_text_options" type="checkbox" value="1" @if(isset($config["brand_small_text_options"]) and $config["brand_small_text_options"]) checked @endif class="mr-1">
                    <span>{{__("global.brand")}}</span>
                </div>
                <div class="mb-1">
                    <input name="side_bar_nav_small_text_options" type="checkbox" value="1" @if(isset($config["side_bar_nav_small_text_options"]) and $config["side_bar_nav_small_text_options"]) checked @endif class="mr-1">
                    <span>{{__("global.side_bar_nav")}}</span>
                </div>
                <div class="mb-1">
                    <input name="footer_small_text_options" type="checkbox" value="1" @if(isset($config["footer_small_text_options"]) and $config["footer_small_text_options"]) checked @endif class="mr-1">
                    <span>{{__("global.footer")}}</span>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <h6>{{__("global.sidebar_options")}}</h6>
                <div class="mb-1">
                    <input name="collapsed" type="checkbox" value="1" @if(isset($config["collapsed"]) and $config["collapsed"]) checked @endif class="mr-1">
                    <span>{{__("global.collapsed")}}</span>
                </div>
                <div class="mb-1">
                    <input name="fixed_sidebar" type="checkbox" value="1" @if(isset($config["fixed_sidebar"]) and $config["fixed_sidebar"]) checked @endif class="mr-1">
                    <span>{{__("global.fixed")}}</span>
                </div>
                <div class="mb-1">
                    <input name="sidebar_mini" type="checkbox" value="1" @if(isset($config["sidebar_mini"]) and $config["sidebar_mini"]) checked @endif class="mr-1">
                    <span>{{__("global.sidebar_mini")}}</span>
                </div>
                <div class="mb-1">
                    <input name="sidebar_mini_md" type="checkbox" value="1" @if(isset($config["sidebar_mini_md"]) and $config["sidebar_mini_md"]) checked @endif class="mr-1">
                    <span>{{__("global.sidebar_mini_md")}}</span>
                </div>
                <div class="mb-1">
                    <input name="sidebar_mini_xs" type="checkbox" value="1" @if(isset($config["sidebar_mini_xs"]) and $config["sidebar_mini_xs"]) checked @endif class="mr-1">
                    <span>{{__("global.sidebar_mini_xs")}}</span>
                </div>
                <div class="mb-1">
                    <input name="nav_legacy_style" type="checkbox" value="1" @if(isset($config["nav_legacy_style"]) and $config["nav_legacy_style"]) checked @endif class="mr-1">
                    <span>{{__("global.nav_legacy_style")}}</span>
                </div>
                <div class="mb-1">
                    <input name="nav_compact" type="checkbox" value="1" @if(isset($config["nav_compact"]) and $config["nav_compact"]) checked @endif class="mr-1">
                    <span>{{__("global.nav_compact")}}</span>
                </div>
                <div class="mb-1">
                    <input name="nav_child_indent" type="checkbox" value="1" @if(isset($config["nav_child_indent"]) and $config["nav_child_indent"]) checked @endif class="mr-1">
                    <span>{{__("global.nav_child_indent")}}</span>
                </div>
                <div class="mb-1">
                    <input name="nav_child_hide_on_collapse" type="checkbox" value="1" @if(isset($config["nav_child_hide_on_collapse"]) and $config["nav_child_hide_on_collapse"]) checked @endif class="mr-1">
                    <span>{{__("global.nav_child_hide_on_collapse")}}</span>
                </div>
                <div class="mb-1">
                    <input name="disable_hover_or_focus_auto_expand" type="checkbox" value="1" @if(isset($config["disable_hover_or_focus_auto_expand"]) and $config["disable_hover_or_focus_auto_expand"]) checked @endif class="mr-1">
                    <span>{{__("global.disable_hover/focus_auto-expand")}}</span>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <h6>{{__("global.navbar_variants")}}</h6>
                <div class="d-flex">
                    <select id="navbar_variants" name="navbar_variants" class="custom-select mb-3 border-0 bg-white">
                        <option class="bg-primary">Primary</option>
                        <option class="bg-secondary">Secondary</option>
                        <option class="bg-info">Info</option>
                        <option class="bg-success">Success</option>
                        <option class="bg-danger">Danger</option>
                        <option class="bg-indigo">Indigo</option>
                        <option class="bg-purple">Purple</option>
                        <option class="bg-pink">Pink</option>
                        <option class="bg-navy">Navy</option>
                        <option class="bg-lightblue">Lightblue</option>
                        <option class="bg-teal">Teal</option>
                        <option class="bg-cyan">Cyan</option>
                        <option class="bg-dark">Dark</option>
                        <option class="bg-gray-dark">Gray dark</option>
                        <option class="bg-gray">Gray</option>
                        <option class="bg-light">Light</option>
                        <option class="bg-warning">Warning</option>
                        <option class="bg-white">White</option>
                        <option class="bg-orange">Orange</option>
                    </select>
                    <script>
                        @if(isset($config["navbar_variants"]))
                        let options = document.querySelectorAll("#navbar_variants option");
                        for (let option in options){
                            if (options[option].innerHTML == "{{$config["navbar_variants"]}}") {
                                options[option].selected = true;
                            }
                        }
                        @endif
                    </script>
                </div>
                <h6>{{__("global.accent_color_variants")}}</h6>
                <div class="d-flex">
                    <select id="accent_color_variants"  name="accent_color_variants" class="custom-select mb-3 border-0">
                        <option>None Selected</option>
                        <option class="bg-primary">Primary</option>
                        <option class="bg-warning">Warning</option>
                        <option class="bg-info">Info</option>
                        <option class="bg-danger">Danger</option>
                        <option class="bg-success">Success</option>
                        <option class="bg-indigo">Indigo</option>
                        <option class="bg-lightblue">Lightblue</option>
                        <option class="bg-navy">Navy</option>
                        <option class="bg-purple">Purple</option>
                        <option class="bg-fuchsia">Fuchsia</option>
                        <option class="bg-pink">Pink</option>
                        <option class="bg-maroon">Maroon</option>
                        <option class="bg-orange">Orange</option>
                        <option class="bg-lime">Lime</option>
                        <option class="bg-teal">Teal</option>
                        <option class="bg-olive">Olive</option>
                    </select>
                    <script>
                        @if(isset($config["accent_color_variants"]))
                            options = document.querySelectorAll("#accent_color_variants option");
                        for (let option in options){
                            if (options[option].innerHTML == "{{$config["accent_color_variants"]}}") {
                                options[option].selected = true;
                            }
                        }
                        @endif
                    </script>
                </div>
                <h6>{{__("global.dark_sidebar_variants")}}</h6>
                <div class="d-flex">
                    <select id="dark_sidebar_variants" name="dark_sidebar_variants" class="custom-select mb-3 text-light border-0 bg-primary">
                        <option>None Selected</option>
                        <option class="bg-primary">Primary</option>
                        <option class="bg-warning">Warning</option>
                        <option class="bg-info">Info</option>
                        <option class="bg-danger">Danger</option>
                        <option class="bg-success">Success</option>
                        <option class="bg-indigo">Indigo</option>
                        <option class="bg-lightblue">Lightblue</option>
                        <option class="bg-navy">Navy</option>
                        <option class="bg-purple">Purple</option>
                        <option class="bg-fuchsia">Fuchsia</option>
                        <option class="bg-pink">Pink</option>
                        <option class="bg-maroon">Maroon</option>
                        <option class="bg-orange">Orange</option>
                        <option class="bg-lime">Lime</option>
                        <option class="bg-teal">Teal</option>
                        <option class="bg-olive">Olive</option>
                    </select>
                    <script>
                        @if(isset($config["dark_sidebar_variants"]))
                            options = document.querySelectorAll("#dark_sidebar_variants option");
                        for (let option in options){
                            if (options[option].innerHTML == "{{$config["dark_sidebar_variants"]}}") {
                                options[option].selected = true;
                            }
                        }
                        @endif
                    </script>
                </div>
                <h6>{{__("global.light_sidebar_variants")}}</h6>
                <div class="d-flex">
                    <select id="light_sidebar_variants" name="light_sidebar_variants" class="custom-select mb-3 border-0">
                        <option>None Selected</option>
                        <option class="bg-primary">Primary</option>
                        <option class="bg-warning">Warning</option>
                        <option class="bg-info">Info</option>
                        <option class="bg-danger">Danger</option>
                        <option class="bg-success">Success</option>
                        <option class="bg-indigo">Indigo</option>
                        <option class="bg-lightblue">Lightblue</option>
                        <option class="bg-navy">Navy</option>
                        <option class="bg-purple">Purple</option>
                        <option class="bg-fuchsia">Fuchsia</option>
                        <option class="bg-pink">Pink</option>
                        <option class="bg-maroon">Maroon</option>
                        <option class="bg-orange">Orange</option>
                        <option class="bg-lime">Lime</option>
                        <option class="bg-teal">Teal</option>
                        <option class="bg-olive">Olive</option>
                    </select>
                    <script>
                        @if(isset($config["light_sidebar_variants"]))
                            options = document.querySelectorAll("#light_sidebar_variants option");
                        for (let option in options){
                            if (options[option].innerHTML == "{{$config["light_sidebar_variants"]}}") {
                                options[option].selected = true;
                            }
                        }
                        @endif
                    </script>
                </div>
                <h6>{{__("global.brand_logo_variants")}}</h6>
                <div class="d-flex">
                    <select id="brand_logo_variants" name="brand_logo_variants" class="custom-select mb-3 border-0">
                        <option>None Selected</option>
                        <option class="bg-primary">Primary</option>
                        <option class="bg-secondary">Secondary</option>
                        <option class="bg-info">Info</option>
                        <option class="bg-success">Success</option>
                        <option class="bg-danger">Danger</option>
                        <option class="bg-indigo">Indigo</option>
                        <option class="bg-purple">Purple</option>
                        <option class="bg-pink">Pink</option>
                        <option class="bg-navy">Navy</option>
                        <option class="bg-lightblue">Lightblue</option>
                        <option class="bg-teal">Teal</option>
                        <option class="bg-cyan">Cyan</option>
                        <option class="bg-dark">Dark</option>
                        <option class="bg-gray-dark">Gray dark</option>
                        <option class="bg-gray">Gray</option>
                        <option class="bg-light">Light</option>
                        <option class="bg-warning">Warning</option>
                        <option class="bg-white">White</option>
                        <option class="bg-orange">Orange</option>
                        <a href="#">clear</a>
                    </select>
                    <script>
                        @if(isset($config["brand_logo_variants"]))
                            options = document.querySelectorAll("#brand_logo_variants option");
                        for (let option in options){
                            if (options[option].innerHTML == "{{$config["brand_logo_variants"]}}") {
                                options[option].selected = true;
                            }
                        }
                        @endif
                    </script>
                </div>
            </div>
        </div>
{{--    </aside>--}}
    <input type="hidden" name="config_type" value="look">
    <input id="btn_save_config" type="submit" class="btn btn-primary" value="{{__("global.save")}}">
</form>



