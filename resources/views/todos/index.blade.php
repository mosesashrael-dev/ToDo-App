<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Laravel 12</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 50%, #eef2ff 100%);
            min-height: 100vh;
            color: #1e293b;
        }
        .app-wrapper {
            max-width: 820px;
        }
        .header-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        }
        .btn-add {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            border: none;
            border-radius: 12px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-add:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.25);
        }
        .filter-bar {
            background: #e2e8f0;
            padding: 5px;
            border-radius: 14px;
            display: inline-flex;
            gap: 4px;
        }
        .filter-bar .nav-link {
            color: #475569;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 10px;
            padding: 0.45rem 1.1rem;
            transition: all 0.2s ease;
        }
        .filter-bar .nav-link.active {
            background: #ffffff;
            color: #4f46e5;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }
        .todo-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.2s ease;
        }
        .todo-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }
        .todo-card.is-done {
            background: #f8fafc;
            border-color: #e2e8f0;
        }
        .custom-check {
            width: 1.45rem;
            height: 1.45rem;
            border-radius: 50% !important;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .custom-check:checked {
            background-color: #10b981;
            border-color: #10b981;
        }
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            transition: all 0.15s ease;
        }
        .action-btn.edit:hover {
            background: #eef2ff;
            color: #4f46e5;
            border-color: #c7d2fe;
        }
        .action-btn.delete:hover {
            background: #fef2f2;
            color: #ef4444;
            border-color: #fecaca;
        }
        .empty-card {
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    @php
        $progress = $totalTodos > 0 ? round(($completedTodos / $totalTodos) * 100) : 0;
    @endphp

    <div class="container py-5 app-wrapper">
        <!-- Header & Progress -->
        <div class="header-card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-4 text-white"
                         style="width: 48px; height: 48px; background: linear-gradient(135deg, #4f46e5, #3b82f6);">
                        <i class="bi bi-check2-square fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">To-Do List</h3>
                        <p class="text-secondary small mb-0">
                            <strong>{{ $completedTodos }}</strong> dari <strong>{{ $totalTodos }}</strong> tugas selesai
                        </p>
                    </div>
                </div>
                <a href="{{ route('todos.create') }}" class="btn btn-primary btn-add text-white">
                    <i class="bi bi-plus-lg me-1"></i> Buat ToDo Baru
                </a>
            </div>

            <!-- Progress Bar -->
            <div class="d-flex justify-content-between align-items-center small text-secondary mb-1">
                <span>Progres Penyelesaian</span>
                <span class="fw-semibold text-primary">{{ $progress }}%</span>
            </div>
            <div class="progress rounded-pill" style="height: 8px; background-color: #e2e8f0;">
                <div class="progress-bar rounded-pill" role="progressbar"
                     style="width: {{ $progress }}%; background: linear-gradient(90deg, #4f46e5, #10b981);"
                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>

        <!-- Alert Sukses -->
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="mb-4">
            <ul class="nav filter-bar">
                <li class="nav-item">
                    <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="{{ route('todos.index', ['filter' => 'all']) }}">
                        Semua
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $filter === 'pending' ? 'active' : '' }}" href="{{ route('todos.index', ['filter' => 'pending']) }}">
                        Belum Selesai
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $filter === 'completed' ? 'active' : '' }}" href="{{ route('todos.index', ['filter' => 'completed']) }}">
                        Selesai
                    </a>
                </li>
            </ul>
        </div>

        <!-- Daftar ToDo -->
        @if($todos->isEmpty())
            <div class="empty-card text-center py-5 px-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-secondary mb-3" style="width: 68px; height: 68px;">
                    <i class="bi bi-inbox fs-2"></i>
                </div>
                <h5 class="fw-semibold text-dark mb-1">Belum ada daftar ToDo di kategori ini</h5>
                <p class="text-secondary small mb-3">Mulai produktif dengan menambahkan tugas baru Anda.</p>
                <a href="{{ route('todos.create') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Tugas Pertama
                </a>
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($todos as $todo)
                    <div class="todo-card p-3 p-md-4 {{ $todo->is_completed ? 'is-done' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="d-flex align-items-start gap-3">
                                <form action="{{ route('todos.toggle', $todo->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" class="form-check-input custom-check"
                                           onchange="this.form.submit()" {{ $todo->is_completed ? 'checked' : '' }} title="Tandai selesai">
                                </form>

                                <div>
                                    <h5 class="fw-semibold mb-1 {{ $todo->is_completed ? 'text-decoration-line-through text-secondary' : 'text-dark' }}">
                                        {{ $todo->judul }}
                                    </h5>

                                    @if($todo->keterangan)
                                        <p class="text-secondary mb-2 small">{{ $todo->keterangan }}</p>
                                    @endif

                                    <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                                        @if($todo->is_completed && $todo->completed_at)
                                            <span class="badge rounded-pill bg-success-subtle text-success px-2 py-1 fw-medium">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Selesai: {{ $todo->completed_at->format('d M Y, H:i') }} WIB
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-2 py-1 fw-medium">
                                                <i class="bi bi-clock me-1"></i> Belum Selesai
                                            </span>
                                        @endif
                                        <small class="text-muted" style="font-size: 0.78rem;">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $todo->created_at->format('d M Y, H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('todos.edit', $todo->id) }}" class="action-btn edit" title="Edit">
                                    <i class="bi bi-pencil-fill" style="font-size: 0.85rem;"></i>
                                </a>
                                <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ToDo ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Hapus">
                                        <i class="bi bi-trash-fill" style="font-size: 0.85rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>