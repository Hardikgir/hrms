@extends('layouts.adminlte')

@section('title', __('messages.roles'))
@section('page_title', __('messages.roles'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.roles') }}</li>
@endsection

@section('content')
    {{-- Statistics Cards --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalRoles }}</h3>
                    <p>{{ __('messages.roles') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>{{ __('messages.user') }}s</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalPermissions }}</h3>
                    <p>{{ __('messages.permissions') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-key"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $roles->total() }}</h3>
                    <p>{{ __('messages.displayed') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-shield mr-2"></i>{{ __('messages.roles') }}</h3>
            <div class="card-tools">
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> {{ __('messages.add_role') }}
                </a>
                <a href="{{ route('user-roles.index') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-users"></i> {{ __('messages.manage_user_roles') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">
                <i class="fas fa-info-circle"></i> {{ __('messages.roles_description') }}
            </p>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-info"></i> {{ session('info') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-ban"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('roles.index') }}" class="form-inline">
                        <div class="form-group mr-2 mb-2">
                            <label class="mr-2"><i class="fas fa-search"></i> {{ __('messages.search') }}:</label>
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="{{ __('messages.search_placeholder') }}" 
                                   value="{{ request('search') }}" style="min-width: 200px;">
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <label class="mr-2"><i class="fas fa-building"></i> {{ __('messages.department') }}:</label>
                            <select name="department_id" class="form-control form-control-sm">
                                <option value="">{{ __('messages.all_departments') }}</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <label class="mr-2"><i class="fas fa-tag"></i> {{ __('messages.role_type') }}:</label>
                            <select name="role_type" class="form-control form-control-sm">
                                <option value="">{{ __('messages.all') }}</option>
                                <option value="admin" {{ request('role_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="manager" {{ request('role_type') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="employee" {{ request('role_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary mb-2">
                            <i class="fas fa-filter"></i> {{ __('messages.filter') }}
                        </button>
                        @if(request()->hasAny(['search', 'department_id', 'role_type']))
                            <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary mb-2">
                                <i class="fas fa-times"></i> {{ __('messages.reset') }}
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Quick Create Default Roles --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-bolt"></i> {{ __('messages.create_default_roles') }}</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('roles.create-for-department') }}" class="form-inline">
                                @csrf
                                <div class="form-group mr-2">
                                    <label class="mr-2">{{ __('messages.select_department') }}:</label>
                                    <select name="department_id" class="form-control form-control-sm" required>
                                        <option value="">{{ __('messages.select_department') }}</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-plus"></i> {{ __('messages.create') }}
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> {{ __('messages.create_default_roles_help') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Roles Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.department') }}</th>
                            <th>{{ __('messages.role_type') }}</th>
                            <th style="width: 120px;">{{ __('messages.user') }}s</th>
                            <th style="width: 120px;">{{ __('messages.permissions') }}</th>
                            <th style="width: 150px;">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if($role->name === 'Super Admin')
                                        <span class="badge badge-danger ml-2">
                                            <i class="fas fa-shield-alt"></i> {{ __('messages.protected') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($role->department)
                                        <span class="badge badge-info">
                                            <i class="fas fa-building"></i> {{ $role->department->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($role->role_type)
                                        @if($role->role_type === 'admin')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-user-cog"></i> {{ ucfirst($role->role_type) }}
                                            </span>
                                        @elseif($role->role_type === 'manager')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-user-tie"></i> {{ ucfirst($role->role_type) }}
                                            </span>
                                        @else
                                            <span class="badge badge-primary">
                                                <i class="fas fa-user"></i> {{ ucfirst($role->role_type) }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($role->users_count > 0)
                                        <a href="{{ route('user-roles.index') }}?role={{ $role->id }}" 
                                           class="badge badge-info" title="{{ __('messages.view_users') }}">
                                            <i class="fas fa-users"></i> {{ $role->users_count }}
                                        </a>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-users"></i> 0
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary" title="{{ __('messages.permissions') }}">
                                        <i class="fas fa-key"></i> {{ $role->permissions_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('roles.edit', $role) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="{{ __('messages.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($role->users_count > 0)
                                            <a href="{{ route('user-roles.index') }}?role={{ $role->id }}" 
                                               class="btn btn-sm btn-info" 
                                               title="{{ __('messages.view_users') }}">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        @endif
                                        @if($role->name !== 'Super Admin')
                                            <form action="{{ route('roles.destroy', $role) }}" 
                                                  method="POST" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('{{ __('messages.delete_role_confirm') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="{{ __('messages.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('messages.no_roles_found') }}</p>
                                    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> {{ __('messages.create_one') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($roles->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $roles->links('vendor.pagination.bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
