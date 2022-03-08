<select class="custom-select {{ isset($class) ? $class : '' }}" name="{{ isset($name) ? $name : 'name[]' }}" {{ isset($required) ? "required" : '' }} @isset($attributes) @foreach($attributes as $key=>$attr) {{ $key }}="{{ $attr }}" @endforeach @endif>
    <option value="">--Pilih--</option>
    @if($options)
    @foreach($options as $key=>$optText)
    <option value="{{ $key }}" {{ isset($value)&&$value==$key ? 'selected' : '' }}>{{ $optText }}</option>
    @endforeach
    @endif
</select>