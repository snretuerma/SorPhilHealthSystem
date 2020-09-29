<div>
    @if ($errors->any())
    <div class="bg-red-300">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>