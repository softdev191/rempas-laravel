<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        <!-- =================================================== -->
        <!-- ========== Top menu items (ordered left) ========== -->
        <!-- =================================================== -->

    <!-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>Home</span></a></li> -->

        <!-- ========== End of top menu left items ========== -->
    </ul>
</div>


<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        @php $locale = session()->get('locale'); @endphp
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                @switch($locale)
                    @case('en')
                    <img src="{{asset('images/flag/uk.png')}}"> {{__('customer_msg.English')}}
                    @break
                    @case('fr')
                    <img src="{{asset('images/flag/fr.png')}}"> {{__('customer_msg.French')}}
                    @break
                    @case('es')
                    <img src="{{asset('images/flag/es.png')}}"> {{__('customer_msg.Spanish')}}
                    @break
                    @case('pt')
                    <img src="{{asset('images/flag/pt.png')}}"> {{__('customer_msg.Portuguese')}}
                    @break
                    @case('it')
                    <img src="{{asset('images/flag/it.png')}}"> {{__('customer_msg.Italian')}}
                    @break
                    @case('ja')
                    <img src="{{asset('images/flag/ja.png')}}"> {{__('customer_msg.Japanese')}}
                    @break
                    @case('nl')
                    <img src="{{asset('images/flag/nl.png')}}"> {{__('customer_msg.Dutch')}}
                    @break
                    @case('pl')
                    <img src="{{asset('images/flag/pl.png')}}"> {{__('customer_msg.Polish')}}
                    @break
                    @case('de')
                    <img src="{{asset('images/flag/de.png')}}"> {{__('customer_msg.German')}}
                    @break
                    @case('ru')
                    <img src="{{asset('images/flag/ru.png')}}"> {{__('customer_msg.Russian')}}
                    @break
                    @case('tr')
                    <img src="{{asset('images/flag/tr.png')}}"> {{__('customer_msg.Turkish')}}
                    @break
                    @case('no')
                    <img src="{{asset('images/flag/no.png')}}"> {{__('customer_msg.Norway')}}
                    @break
                    @case('se')
                    <img src="{{asset('images/flag/se.png')}}"> {{__('customer_msg.Sweden')}}
                    @break
                    @case('dk')
                    <img src="{{asset('images/flag/dk.png')}}"> {{__('customer_msg.Denmark')}}
                    @break
                    @default
                    <img src="{{asset('images/flag/uk.png')}}"> {{__('customer_msg.English')}}
                @endswitch
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/lang/en"><img class="flag-icon" src="{{asset('images/flag/uk.png')}}"> {{__('customer_msg.English')}}</a>
                <a class="dropdown-item" href="/lang/fr"><img class="flag-icon" src="{{asset('images/flag/fr.png')}}"> {{__('customer_msg.French')}} </a>
                <a class="dropdown-item" href="/lang/es"><img class="flag-icon" src="{{asset('images/flag/es.png')}}"> {{__('customer_msg.Spanish')}} </a>
                <a class="dropdown-item" href="/lang/pt"><img class="flag-icon" src="{{asset('images/flag/pt.png')}}"> {{__('customer_msg.Portuguese')}} </a>
                <a class="dropdown-item" href="/lang/it"><img class="flag-icon" src="{{asset('images/flag/it.png')}}"> {{__('customer_msg.Italian')}} </a>
                <a class="dropdown-item" href="/lang/ja"><img class="flag-icon" src="{{asset('images/flag/ja.png')}}"> {{__('customer_msg.Japanese')}} </a>
                <a class="dropdown-item" href="/lang/nl"><img class="flag-icon" src="{{asset('images/flag/nl.png')}}"> {{__('customer_msg.Dutch')}} </a>
                <a class="dropdown-item" href="/lang/pl"><img class="flag-icon" src="{{asset('images/flag/pl.png')}}"> {{__('customer_msg.Polish')}} </a>
                <a class="dropdown-item" href="/lang/de"><img class="flag-icon" src="{{asset('images/flag/de.png')}}"> {{__('customer_msg.German')}} </a>
                <a class="dropdown-item" href="/lang/ru"><img class="flag-icon" src="{{asset('images/flag/ru.png')}}"> {{__('customer_msg.Russian')}} </a>
                <a class="dropdown-item" href="/lang/tr"><img class="flag-icon" src="{{asset('images/flag/tr.png')}}"> {{__('customer_msg.Turkish')}} </a>
                <a class="dropdown-item" href="/lang/no"><img class="flag-icon" src="{{asset('images/flag/no.png')}}"> {{__('customer_msg.Norway')}} </a>
                <a class="dropdown-item" href="/lang/se"><img class="flag-icon" src="{{asset('images/flag/se.png')}}"> {{__('customer_msg.Sweden')}} </a>
                <a class="dropdown-item" href="/lang/dk"><img class="flag-icon" src="{{asset('images/flag/dk.png')}}"> {{__('customer_msg.Denmark')}} </a>
            </div>
        </li>
    </ul>
    <ul class="nav navbar-nav">
        <li>
        @if($user)
            <li class="dropdown user user-menu">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    @php
                        $avtarString = $user->full_name;
                        $avtarWords = explode(" ", $avtarString);
                        $allFirstLetters = "";
                        foreach ($avtarWords as $value) {
                            $allFirstLetters .= substr($value, 0, 1);
                        }
                    @endphp
                    <span class="user-image"><small>{{ $allFirstLetters }}</small></span>
                    <div class="right-user-data">
                        <span class="user-name hidden-xs dis-block">{{ $user->full_name }}</span>
                        <span class="company-name dis-block">{{ $company->name }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('account.info') }}"><i class="fa fa-btn fa-user"></i>Edit account</a></li>
                    <li>
                        @if($user->is_admin)
                            <a href="{{ route('admin.auth.logout') }}" onclick="event.preventDefault();
                                                                   document.getElementById('logout-form').submit();">
                                <i class="fa fa-btn fa-sign-out"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('admin.auth.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                   document.getElementById('logout-form').submit();">
                                <i class="fa fa-btn fa-sign-out"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </li>
                </ul>
            </li>

            @endif
            </li>
            <!-- ========== End of top menu right items ========== -->
    </ul>
</div>
