<div class="btn-group align-items-center gap-2">
    <a href="{{ route('users.destroy', $user->id) }}" class="bi bi-pencil-square mx-2" data-title="Edit User"
        data-url="{{ route('users.edit', $user->id) }}" id="editUserBtn" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"></a>
    <a href="" class="bi bi-trash text-danger deleteUserBtn" data-id="{{ $user->id }}"
        data-url="{{ route('users.destroy', $user->id) }}">
    </a>

</div>
