@props([
    'id',
    'edit' => true,
    'delete' => true,
    'editData' => [],
    'editClass' => 'btn btn-primary btn-sm',
    'deleteClass' => 'btn btn-danger btn-sm',
    'editIcon' => 'bi bi-pencil-square',
    'deleteIcon' => 'bi bi-trash',
    'editText' => '',
    'deleteText' => '',
    'tooltip' => true,
    'canEdit' => true,
    'canDelete' => true,
])

<div class="btn-group">
    @if ($edit && $canEdit)
        <button type="button" class="{{ $editClass }} edit-data" data-id="{{ $id }}"
            @if ($tooltip) title="Edit" data-bs-toggle="tooltip" @endif
            @foreach ($editData as $key => $value)
                data-{{ $key }}="{{ $value }}" @endforeach>
            <i class="{{ $editIcon }}"></i>
            @if ($editText)
                <span class="ms-1">{{ $editText }}</span>
            @endif
        </button>
    @endif

    @if ($delete && $canDelete)
        <button type="button" class="{{ $deleteClass }} delete-data" data-id="{{ $id }}"
            @if ($tooltip) title="Delete" data-bs-toggle="tooltip" @endif>
            <i class="{{ $deleteIcon }}"></i>
            @if ($deleteText)
                <span class="ms-1">{{ $deleteText }}</span>
            @endif
        </button>
    @endif
</div>

@if ($tooltip)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endif
