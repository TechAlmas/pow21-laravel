<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-{{ trans('crudbooster.left') }} image">
                <img src="{{ CRUDBooster::myPhoto() }}" class="img-circle" alt="{{ trans('crudbooster.user_image') }}"/>
            </div>
            <div class="pull-{{ trans('crudbooster.left') }} info">
                <p>{{ CRUDBooster::myName() }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('crudbooster.online') }}</a>
            </div>
        </div>


        <div class='main-menu'>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">{{trans("crudbooster.menu_navigation")}}</li>
                <!-- Optionally, you can add icons to the links -->

                <?php $dashboard = CRUDBooster::sidebarDashboard();?>
                @if($dashboard)
                    <li data-id='{{$dashboard->id}}' class="{{ (Request::is(config('crudbooster.ADMIN_PATH'))) ? 'active' : '' }}"><a
                                href='{{CRUDBooster::adminPath()}}' class='{{($dashboard->color)?"text-".$dashboard->color:""}}'><i class='fa fa-dashboard'></i>
                            <span>{{trans("crudbooster.text_dashboard")}}</span> </a></li>
							
                @endif

                @foreach(CRUDBooster::sidebarMenu() as $menu)
                    <li data-id='{{$menu->id}}' class='{{(!empty($menu->children))?"treeview":""}} {{ (Request::is($menu->url_path."*"))?"active":""}}'>
                        <a href='{{ ($menu->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$menu->url }}'
                           class='{{($menu->color)?"text-".$menu->color:""}}'>
                            <i class='{{$menu->icon}} {{($menu->color)?"text-".$menu->color:""}}'></i> <span>{{$menu->name}}</span>
                            @if(!empty($menu->children))<i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>@endif
                        </a>
                        @if(!empty($menu->children))
                            <ul class="treeview-menu">
                                @foreach($menu->children as $child)
                                    <li data-id='{{$child->id}}' class='{{(Request::is($child->url_path .= !ends_with(Request::decodedPath(), $child->url_path) ? "/*" : ""))?"active":""}}'>
                                        <a href='{{ ($child->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$child->url}}'
                                           class='{{($child->color)?"text-".$child->color:""}}'>
                                            <i class='{{$child->icon}}'></i> <span>{{$child->name}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach



                @if(CRUDBooster::isSuperadmin())
                    <li class="header">{{ trans('crudbooster.SUPERADMIN') }}</li>
                    <li class='treeview'>
                        <a href='#'><i class='fa fa-key'></i> <span>{{ trans('crudbooster.Privileges_Roles') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/privileges/add*')) ? 'active' : '' }}"><a
                                        href='{{Route("PrivilegesControllerGetAdd")}}'>{{ $current_path }}<i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Privilege') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/privileges')) ? 'active' : '' }}"><a
                                        href='{{Route("PrivilegesControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Privilege') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-users'></i> <span>{{ trans('crudbooster.Users_Management') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/users/add*')) ? 'active' : '' }}"><a
                                        href='{{Route("AdminCmsUsersControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.add_user') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/users')) ? 'active' : '' }}"><a
                                        href='{{Route("AdminCmsUsersControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_users') }}</span></a></li>
                        </ul>
                    </li>

                    <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/menu_management*')) ? 'active' : '' }}"><a
                                href='{{Route("MenusControllerGetIndex")}}'><i class='fa fa-bars'></i>
                            <span>{{ trans('crudbooster.Menu_Management') }}</span></a></li>
                    <li class="treeview">
                        <a href="#"><i class='fa fa-wrench'></i> <span>{{ trans('crudbooster.settings') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class="treeview-menu">
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/settings/add*')) ? 'active' : '' }}"><a
                                        href='{{route("SettingsControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Setting') }}</span></a></li>
                            <?php
                            $groupSetting = DB::table('cms_settings')->groupby('group_setting')->pluck('group_setting');
                            foreach($groupSetting as $gs):
                            ?>
                            <li class="<?=($gs == Request::get('group')) ? 'active' : ''?>"><a
                                        href='{{route("SettingsControllerGetShow")}}?group={{urlencode($gs)}}&m=0'><i class='fa fa-wrench'></i>
                                    <span>{{$gs}}</span></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li class='treeview'>
                        <a href='#'><i class='fa fa-th'></i> <span>{{ trans('crudbooster.Module_Generator') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/module_generator/step1')) ? 'active' : '' }}"><a
                                        href='{{Route("ModulsControllerGetStep1")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Module') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/module_generator')) ? 'active' : '' }}"><a
                                        href='{{Route("ModulsControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Module') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-dashboard'></i> <span>{{ trans('crudbooster.Statistic_Builder') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/statistic_builder/add')) ? 'active' : '' }}"><a
                                        href='{{Route("StatisticBuilderControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Statistic') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/statistic_builder')) ? 'active' : '' }}"><a
                                        href='{{Route("StatisticBuilderControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Statistic') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-fire'></i> <span>{{ trans('crudbooster.API_Generator') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator/generator*')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetGenerator")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_API') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.list_API') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator/screet-key*')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetScreetKey")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.Generate_Screet_Key') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-envelope-o'></i> <span>{{ trans('crudbooster.Email_Templates') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/email_templates/add*')) ? 'active' : '' }}"><a
                                        href='{{Route("EmailTemplatesControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Email') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/email_templates')) ? 'active' : '' }}"><a
                                        href='{{Route("EmailTemplatesControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Email_Template') }}</span></a></li>
                        </ul>
                    </li>

                    <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/logs*')) ? 'active' : '' }}"><a href='{{Route("LogsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>{{ trans('crudbooster.Log_User_Access') }}</span></a></li>

                     <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/mass-units*')) ? 'active' : '' }}"><a href='{{Route("AdminMassUnitsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Mass Units</span></a></li>

                      <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/meta_data*')) ? 'active' : '' }}"><a href='{{Route("AdminMetaDataControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Meta Managment</span></a></li>

                        <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/master-cities*')) ? 'active' : '' }}"><a href='{{Route("AdminMasterCitiesControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Cities Master</span></a></li>
                @endif
                @if(CRUDBooster::isSuperadmin())
                  <li class="header">Raw Data</li>

                 <li class='treeview'>
                        <a href='#'><i class='fa fa-flag'></i> <span>Leafly Raw Data</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>

                     <ul class='treeview-menu'>
                           <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/leafy-strains*')) ? 'active' : '' }}"><a href='{{Route("AdminLeafyStrainsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Leafly Strains</span></a></li>
                             <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/leafly-dispensaries*')) ? 'active' : '' }}"><a href='{{Route("AdminLeaflyDispensariesControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Leafly Dispensaries</span></a></li>

                                 <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/leafly-dispensaries-menu*')) ? 'active' : '' }}"><a href='{{Route("AdminLeaflyDispensariesMenuControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Leafly  Dispensaries Menu</span></a></li>

                        </ul>
                </li>
                  <li class='treeview'>
                        <a href='#'><i class='fa fa-flag'></i> <span>Weedmaps Raw Data</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>

                     <ul class='treeview-menu'>
                           <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/weedmaps-products*')) ? 'active' : '' }}"><a href='{{Route("AdminWeedmapsProductsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Weedmaps Products</span></a></li>
                             <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/weedmaps-dispensaries*')) ? 'active' : '' }}"><a href='{{Route("AdminWeedmapsDispensariesControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Weedmaps Dispensaries</span></a></li>

                                 <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/weedmaps-brands*')) ? 'active' : '' }}"><a href='{{Route("AdminWeedmapsBrandsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Weedmaps Brands</span></a></li>
                                 <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/weedmaps-brands-products*')) ? 'active' : '' }}"><a href='{{Route("AdminWeedmapsBrandsProductsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>Weedmaps Brands Products</span></a></li>

                        </ul>
                </li>
                <li class='treeview'>
                        <a href='#'><i class='fa fa-flag'></i> <span>PriceofWeed raw data</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>

                     <ul class='treeview-menu'>
                           <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/pow_locations*')) ? 'active' : '' }}"><a href='{{Route("AdminPowLocationsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>POW Locations</span></a></li>
                             <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/pow_locations_avg_price*')) ? 'active' : '' }}"><a href='{{Route("AdminPowLocationsAvgPriceControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>POW Avg Prices</span></a></li>

                                 <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/pow_cities_submissions*')) ? 'active' : '' }}"><a href='{{Route("AdminPowCitiesSubmissionsControllerGetIndex")}}'><i
                                    class='fa fa-flag'></i> <span>POW Submissions</span></a></li>
                                 

                        </ul>
                </li>
                <li class='treeview'>
                    <a href='#'>
                        <i class='fa fa-flag'></i> <span>BC Provincial Store</span>
                         <i class="fa fa-angle-{{ trans('crudbooster.right') }} pull-{{ trans('crudbooster.right') }}"></i>
                    </a>

                    <ul class='treeview-menu'>
                        <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/leafy-strains*')) ? 'active' : '' }}"><a href='{{ Route("ScrapeBccControllerGetIndex") }}'><i
                        class='fa fa-flag'></i> <span>BC Provincial Store Category</span></a></li>
                        <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/bcc-strains*')) ? 'active' : '' }}"><a href='{{Route("AdminBccStrainsControllerGetIndex")}}'><i
                        class='fa fa-flag'></i> <span>BC Provincial Store Products</span></a></li>

                    </ul>
                </li>
                @endif
            </ul><!-- /.sidebar-menu -->

        </div>

    </section>
    <!-- /.sidebar -->
</aside>
