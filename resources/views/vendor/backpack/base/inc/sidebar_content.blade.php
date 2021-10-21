<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@if($user->is_admin && !$user->is_staff)
    <li>
        <a href="{{ backpack_url('dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('customer') }}">
            <i class="fa fa-fw fa-users"></i> <span>{{ __('customer_msg.menu_Customers')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('staff') }}">
            <i class="fa fa-fw fa-users"></i> <span>{{ __('customer_msg.menu_Staffs')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('file-service') }}">
            <i class="fa fa-download"></i> <span>{{ __('customer_msg.menu_FileServices')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('tickets') }}">
            <i class="fa fa-comments"></i> <span>{{ __('customer_msg.menu_SupportTickets')}}</span>
            @if($tickets_count)
                <span class="pull-right-container">
              <small class="label pull-right bg-blue"><i class="fa fa-envelope"></i></small>
            </span>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('order') }}">
            <i class="fa fa-list"></i> <span>{{ __('customer_msg.menu_Orders')}}</span>
        </a>
    </li>

    <li>
        <a href="{{ backpack_url('transaction') }}">
            <i class="fa fa-fw fa-table"></i> <span>{{ __('customer_msg.menu_Transactions')}}</span>
        </a>
    </li>

    <li>
        <a href="{{ backpack_url('email-template') }}">
            <i class="fa fa-copy"></i> <span>{{ __('customer_msg.menu_EmailTemplates')}}</span>
        </a>
    </li>

    <li>
        <a href="{{ backpack_url('tuning-credit') }}">
            <i class="fa fa-list"></i> <span>{{ __('customer_msg.menu_TuningCredit')}}</span>
        </a>
    </li>
    @if($user->company->reseller_id)
    <li>
        <a href="{{ backpack_url('tuning-evc-credit') }}">
            <i class="fa fa-list"></i> <span>{{ __('customer_msg.menu_EVCTuningCredit')}}</span>
        </a>
    </li>
    @endif
    <li>
        <a href="{{ backpack_url('tuning-type') }}">
            <i class="fa fa-list"></i> <span>{{ __('customer_msg.menu_TuningTypes')}}</span>
        </a>
    </li>
    @if($user->is_master)
        <li>
            <a href="{{ backpack_url('company') }}">
                <i class="fa fa-list"></i> <span>{{ __('Manage Companies')}}</span>
            </a>
        </li>

        <li>
            <a href="{{ backpack_url('package') }}">
                <i class="fa fa-list"></i> <span>{{ __('Packages')}}</span>
            </a>
        </li>
        <li>
            <a href="{{ backpack_url('slidermanager') }}">
                <i class='fa fa-list'></i> <span>{{ __('SliderManager')}}</span>
            </a>
        </li>
    @else
        <li>
            <a href="{{ backpack_url('subscription') }}">
                <i class="fa fa-list"></i> <span>{{ __('customer_msg.menu_MySubscriptions')}}</span>
            </a>
        </li>
    @endif
    <li>
        <a href="{{ backpack_url('company-setting') }}">
            <i class="fa fa-cog"></i> <span>{{ __('customer_msg.menu_CompanySettings')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('/car/browser') }}">
            <i class='fa fa-list'></i> <span>{{ __('Browse Tuning Specs')}}</span>
        </a>
    </li>
@elseif ($user->is_staff)
    <li>
        <a href="{{ backpack_url('dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('customer') }}">
            <i class="fa fa-fw fa-users"></i> <span>{{ __('customer_msg.menu_Customers')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('file-service') }}">
            <i class="fa fa-download"></i> <span>{{ __('customer_msg.menu_FileServices')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('tickets') }}">
            <i class="fa fa-comments"></i> <span>{{ __('customer_msg.menu_SupportTickets')}}</span>
            @if($tickets_count)
                <span class="pull-right-container">
              <small class="label pull-right bg-blue"><i class="fa fa-envelope"></i></small>
            </span>
            @endif
        </a>
    </li>
@else
    <li>
        <a href="{{ backpack_url('buy-credits') }}">
            Tuning credits
            <h3 style="margin:5px 0px; font-size:3rem; font-weight:bold">{{ $user->user_tuning_credits }}</h3>
            <button class="btn btn-danger">Buy credits &nbsp;<i class="fa fa-arrow-right"></i></button>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('file-service') }}">
            <i class="fa fa-download"></i> <span>{{ __('customer_msg.menu_FileServices')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('tickets') }}">
            <i class="fa fa-comments"></i> <span>{{ __('customer_msg.menu_SupportTickets')}}</span>
            @if($tickets_count)
                <span class="pull-right-container">
              <small class="label pull-right bg-blue"><i class="fa fa-envelope"></i></small>
            </span>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('buy-credits') }}">
            <i class="fa fa-plus"></i> <span>{{ __('customer_msg.menu_BuyTuningCredits')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('order') }}">
            <i class="fa fa-list"></i> <span>{{ __('customer_msg.menu_Orders')}}</span>
        </a>
    </li>
    <li>
        <a href="{{ backpack_url('transaction') }}">
            <i class="fa fa-fw fa-table"></i> <span>{{ __('customer_msg.menu_Transactions')}}</span>
        </a>
    </li>
    @if ($user->company->link_name && $user->company->link_value)
        <li>
            <a href="{{ $user->company->link_value }}">
                <i class="fa fa-fw fa-external-link"></i> <span>{{ $user->company->link_name }}</span>
            </a>
        </li>
    @endif
    <li>
        <a href="{{ backpack_url('/car/browser') }}">
            <i class='fa fa-list'></i> <span>{{ __('Browse Tuning Specs')}}</span>
        </a>
    </li>
@endif


<style>
    .flag-icon{
        width: 16px;
        height: auto;
    }
    .dropdown-item{

        min-width: 180px;
        border-top-left-radius: calc(.25rem - 1px);
        border-top-right-radius: calc(.25rem - 1px);
        display: block;
        width: 100%;
        padding: .25rem 1.5rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        position: relative;
    }

</style>
