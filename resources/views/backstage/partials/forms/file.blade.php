<div class="grid grid-cols-4 gap-4 items-start pt-5">
    <label for="{{ $field }}" class="thunderbite-label">
        {{ $label }}
    </label>

    <div class="col-span-2">
        <div class="custom-file">
            <input type="file" name="{{ $field }}" value="{{ $value ?? '' }}" class="custom-file-input @error($field) is-invalid @enderror">
            <label class="custom-file-label" for="customFile"> {{ $cta ?? 'Click to upload a file'}}  </label>
        </div>


        @error($field)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="col-span-1">
        @if( isset($artwork) && array_key_exists($field, $artwork) )
            <img src="{{ $artwork[$field] }}" class="img-responsive w-16" />
        @endif

        @if( isset($value)  )
            <img src="{{ $value }}" class="img-responsive w-16" />
        @endif
    </div>
</div>
@once
    @push('scripts')
        <script>
            let customfiles = document.getElementsByClassName('custom-file-input');
            for (let i = 0; i < customfiles.length; i++) {
                customfiles[i].addEventListener('change', changeInputName, false)
            }

            function changeInputName(e){
                var fileName = e.target.files[0].name;
                var nextSibling = e.target.nextElementSibling
                nextSibling.innerText = fileName
            }

        </script>
    @endpush
@endonce
