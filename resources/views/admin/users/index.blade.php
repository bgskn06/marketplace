<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">Manajemen User</h4>
    </x-slot>

    <div class="container mt-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($user->role === 'buyer')
                                        <form method="POST"
                                              action="{{ route('admin.users.promote', $user) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Promosikan user ini menjadi seller?')">
                                            @csrf
                                            <x-general.action-button
                                                icon="material-symbols:arrow-circle-up"
                                                color="red"
                                                title="Promote ke Seller"
                                                type="submit" />
                                        </form>
                                    @else
                                        <x-general.action-button
                                            icon="mdi:check-circle"
                                            color="gray"
                                            title="Sudah Seller"
                                            disabled />
                                    @endif

                                    @if($user->role === 'seller')
                                        <form method="POST"
                                              action="{{ route('admin.users.demote', $user) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Promosikan user ini menjadi buyer?')">
                                            @csrf
                                            <x-general.action-button
                                                icon="material-symbols:arrow-circle-down"
                                                color="red"
                                                title="Promote ke Seller"
                                                type="submit" />
                                        </form>
                                    @else
                                        <x-general.action-button
                                            icon="mdi:check-circle"
                                            color="gray"
                                            title="Sudah Buyer"
                                            disabled />
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-input-label value="name"/>
            <x-text-input/>
            <x-input-label value="address"/>
            <x-text-input/>
            <x-input-label value="detail"/>
            <x-text-input/>
        </div>

    </div>
</x-app-layout>
