<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Category</h4>
    </div>


    <div class="row">
        <div class="col-md-4">
            <ul class="list rounded-box shadow-md">
                @foreach ($users as $user)
                    <li class="list-row">
                        <div><img class="size-10 rounded-box"
                                src="https://img.daisyui.com/images/profile/demo/1@94.webp" /></div>
                        <div>
                            <div>{{ $user->name }}</div>
                            <div class="text-xs uppercase font-semibold opacity-60">Remaining Reason</div>
                        </div>
                    </li>   
                @endforeach

            </ul>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
