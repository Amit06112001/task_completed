<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .completed {  color: gray; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .task-completed {
            color: grey;
        }
        .task-box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .task-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="task-box mx-auto" style="max-width: 700px;">
            <div class="mb-3 d-flex align-items-center">
                <input type="checkbox" id="showAll" class="form-check-input me-2" >
                <label for="showAll" class="form-label mb-0">Show All Tasks</label>
            </div>
    
            <div class="input-group mb-3">
                <input type="text" id="taskInput" class="form-control" placeholder="Project # To Do" />
                <button class="btn btn-success" id="addBtn">Add</button>
            </div>
    
            <ul class="list-group" id="taskList">
                @foreach ($task as $row)
                <li class="list-group-item d-flex align-items-center justify-content-between" data-id="{{ $row->id }}">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2 mark-complete" {{ $row->is_completed ? 'checked' : '' }} />
                        <span class="{{ $row->is_completed ? 'task-completed' : '' }}">{{ $row->title }}</span>
                        <small class="ms-3 text-muted">a few seconds ago</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('image/test.jpg') }}" class="task-avatar" />
                        <button class="btn btn-sm btn-outline-danger delete-btn">delete</button>
                    </div>
                </li>
            @endforeach
              
            </ul>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let showAll = true;

    // fetchTasks();

    function fetchTasks() {
        $.get('/tasks-all', function (tasks) {
            let html = '';
            tasks.forEach(task => {
                if (!showAll && task.is_completed) return;

                html += `
                    <li class="list-group-item d-flex align-items-center justify-content-between ${task.is_completed ? 'task-completed' : ''}" data-id="${task.id}">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2 mark-complete" ${task.is_completed ? 'checked' : ''} />
                            <span>${task.title}</span>
                            <small class="ms-3 text-muted">a few seconds ago</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('image/test.jpg') }}" class="task-avatar" />
                            <button class="btn btn-sm btn-outline-danger delete-btn">delete</button>
                        </div>
                    </li>`;
            });
            $('#taskList').html(html);
        });
    }

    $('#taskInput').keypress(function (e) {
        if (e.which === 13) $('#addBtn').click();
    });

    $('#addBtn').click(function () {
    let title = $('#taskInput').val().trim();
    if (!title) return alert("Task cannot be empty!");

    $.post('/tasks', { title, _token: '{{ csrf_token() }}' }, function () {
        $('#taskInput').val('');

        $.get('/', function (html) {
            const newList = $('<div>').html(html).find('#taskList').html();
            $('#taskList').html(newList);
        });

    }).fail(function (res) {
        alert(res.responseJSON.message);
    });
});

$(document).on('change', '.mark-complete', function () {
    const id = $(this).closest('li').data('id');

    $.post(`/tasks/${id}/complete`, { _token: '{{ csrf_token() }}' }, function () {
        $.get('/', function (html) {
            const newList = $('<div>').html(html).find('#taskList').html();
            $('#taskList').html(newList);
        });
    });
});

    $(document).on('click', '.delete-btn', function () {
        if (!confirm("Are you sure to delete this task?")) return;

        const id = $(this).closest('li').data('id');
        $.ajax({
            url: `/tasks/${id}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: fetchTasks
        });
    });

    $('#showAll').change(function () {
        showAll = this.checked;
        fetchTasks();
    });
</script>

</body>
</html>
