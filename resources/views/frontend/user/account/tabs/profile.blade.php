<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered">
        <tr>
            <th>Username</th>
            <td>{{ $logged_in_user->preferred_username }}</td>
        </tr>
        <tr>
            <th>@lang('labels.frontend.user.profile.name')</th>
            <td>{{ $logged_in_user->name }}</td>
        </tr>
        <tr>
            <th>@lang('labels.frontend.user.profile.email')</th>
            <td>{{ $logged_in_user->email }}</td>
        </tr>
        <tr>
            @if ($logged_in_user->hasCreatedAt())
                <th>@lang('labels.frontend.user.profile.created_at')</th>
                <td>{{ timezone()->convertToLocal($logged_in_user->getCreatedAt()) }} ({{$logged_in_user->getCreatedAt()->diffForHumans() }})</td>
            @endif
        </tr>
    </table>
</div>
