<select @change="{{ $js }}" class="form-control" name="{{ $name }}" form="schedule-filter-form">
    <option {{ empty($selected) ? 'selected' : '' }} value=""></option>
    @foreach ($options as $value => $caption)
        <option {{ (string) $value === (string) $selected ? 'selected' : '' }} value="{{ $value }}">{{ $caption }}</option>
    @endforeach
</select>
