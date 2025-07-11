@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Method')</th>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="{{ route('admin.users.withdraw.method.permissions.update', $user->id) }}" method="POST">
                                    @csrf
                                    @foreach($withdrawMethods as $method)
                                        <tr>
                                            <td>
                                                <div class="user">
                                                    <div class="thumb">
                                                        <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $method->image) }}" alt="@lang('image')">
                                                    </div>
                                                    <span class="name">{{ __($method->name) }}</span>
                                                </div>
                                            </td>
                                            <td>{{ __($method->currency) }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="hidden" name="permissions[{{ $method->id }}]" value="0">
                                                    <input type="checkbox" name="permissions[{{ $method->id }}]" value="1" 
                                                        @if(isset($permissions[$method->id]) && $permissions[$method->id] == 1) checked @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('admin.users.withdraw.method.permissions.reset', $user->id) }}" 
                                                   class="btn btn--danger" 
                                                   onclick="return confirm('Are you sure you want to reset all withdrawal method permissions for this user?')">
                                                    @lang('Reset All')
                                                </a>
                                                <button type="submit" class="btn btn--primary">@lang('Save Changes')</button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }
    input:checked + .slider {
        background-color: #00a6f7;
    }
    input:focus + .slider {
        box-shadow: 0 0 1px #00a6f7;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
</style>
@endpush