<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">Manajemen User</h4>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Seller Status</th>
                        <th>User Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{ $user->role }}
                            </td>
                            <td>
                                {{ $user->seller_status_label }}
                                @if ($user->seller_status == 1)
                                    <x-general.action-button icon="mdi:check-circle" variant="primary"
                                        onclick="approveSeller({{ $user->id }},'{{ $user->name }}')" />
                                    <x-general.action-button icon="mdi:close-circle" variant="danger"
                                        onclick="rejectSeller({{ $user->id }},'{{ $user->name }}')" />
                                @endif
                            </td>
                            <td>
                                {{ $user->status_label }}
                            </td>
                            <td class="text-center">
                                @if ($user->status == 1)
                                    <x-general.action-button icon="mdi:check-circle" title="nonaktifkan"
                                        onclick="disableduser({{ $user->id }},'{{ $user->name }}')" />
                                @elseif ($user->status == 0)
                                    <x-general.action-button icon="mdi:check-circle" title="actifkan"
                                        onclick="enableuser({{ $user->id }},'{{ $user->name }}')" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <form id="approve-form" method="POST" style="display:none;">
        @csrf
        @method('POST')
    </form>

    <form id="reject-form" action="{{ route('admin.users.reject', $user->id) }}" method="POST" style="display:none;">
        @csrf
        @method('POST')
    </form>
</x-app-layout>



<script>
    function approveSeller(id, name) {
        console.log(id, name);

        Swal.fire({
            title: 'Aprrove ke Seller?',
            text: `User ${name} akan di promosikan menjadi Seller`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('approve-form')
                form.action = `/admin/users/${id}/promote`
                form.submit()
            }
        })
    }

    function rejectSeller(id, name) {
        console.log(id, name);

        Swal.fire({
            title: 'Tolak permintaan Seller?',
            text: `User ${name} akan di ditolak untuk menjadi Seller`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`reject-form`).submit();
            }
        })
    }
</script>
