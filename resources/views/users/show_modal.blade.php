
<div class="mb-3 row">
    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
        <div class="col-md-6" style="line-height: 35px;">
            {{ $user->name }}
        </div>
    </div>

<div class="mb-3 row">
    <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Email Address:</strong></label>
        <div class="col-md-6" style="line-height: 35px;">
            {{ $user->email }}
        </div>
</div>

<div class="mb-3 row">
    <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Roles:</strong></label>
        <div class="col-md-6" style="line-height: 35px;">
            @forelse ($user->getRoleNames() as $role)
                <span class="badge bg-primary">{{ $role }}</span>
            @empty
            @endforelse
        </div>
</div>