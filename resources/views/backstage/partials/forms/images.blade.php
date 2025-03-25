<div class="grid grid-cols-4 gap-4 items-start pt-5">
    <label for="{{ $field }}" class="thunderbite-label">
        {{ $label }}
    </label>

    <div class="col-span-2 imageupload">
        <div class="">
            <div class="imageupload__input">
                <input class="imageupload__file custom-file-input @error($field) is-invalid @enderror" type="file" name="{{ $field }}" value="{{ $value ?? '' }}"  id="{{ $field }}" data-multiple-caption="{count} files selected"  />
                <label for="{{ $field }}"><strong>Click to choose a file</strong><span class="imageupload__dragndrop"> or drag it here</span>.</label>
            </div>

            @error($field)
            <div class="imageupload__error">
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            </div>
            @enderror
        </div>

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
