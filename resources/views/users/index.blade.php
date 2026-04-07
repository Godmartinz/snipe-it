@extends('layouts/default')
{{-- Page title --}}
@section('title')

    @if (request('status')=='deleted')
        {{ trans('general.deleted') }}
    @elseif (request('admins')=='true')
        {{ trans('general.show_admins') }}
    @elseif (request('superadmins')=='true')
        {{ trans('general.show_superadmins') }}
    @else
        {{ trans('general.current') }}
    @endif
    {{ trans('general.users') }}
    @parent

@stop

@section('header_right')

    @can('create', \App\Models\User::class)
        @if ($snipeSettings->ldap_enabled == 1)
            <a href="{{ route('ldap/user') }}" class="btn btn-theme pull-right"><i class="fas fa-sitemap"></i> {{trans('general.ldap_sync')}}</a>
        @endif
    @endcan
@stop

{{-- Page content --}}
@section('content')
    <x-container>
        <x-box>
            <x-table.users :route="route('api.users.index',
                [
                    'status' => e(request('status')),
                    'deleted'=> (request('status')=='deleted') ? 'true' : 'false',
                    'company_id' => e(request('company_id')),
                    'manager_id' => e(request('manager_id')),
                    'admins' => e(request('admins')),
                    'superadmins' => e(request('superadmins')),
                    'activated' => e(request('activated')),
               ])"/>
        </x-box>
    </x-container>


@stop

@section('moar_scripts')

    @include ('partials.bootstrap-table')

@stop
