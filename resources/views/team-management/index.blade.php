@extends('layouts.app')

@section('title', 'Team Management — StressLab')
@section('page-title', 'Team Management')

@section('styles')
<style>
    .page-header {
        margin-bottom: 1.5rem;
    }
    .page-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.04em;
        line-height: 1.15;
        margin-bottom: 0.2rem;
    }
    .page-header p {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    .card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .card-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--gray-2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-container {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        text-align: left;
    }
    th {
        padding: 0.75rem 1rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        border-bottom: 2px solid var(--gray-2);
    }
    td {
        padding: 0.75rem 1rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--gray-1);
    }
    td.font-semibold {
        font-weight: 600;
        color: var(--text-primary);
    }
    tr:last-child td {
        border-bottom: none;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid transparent;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-primary {
        background: var(--pink);
        color: white;
        box-shadow: 0 4px 10px rgba(219,39,119,0.2);
    }
    .btn-primary:hover {
        background: var(--pink-dark);
        transform: translateY(-1px);
    }
    .btn-danger {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fca5a5;
    }
    .btn-danger:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    .form-group {
        margin-bottom: 1rem;
    }
    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
        text-transform: uppercase;
    }
    .form-control {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid var(--gray-2);
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--text-primary);
        background: #f8fafc;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--pink);
        background: white;
    }

    .grid-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 768px) {
        .grid-layout {
            grid-template-columns: 1fr;
        }
    }
    
    .alert {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h1>{{ $team->name ?? 'Your Team' }} - Management</h1>
    <p>Manage researchers and access control for your team.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin:0; padding-left:1rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <!-- Researchers Table -->
    <div class="card">
        <div class="card-title">
            Team Researchers
            <span style="background: var(--pink-lt); color: var(--pink); padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.7rem;">{{ $researchers->count() }} Members</span>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date Added</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($researchers as $researcher)
                        <tr>
                            <td class="font-semibold">{{ $researcher->name }}</td>
                            <td>{{ $researcher->email }}</td>
                            <td>{{ $researcher->created_at ? $researcher->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td style="text-align: right;">
                                <form action="{{ route('team.destroy', $researcher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this researcher?');" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">No researchers found in your team.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>

@endsection
